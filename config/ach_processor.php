<?php

return [

    'hsbc' => [

        'company_a' => [

            'beneficiary_adapter' => \Simmatrix\ACHProcessor\Adapter\ExampleBeneficiaryAdapter::class,

            // [COMPULSORY] Batch Header Record
            'first_party_account_branch' => '123',        // maximum length: 3 - ** Please define this yourself **
            'first_party_account_serial' => '123456',     // maximum length: 6 - ** Please define this yourself **
            'first_party_account_suffix' => '001',        // maximum length: 3 - ** Please define this yourself **
            'payment_set_number' => 'C01',              // maximum length: 3 - ** Please define this yourself **

            // [OPTIONAL] Batch Header Record
            'country_code' => 'MY',                     // maximum length: 2
            'group_member' => 'HSBC',                   // maximum length: 4
            'payment_type' => 'APO',                    // maximum length: 3  - APO stands for Auto Pay Out (Debit 1st party, Credit 2nd party)
            'hexagon_customer_id' => '',                // maximum length: 12 - For E-Billing Solutions Payment only, else, leave this blank
            'hexagon_account_id' => '',                 // maximum length: 4  - For E-Billing Solutions Payment only, else, leave this blank

            // Data Record
            'autopay_currency' => 'MYR',                // maximum length: 3
            'payment_currency' => 'MYR',                // maximum length: 3

        ],

        // If you have a subsidiary company, you can copy the structure of the key above
        'company_b' => [

            'beneficiary_adapter' => \Simmatrix\ACHProcessor\Adapter\ExampleBeneficiaryAdapter::class,

            // [COMPULSORY] Batch Header Record
            'first_party_account_branch' => '123',        // maximum length: 3 - ** Please define this yourself **
            'first_party_account_serial' => '123456',     // maximum length: 6 - ** Please define this yourself **
            'first_party_account_suffix' => '001',        // maximum length: 3 - ** Please define this yourself **
            'payment_set_number' => 'C01',               // maximum length: 3 - ** Please define this yourself **

            // [OPTIONAL] Batch Header Record
            'country_code' => 'MY',                     // maximum length: 2
            'group_member' => 'HSBC',                   // maximum length: 4
            'payment_type' => 'APO',                    // maximum length: 3  - APO stands for Auto Pay Out (Debit 1st party, Credit 2nd party)
            'hexagon_customer_id' => '',                // maximum length: 12 - For E-Billing Solutions Payment only, else, leave this blank
            'hexagon_account_id' => '',                 // maximum length: 4  - For E-Billing Solutions Payment only, else, leave this blank

            // Data Record
            'autopay_currency' => 'MYR',                // maximum length: 3
            'payment_currency' => 'MYR',                // maximum length: 3

        ],

    ],

    'uob' => [

        'company_a' => [

            'beneficiary_adapter' => \Simmatrix\ACHProcessor\Adapter\ExampleBeneficiaryAdapter::class,

            // [COMPULSORY] Batch Header
            'service_type' => 'IBGINORM',                   // Maximum length: 10. 'IBGINORM' is normal service. Another option is 'IBGIEXP' which is express service, of which all receiving accounts must belong to UOB
            'originating_bank_code' => '',                  // Maximum length: 4
            'originating_branch_code' => '',                // Maximum length: 3
            'originating_account_number' => '',             // Maximum length: 11
            'originating_account_name' => 'LOREM IPSUM',    // Maximum length: 20 - CAPITAL LETTER ONLY

            // Batch Detail

            // For Payroll, use '22' (Salary Credit)
            // For Collection, use '30' (Direct Debit)
            // For Payments, use '20' (Miscellaneous Credit), '23' (Dividend Credit), '24' (Remittance Credit), '25' (Bill Credit)
            'transaction_code' => '20',

        ],

        'company_b' => [

            'beneficiary_adapter' => \Simmatrix\ACHProcessor\Adapter\ExampleBeneficiaryAdapter::class,

            // [COMPULSORY] Batch Header
            'service_type' => 'IBGINORM',                   // Maximum length: 10. 'IBGINORM' is normal service. Another option is 'IBGIEXP' which is express service, of which all receiving accounts must belong to UOB
            'originating_bank_code' => '',                  // Maximum length: 4
            'originating_branch_code' => '',                // Maximum length: 3
            'originating_account_number' => '',             // Maximum length: 11
            'originating_account_name' => 'LOREM IPSUM',    // Maximum length: 20 - CAPITAL LETTER ONLY

            // Batch Detail

            // For Payroll, use '22' (Salary Credit)
            // For Collection, use '30' (Direct Debit)
            // For Payments, use '20' (Miscellaneous Credit), '23' (Dividend Credit), '24' (Remittance Credit), '25' (Bill Credit)
            'transaction_code' => '20',

        ],

    ]

];

?>
