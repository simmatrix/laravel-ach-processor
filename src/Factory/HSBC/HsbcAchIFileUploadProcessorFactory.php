<?php

namespace Simmatrix\ACHProcessor\Factory\HSBC;

use Simmatrix\ACHProcessor\Services\CoreHelper;
use Simmatrix\ACHProcessor\Factory\HSBC\Header\HSBCFileHeaderIFile;
use Simmatrix\ACHProcessor\Factory\HSBC\Header\HSBCBatchHeaderIFile;
use Simmatrix\ACHProcessor\Factory\HSBC\HSBCBeneficiaryIFileFactory;
use Simmatrix\ACHProcessor\Adapter\Beneficiary\BeneficiaryAdapterInterface;
use Simmatrix\ACHProcessor\ACHUploadProcessor;

use Illuminate\Config\Repository;

class HsbcAchIFileUploadProcessorFactory
{
    /**
     * @param Collection of entries to be passed into the adapter
     * @param String The key to read the config from
     * @param String The payment description
     * @return ACHUploadProcessor
     */
    public static function create($beneficiaries, $config_key, $payment_description)
    {
        $helper = new CoreHelper( $config_key );
        $config = new Repository(config($config_key));
        $adapter_class = $config['beneficiary_adapter'];

        $beneficiaries = $beneficiaries -> map( function($payment) use($adapter_class){
            return new $adapter_class($payment);
        }) -> toArray();

        $ach = new ACHUploadProcessor($beneficiaries);

        $file_header = new HSBCFileHeaderIFile($beneficiaries, $config_key, $payment_description);
        $file_header -> setColumnDelimiter(",");

        $batch_header = new HSBCBatchHeaderIFile($beneficiaries, $config_key, $payment_description);
        $batch_header -> setColumnDelimiter(",");

        $beneficiary_lines = collect($beneficiaries) -> map( function(BeneficiaryAdapterInterface $beneficiary) use ($config_key, $payment_description){
            return HSBCBeneficiaryIFileFactory::create($beneficiary, $config_key, $payment_description);
        }) -> toArray();
        
        $ach -> setFileHeader($file_header);
        $ach -> setBatchHeader($batch_header);
        $ach -> setBeneficiaryLines($beneficiary_lines);

        $ach -> setIdentifier($helper -> getFileReference());
        $ach -> setFileName('hsbc_ach_ifile_'.time());
        $ach -> setFileExtension('csv');

        return $ach;
    }
}
