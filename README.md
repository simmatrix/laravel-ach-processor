# ACH Processor for Laravel

Handles direct bank transfer (ACH payment). Currently supports HSBC and UOB banks. Generates files based on payment entries.

## Acknowledgement

This is built based on [Laravel COS Processor](https://github.com/chalcedonyt/laravel-cos-processor)

## Install

Via Composer

``` bash
$ composer require simmatrix/laravel-ach-processor
```

## Creating an Adapter

Create an adapter that implements `Simmatrix\ACHProcessor\Adapter\Beneficiary\BeneficiaryAdapterInterface`. This should translate your model into the attributes that will be used in the ACH entries. Refer to `Simmatrix\ACHProcessor\Adapter\Beneficiary\ExampleBeneficiaryAdapter` for an example.

## Configuration

`php artisan vendor:publish` should publish a `ach_processor.php` into the config folder. Edit this with the configuration options for your account. Change `"beneficiary_adapter"` to the class of the adapter you created earlier.


## Usage - Generating a file to upload

Call the relevant `ACHUploadProcessorFactory` subclass (either `HsbcAchUploadProcessorFactory` or `UobAchUploadProcessorFactory`) and pass in your beneficiaries, config key, and the payment description.


``` php
$beneficiaries = TestPayment::all();
$ach = HsbcAchUploadProcessorFactory::create($beneficiaries, 'ach_processor.hsbc.company_a', 'CashoutOct17');
echo $ach -> getString();
```

``` php
$beneficiaries = TestPayment::all();
$ach = UobAchUploadProcessorFactory::create($beneficiaries, 'ach_processor.uob.company_a', 'CashoutOct17');
echo $ach -> getString();
```

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.
