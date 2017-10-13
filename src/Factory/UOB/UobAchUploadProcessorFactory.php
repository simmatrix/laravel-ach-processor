<?php

namespace Simmatrix\ACHProcessor\Factory\UOB;

use Simmatrix\ACHProcessor\Factory\UOB\Header\UOBBatchHeader;
use Simmatrix\ACHProcessor\Factory\UOB\Header\UOBBatchTrailer;
use Simmatrix\ACHProcessor\Factory\UOB\UOBBeneficiaryFactory;

use Simmatrix\ACHProcessor\Adapter\Beneficiary\BeneficiaryAdapterInterface;
use Simmatrix\ACHProcessor\ACHUploadProcessor;

use Illuminate\Config\Repository;

class UobAchUploadProcessorFactory
{
    /**
     * @param Collection of entries to be passed into the adapter
     * @param String The config key to read from
     * @param Int The sequence number for file name generation. If multiple files are generated in a day, this number should be incremented. Can go up till 99 per day
     * @return ACHUploadProcessor
     */
    public static function create($beneficiaries, $config_key, $payment_description = '', $sequence_number = 1)
    {
        $config = new Repository(config($config_key));
        $adapter_class = $config['beneficiary_adapter'];

        $beneficiaries = $beneficiaries -> map( function($payment) use($adapter_class){
            return new $adapter_class($payment);
        }) -> toArray();

        $beneficiary_lines = collect($beneficiaries) -> map( function(BeneficiaryAdapterInterface $beneficiary) use($config_key, $payment_description){
            return UOBBeneficiaryFactory::create($beneficiary, $config_key, $payment_description);
        }) -> toArray();

        $ach = new ACHUploadProcessor($beneficiaries);

        $file_name = static::getFileName($sequence_number);
        $ach -> setFileName($file_name);
        $ach -> setFileExtension('txt');

        $batch_header = new UOBBatchHeader($beneficiaries, $config_key);
        $batch_header -> setColumnDelimiter("");

        $batch_trailer = new UOBBatchTrailer($beneficiaries, $config_key);
        $batch_trailer -> setColumnDelimiter("");

        $ach -> setBatchHeader($batch_header);
        $ach -> setBatchTrailer($batch_trailer);
        $ach -> setBeneficiaryLines($beneficiary_lines);
        $ach -> setIdentifier($batch_trailer -> getCheckSum());
        return $ach;
    }

    /**
    * @return String
    */
    public static function getFileName($sequence_number){
        return sprintf("UITI%s%s", date('dm'), str_pad($sequence_number, 2, STR_PAD_LEFT, '0') );
    }
}
