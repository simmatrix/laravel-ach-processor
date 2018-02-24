<?php

namespace Simmatrix\ACHProcessor\Services;

use Illuminate\Config\Repository;
use Simmatrix\ACHProcessor\Factory\Column\ConfigurableStringColumnFactory;
use Simmatrix\ACHProcessor\Column\Column;

class CoreHelper
{
    /**
     * To be displayed if user doesn't specify the file reference prefix in the configuration file ( config/ach_processor.php )
     * @var string
     */
    const DEFAULT_FILE_REFERENCE_PREFIX = 'IFILEPYT_';
    
    /**
     * @var Illuminate\Config\Repository The config array to use.
     */
    public $config;

    /**
     * @param String The key to read the config from
     */
    public function __construct( $config_key = null )
    {
        if( $config_key ){
            $this -> config = new Repository(config($config_key));
        }
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

    /**
     * Getting the value for the file reference column
     * @return String
     */
    public function getFileReference()
    {
        // Maximum 24 character length, since the time took up 10 character, the prefix should not be more than 14 characters
        $prefix = ConfigurableStringColumnFactory::create($config = $this -> config, $config_key = 'file_reference_prefix', $label = 'file_reference_prefix', $default_value = SELF::DEFAULT_FILE_REFERENCE_PREFIX, $max_length = 14, $auto_trim = TRUE, $padding_type = Column::PADDING_NONE) -> getString();
        $time = strtotime(date('Y-m-d H:i:00'));
        return $prefix.$time;
    }
}
?>
