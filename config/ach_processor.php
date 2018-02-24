<?php

return [

    'hsbc_mri' => [

        'company_a' => [

            'beneficiary_adapter' => \Simmatrix\ACHProcessor\Adapter\ExampleBeneficiaryAdapter::class,

            'file_reference_prefix' => 'BP',

            // [COMPULSORY] Batch Header Record
            'first_party_account_branch' => '123',       // maximum length: 3 - ** Please define this yourself **
            'first_party_account_serial' => '123456',    // maximum length: 6 - ** Please define this yourself **
            'first_party_account_suffix' => '001',       // maximum length: 3 - ** Please define this yourself **
            'payment_set_number' => 'C01',               // maximum length: 3 - ** Please define this yourself **
            'effective_working_days' => '1',             // maximum length: 2 - how many days would it take for the amount to be debited from the first party, after the generation of this ACH file

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

            'file_reference_prefix' => 'BP',
            
            // [COMPULSORY] Batch Header Record
            'first_party_account_branch' => '123',       // maximum length: 3 - ** Please define this yourself **
            'first_party_account_serial' => '123456',    // maximum length: 6 - ** Please define this yourself **
            'first_party_account_suffix' => '001',       // maximum length: 3 - ** Please define this yourself **
            'payment_set_number' => 'C01',               // maximum length: 3 - ** Please define this yourself **
            'effective_working_days' => '1',             // maximum length: 2 - how many days would it take for the amount to be debited from the first party, after the generation of this ACH file

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

    'hsbc_ifile' => [

        'company_a' => [

            'beneficiary_adapter' => \Simmatrix\ACHProcessor\Adapter\ExampleBeneficiaryAdapter::class,

            'effective_working_days'                => '1',         // maximum length: 2 - how many days would it take for the amount to be debited from the first party, after the generation of this ACH file

            // File  Header
            'hexagon_abc_customer_id'               => '[MISSING]', // To be provided by HSBC
            'hsbcnet_id'                            => '[MISSING]', // To be provided by HSBC
            'file_reference_prefix'                 => 'IFILEPYT_',
            'authorization_type'                    => 'P',         // A: Preauthorized, P: Instruction level authorization, F: File level authorization (summary), V: File level authorization (detail)

            // Batch Header
            'instruction_type'                      => 'ACH-CR',    // ACH-CR: Payment, ACH-DR: Collection of Debts
            'first_party_account'                   => '[MISSING]',
            'first_party_account_country_code'      => 'MY',
            'first_party_account_institution_code'  => 'HBMB',
            'first_party_account_currency'          => 'MYR',
            'transaction_currency'                  => 'MYR',
            'payment_set_number'                    => 'C01',      

            // Advising Record
            'email_channel_select_flag'             => 'Y',         // "Y" - yes, or leave blank
            'email_format'                          => '1',         // 1 - PDF, 2 - CSV. Mandatory if email_channel_select_flag is "Y"
            'domicile_of_email_recipient'           => 'MY'

        ],

        // If you have a subsidiary company, you can copy the structure of the key above

        'company_b' => [

            'beneficiary_adapter' => \Simmatrix\ACHProcessor\Adapter\ExampleBeneficiaryAdapter::class,

            'effective_working_days'                => '1',         // maximum length: 2 - how many days would it take for the amount to be debited from the first party, after the generation of this ACH file

            // File  Header
            'hexagon_abc_customer_id'               => '[MISSING]', // To be provided by HSBC
            'hsbcnet_id'                            => '[MISSING]', // To be provided by HSBC
            'file_reference_prefix'                 => 'IFILEPYT_',
            'authorization_type'                    => 'P',         // A: Preauthorized, P: Instruction level authorization, F: File level authorization (summary), V: File level authorization (detail)

            // Batch Header
            'instruction_type'                      => 'ACH-CR',    // ACH-CR: Payment, ACH-DR: Collection of Debts
            'first_party_account'                   => '[MISSING]',
            'first_party_account_country_code'      => 'MY',
            'first_party_account_institution_code'  => 'HBMB',
            'first_party_account_currency'          => 'MYR',
            'transaction_currency'                  => 'MYR',
            'payment_set_number'                    => 'C01',      

            // Advising Record
            'email_channel_select_flag'             => 'Y',         // "Y" - yes, or leave blank
            'email_format'                          => '1',         // 1 - PDF, 2 - CSV. Mandatory if email_channel_select_flag is "Y"
            'domicile_of_email_recipient'           => 'MY'

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
            'effective_working_days' => '1',                // Maximum length: 2 - How many working days later (after generating this ACH file) that the amount of money get passed to the beneficiary / collected from debtor)

            // [OPTIONAL] Batch Detail

            // For Payroll      : use '22' (Salary Credit)
            // For Collection   : use '30' (Direct Debit)
            // For Payments     : use '20' (Miscellaneous Credit), '23' (Dividend Credit), '24' (Remittance Credit), '25' (Bill Credit)
            'transaction_code' => '20',

            // Only fill this up if your set '30' as your transaction code (If you are doing debt collection)
            'reference' => '',

        ],

        'company_b' => [

            'beneficiary_adapter' => \Simmatrix\ACHProcessor\Adapter\ExampleBeneficiaryAdapter::class,

            // [COMPULSORY] Batch Header

            'service_type' => 'IBGINORM',                   // Maximum length: 10. 'IBGINORM' is normal service. Another option is 'IBGIEXP' which is express service, of which all receiving accounts must belong to UOB
            'originating_bank_code' => '',                  // Maximum length: 4
            'originating_branch_code' => '',                // Maximum length: 3
            'originating_account_number' => '',             // Maximum length: 11
            'originating_account_name' => 'LOREM IPSUM',    // Maximum length: 20 - CAPITAL LETTER ONLY
            'effective_working_days' => '1',                // Maximum length: 1 - How many working days later (after generating this ACH file) that the amount of money get passed to the beneficiary / collected from debtor)

            // [OPTIONAL] Batch Detail

            // For Payroll      : use '22' (Salary Credit)
            // For Collection   : use '30' (Direct Debit)
            // For Payments     : use '20' (Miscellaneous Credit), '23' (Dividend Credit), '24' (Remittance Credit), '25' (Bill Credit)
            'transaction_code' => '20',

            // Only fill this up if your set '30' as your transaction code (If you are doing debt collection)
            'reference' => '',

        ],

    ]

];

?>
