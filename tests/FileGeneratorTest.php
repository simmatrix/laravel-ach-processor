<?php

use Illuminate\FileSystem\Filesystem;
use Illuminate\FileSystem\ClassFinder;
use Models\TestPayment;
use Adapter\MyBeneficiaryAdapter;

use Simmatrix\ACHProcessor\Factory\HSBC\HsbcAchUploadProcessorFactory;
use Simmatrix\ACHProcessor\Factory\UOB\UobAchUploadProcessorFactory;

use Simmatrix\ACHProcessor\Adapter\Result\HSBC\HsbcAchResultAdapter;

class FileGeneratorTest extends Orchestra\Testbench\TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->app['config']->set('database.default','sqlite');
        $this->app['config']->set('database.connections.sqlite.database', ':memory:');
        //read from the tests/config file.
        $config = require 'config/ach_processor_test.php';
        $this -> app['config'] -> set('ach_processor',
            [
                'hsbc' => [
                    'company_a' => $config['hsbc']['company_a']
                ],
                'uob' => [
                    'company_a' => $config['uob']['company_a']
                ]
            ]
        );

        $this -> migrate();
    }

    /**
     * run package database migrations
     *
     * @return void
     */
    public function migrate()
    {
        $fileSystem = new Filesystem;
        $classFinder = new ClassFinder;

        foreach($fileSystem->files(__DIR__ . "/migrations") as $file)
        {
            $fileSystem->requireOnce($file);
            $migrationClass = $classFinder->findClass($file);

            (new $migrationClass)->up();
        }
        foreach($fileSystem->files(__DIR__ . "/seeds") as $file)
        {
            $fileSystem->requireOnce($file);
            $migrationClass = $classFinder->findClass($file);

            (new $migrationClass)->run();
        }
    }

    public function testHSBCDownload()
    {
        echo "\r\n\r\n";

        // Create an array of BeneficiaryAdapterInterface
        $beneficiaries = TestPayment::all();
        $ach = HsbcAchUploadProcessorFactory::create($beneficiaries, 'ach_processor.hsbc.company_a', 'CashoutOct17');
        echo $ach -> getString();
    }

    public function testUOBDownload()
    {
        echo "\r\n\r\n";

        // Create an array of BeneficiaryAdapterInterface
        $beneficiaries = TestPayment::all();

        $ach = UobAchUploadProcessorFactory::create($beneficiaries, 'ach_processor.uob.company_a', 'CashoutOct17');
        $string = $ach -> getString();

        // Every line in a UOB file except the first, must be exactly 900 characters wide
        $i = 0;
        foreach(explode("\r\n", $string) as $line){
            if($i++ >= 1){
                $this -> assertEquals(80, strlen($line));
            }
        }

        echo $string;
    }

    /**
     * @TODO
     */
    public function xtestHSBCUpload()
    {
        //the first line is the Header
        $handle = fopen( __DIR__ ."/ifile_result.csv", "r");
        $index = 0;
        $results = [];

        while (($line = fgets($handle)) !== false) {
            if( $index++ !== 0){
                $adapter = new HsbcAchResultAdapter($line);
                $results[] = $adapter -> getAchResult();
            }
        }
        fclose($handle);

        $this -> assertEquals("ABU BIN BAKAR", $results[0] -> fullname);
        $this -> assertEquals(200, $results[1] -> amount);
        $this -> assertEquals(123458, $results[2] -> paymentId);
        $this -> assertEquals('CIFB04008522', $results[3] -> transactionId);
        $this -> assertEquals('IFILEPYT_1445567761', $results[4] -> fileIdentifier);
        $this -> assertEquals(\DateTime::createFromFormat('Y-m-d', '2015-11-05'), $results[4] -> dateTime);
    }

}
