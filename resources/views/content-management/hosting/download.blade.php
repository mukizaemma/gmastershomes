<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Request {{ $invoice->number }}</title>
    @include('content-management.hosting.styles')
    <style>
        body { margin: 0; background: #fff; }
        .pay-request { border: 0; }
        @page { margin: 16mm; }
    </style>
</head>
<body>
    @include('content-management.hosting.document')
</body>
</html>
