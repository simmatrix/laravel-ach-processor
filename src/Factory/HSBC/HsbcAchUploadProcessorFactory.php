<?php

namespace Simmatrix\ACHProcessor\Factory\HSBC;

use Simmatrix\ACHProcessor\Factory\HSBC\Header\HSBCBatchHeader;
use Simmatrix\ACHProcessor\Factory\HSBC\HSBCBeneficiaryFactory;
use Simmatrix\ACHProcessor\Adapter\Beneficiary\BeneficiaryAdapterInterface;
use Simmatrix\ACHProcessor\ACHUploadProcessor;

use Illuminate\Config\Repository;

class HsbcAchUploadProcessorFactory
{
    /**
     * @param Collection of entries to be passed into the adapter
     * @param String The key to read the config from
     * @param String The payment description
     * @return ACHUploadProcessor
     */
    public static function create($beneficiaries, $config_key, $payment_description)
    {
        $config = new Repository(config($config_key));
        $adapter_class = $config['beneficiary_adapter'];

        $beneficiaries = $beneficiaries -> map( function($payment) use($adapter_class){
            return new $adapter_class($payment);
        }) -> toArray();

        $beneficiary_lines = collect($beneficiaries) -> map( function(BeneficiaryAdapterInterface $beneficiary) use ($config_key){
            return HSBCBeneficiaryFactory::create($beneficiary, $config_key);
        }) -> toArray();

        $ach = new ACHUploadProcessor($beneficiaries);
        $batch_header = new HSBCBatchHeader($beneficiaries, $config_key, $payment_description);

        $ach -> setBatchHeader($batch_header);
        $ach -> setBeneficiaryLines($beneficiary_lines);
        $ach -> setIdentifier($batch_header -> getFileReference());
        $ach -> setFileName('hsbc_ach_'.time());
        $ach -> setFileExtension('csv');
        return $ach;
    }
}
