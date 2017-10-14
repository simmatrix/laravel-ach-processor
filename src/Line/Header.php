<?php

namespace Simmatrix\ACHProcessor\Line;

use Simmatrix\ACHProcessor\Stringable;
use Simmatrix\ACHProcessor\BeneficiaryLine;
use Simmatrix\ACHProcessor\Adapter\Beneficiary\BeneficiaryAdapterInterface;
use Simmatrix\ACHProcessor\Factory\Column\ConfigurableStringColumnFactory;

abstract class Header extends Line implements Stringable
{
    /**
     * @var Array of BeneficiaryAdapterInterface
     */
    protected $beneficiaries;

    /**
     * @var String The payment description
     */
    protected $paymentDescription;

    protected $batchHeaderHeight = 1;

    protected $beneficiaryLineHeight = 1;

    protected $fileHeaderHeight = 1;

    /**
     * @param Array of BeneficiaryAdapterInterface
     * @param String The key to read the config from
     * @param String The payment description
     */
    public function __construct( array $beneficiaries, string $config_key, string $payment_description = '' )
    {
        $this -> beneficiaries = $beneficiaries;
        $this -> paymentDescription = $payment_description;
        parent::__construct($config_key);
    }

    /**
     * Get the payment description
     * @return String
     */
    public function getPaymentDescription()
    {
        return $this -> paymentDescription;
    }

    /**
     * Get the total number of beneficiaries
     * @return int
     */
    public function getBeneficiaryCount()
    {
        return count($this -> beneficiaries);
    }

    /**
     * Get the number of lines that make up a BeneficiaryLine entry.
     * @return int
     */
    public function getBeneficiaryLineHeight()
    {
        return $this -> beneficiaryLineHeight;
    }

    /**
     * @return String
     */
    public function getString()
    {
        $line = $this -> getLine();
        return $line -> getString();
    }
    /**
     * @return int
     */
    public function getTotalLines()
    {
        //assumes
        return $this -> fileHeaderHeight
        + $this -> batchHeaderHeight
        + ($this -> getBeneficiaryCount() * $this -> getBeneficiaryLineHeight() );
    }

    /**
     * @return float
     */
    public function getTotalPaymentAmount()
    {
        return (float)collect($this -> beneficiaries) -> reduce( function($carry,  BeneficiaryAdapterInterface $beneficiary){
            return $carry += $beneficiary -> getPaymentAmount();
        }, 0);
    }

    /**
     * Return the next working day that is in the future.
     * @return Datetime
     */
    public function getEffectivePaymentDate()
    {
        $effective_working_days = intval(ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'effective_working_days', $label = 'effective_working_days', 1, $max_length = 2) -> getString());

        $invalid_day = array('sat','sun');

        $targeted_unix = mktime(0, 0, 0, date("m"), date("d")+$effective_working_days, date("y"));
        $targeted_day = strtolower(date('D',$targeted_unix));

        $datetime = new \DateTime();
        if(!in_array($targeted_day,$invalid_day)){
           $datetime -> setTimestamp($targeted_unix);
           return $datetime -> format('Ymd');
        }else{
           switch($targeted_day){
               case 'sat':
                   $targeted_unix = mktime(0, 0, 0, date("m"), date("d")+$effective_working_days+2, date("y"));
                   break;
               case 'sun':
                   $targeted_unix = mktime(0, 0, 0, date("m"), date("d")+$effective_working_days+1, date("y"));
                   break;
           }
           $datetime -> setTimestamp($targeted_unix);
           return $datetime -> format('Ymd');
        }
    }
}
