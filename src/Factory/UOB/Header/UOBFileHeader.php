<?php

namespace Simmatrix\ACHProcessor\Factory\UOB\Header;

use Simmatrix\ACHProcessor\Line\Line;
use Simmatrix\ACHProcessor\Line\Header;
use Simmatrix\ACHProcessor\Factory\UOB\UOBBeneficiaryFactory;
use Simmatrix\ACHProcessor\Beneficiary;
use Simmatrix\ACHProcessor\BeneficiaryLine;
use Simmatrix\ACHProcessor\Column\Date;
use Simmatrix\ACHProcessor\Factory\Column\ConfigurableStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\EmptyColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\LeftPaddedDecimalWithoutDelimiterColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\LeftPaddedZerofillStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\PresetStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\RightPaddedStringColumnFactory;
use Simmatrix\ACHProcessor\Factory\Column\VariableLengthStringColumnFactory;


class UOBFileHeader extends Header
{
    protected $columnDelimiter = "";

    /**
    * @var String
    */
    protected $fileName;

    /**
     * @return Line
     */
    public function getLine(){
        $line = new Line();
        $line -> setColumnDelimiter("");

        $columns = [
            'record_type'       => PresetStringColumnFactory::create('0', $label = 'record_type'),
            'file_name'         => VariableLengthStringColumnFactory::create( $this -> fileName, 10, $label = 'file_name'),
            'creation_date'     => RightPaddedStringColumnFactory::create( date('Ymd'), 8, $label = 'creation_date'),
            'creation_time'     => RightPaddedStringColumnFactory::create( date('His'), 6, $label = 'creation_time'),
            'company_id'        => ConfigurableStringColumnFactory::create( $config = $this -> config, 'company_id', $label = 'company_id'),
            'check_sum'         => LeftPaddedZerofillStringColumnFactory::create( self::getCheckSum(), 15, $label = 'check_sum'),
            'company_id_bib'    => ConfigurableStringColumnFactory::create( $config = $this -> config, 'company_id', $label = 'company_id_bib'),
            'filler'            => RightPaddedStringColumnFactory::create('', 836, $label = 'filler')
        ];
        $line -> setColumns($columns);
        return $line;
    }

    /**
     * Calculate the checksum of the file
     * As defined in the UOB documentation
     * @return String
     */
    public function getCheckSum(){
        $headers = [];
        $checksum = 0;

        $hashcode_index = 1;
        $record_no = 1;

        $lines = [];
        $batch_header = new UOBBatchHeader($this -> beneficiaries, $this -> configKey);
        $lines[] = $batch_header -> getLine();
        foreach ($this -> beneficiaries as $beneficiary){
            $beneficiary_lines = UOBBeneficiaryFactory::create($beneficiary, $this -> configKey);
            $lines[]= collect($beneficiary_lines -> getLines()) -> first();
        }
        $batch_trailer = new UOBBatchTrailer($this -> beneficiaries, $this -> configKey);
        $lines[] = $batch_trailer -> getLine();

        foreach ($lines as $line){
            // printf("\nLine with value %s has length of %d", substr($line -> getString(),0, 100), strlen($line -> getString()));
            $string = $line -> getString();
            $record_no++;
            $column_no = 1;
            for( $i = 0; $i < 900; $i++){
              $byte_code = ord($string[$i]);
              if($hashcode_index > 23) $hashcode_index = 1;

              $checksum += $record_no + ($record_no + $column_no) * $byte_code * $this -> getHashCodeArray($hashcode_index);
              $hashcode_index++;
              $column_no++;
            }
        }

        return $checksum;
    }

    /**
     * Returns a hashcode as defined in the UOB documentation
     * @param int
     * @return int
     */
    private function getHashCodeArray($index){
        $hashcode_array = [
            '1' => '23',
            '2' => '05',
            '3' => '17',
            '4' => '20',
            '5' => '04',
            '6' => '13',
            '7' => '22',
            '8' => '03',
            '9' => '11',
            '10' => '21',
            '11' => '07',
            '12' => '10',
            '13' => '19',
            '14' => '02',
            '15' => '24',
            '16' => '18',
            '17' => '06',
            '18' => '16',
            '19' => '08',
            '20' => '12',
            '21' => '09',
            '22' => '15',
            '23' => '14'
        ];

        return (int)$hashcode_array[$index];
    }

    /**
    * @var String
    */
    public function setFileName($filename){
        $this -> fileName = $filename;
    }
}
