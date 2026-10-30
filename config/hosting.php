<?php

return [

    /*
    | Annual hosting is issued on 1 October and covers through 30 September.
    | The 2026/2027 invoice is the year already paid at handover.
    */
    'prepaid_period_start' => '2026-10-01',

    'hosting_amount' => 80,

    'currency' => 'USD',

    'client' => 'GMasters Homes',

    'client_website' => 'https://gmastershomes.com',

    'handover_url' => 'https://gmastershomes.com/handover',

    'issuer' => 'Ireme Technologies',

    'issuer_legal' => 'Ireme Technologies Ltd',

    'issuer_lines' => [
        'KG 622 ST, Fair View Building, 4th Floor',
        'Kimihurura – Gasabo – Kigali City',
    ],

    'issuer_phone' => '+250 783 807 409',

    'issuer_email' => 'info@iremetech.com',

    'prepared_by' => 'Emma TWAGIRUMUKIZA',

    'usd_to_rwf' => 1530,

    'rate_editor_email' => 'admin@iremetech.com',

    'timezone' => 'Africa/Kigali',

    'payment_methods' => [
        [
            'name' => 'Bank Transfer',
            'lines' => [
                'Account No: 4491274576',
                'Bank: BPR',
                'Names: Ireme Technologies',
            ],
        ],
        [
            'name' => 'MoMo Pay',
            'lines' => [
                '20033',
                'Ireme Technologies',
            ],
        ],
    ],

];
