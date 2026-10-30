<?php

namespace App\Support;

class PlainPdf
{
    /**
     * @param  list<array{text: string, size?: int}>  $lines
     */
    public static function render(array $lines): string
    {
        $commands = ['BT'];
        $y = 800;

        foreach ($lines as $line) {
            if ($y < 48) {
                break;
            }

            $size = max(9, (int) ($line['size'] ?? 11));
            $text = self::escape((string) ($line['text'] ?? ''));
            $commands[] = '/F1 '.$size.' Tf';
            $commands[] = '1 0 0 1 48 '.$y.' Tm';
            $commands[] = '('.$text.') Tj';
            $y -= ($size + 6);
        }

        $commands[] = 'ET';

        return self::document(implode("\n", $commands));
    }

    private static function escape(string $text): string
    {
        $text = str_replace(["\\", '(', ')'], ['\\\\', '\\(', '\\)'], $text);
        $converted = iconv('UTF-8', 'Windows-1252//TRANSLIT', $text);

        return $converted === false ? preg_replace('/[^\x20-\x7E]/', '', $text) : $converted;
    }

    private static function document(string $stream): string
    {
        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>',
            "<< /Length ".strlen($stream)." >>\nstream\n".$stream."\nendstream",
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [];

        foreach ($objects as $index => $body) {
            $offsets[] = strlen($pdf);
            $pdf .= ($index + 1)." 0 obj\n".$body."\nendobj\n";
        }

        $xref = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n";
        $pdf .= "0000000000 65535 f \n";

        foreach ($offsets as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        $pdf .= "trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\n";
        $pdf .= "startxref\n".$xref."\n%%EOF";

        return $pdf;
    }
}
