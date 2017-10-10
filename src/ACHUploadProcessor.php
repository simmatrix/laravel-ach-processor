<?php

namespace Simmatrix\ACHProcessor;

class ACHUploadProcessor implements Stringable
{
    /**
     * @var Header (optional)
     */
    protected $fileHeader;

    /**
     * @var Header
     */
    protected $batchHeader;

    /**
     * @var Header (optional)
     */
    protected $batchTrailer;

    /**
     * @var Array of BeneficiaryAbstract
     */
    protected $beneficiaries;

    /**
     * @var BeneficiaryLines
     */
    protected $beneficiaryLines;

    /**
     * @var String
     */
    protected $identifier;

    /**
     * @var String
     */
    protected $lineBreak = "\r\n";

    /**
     * @var String
     */
    protected $columnDelimiter = "";

    /**
     * @var String
     */
    protected $fileName;

    /**
     * @var String
     */
    protected $fileExtension;

    /**
     * @param array BeneficiaryAdapterInterface
     */
    public function __construct($beneficiaries)
    {
        $this -> beneficiaries = $beneficiaries;
    }

    /**
     * @param BeneficiaryAdapterInterface
     */
    public function addBeneficiary(BeneficiaryAdapterInterface $beneficiary)
    {
        $this -> beneficiaries[] = $beneficiary;
    }

    /**
    * @return String An identifier for the batch that was generated.
    */
    public function getIdentifier()
    {
        return $this -> identifier;
    }

    /**
     * @return String
     */
    public function getString()
    {
        $line_outputs = [];

        if( $this -> fileHeader )
            $line_outputs[] = $this -> fileHeader -> getString();

        if( $this -> batchHeader )
            $line_outputs[] = $this -> batchHeader -> getString();

        foreach( $this -> beneficiaryLines as $beneficiary_line ){
            $line_outputs[] = $beneficiary_line -> getString();
        }

        if( $this -> batchTrailer )
            $line_outputs[] = $this -> batchTrailer -> getString();

        return implode($this -> lineBreak, $line_outputs);
    }

    /**
     * @param FileHeader
     */
    public function setBatchHeader(Stringable $header)
    {
        $this -> batchHeader = $header;
    }

    /**
     * @param FileHeader
     */
    public function setBatchTrailer(Stringable $header)
    {
        $this -> batchTrailer = $header;
    }

    /**
     * @param Array BeneficiaryAdapterInterface
     */
    public function setBeneficiaries(array $beneficiaries)
    {
        $this -> beneficiaries = $beneficiaries;
    }

    /**
     * @param Array BeneficiaryLines
     */
    public function setBeneficiaryLines(array $beneficiary_lines)
    {
        $this -> beneficiaryLines = $beneficiary_lines;
    }

    /**
     * @param String
     */
    public function setColumnDelimiter($string)
    {
        $this -> columnDelimiter = $string;
    }

    /**
     * @param String
     */
    public function setIdentifier($string)
    {
        $this -> identifier = $string;
    }

    /**
     * @param String
     */
    public function setLineBreak($string)
    {
        $this -> lineBreak = $string;
    }

    /**
     * @param FileHeader
     */
    public function setFileHeader(Stringable $header)
    {
        $this -> fileHeader = $header;
    }

    /**
     * @param String
     */
    public function setFileName($filename)
    {
        $this -> fileName = $filename;
    }

    /**
     * @param String
     */
    public function setFileExtension($extension)
    {
        $this -> fileExtension = $extension;
    }

    /**
     * @var String
     */
    public function getFileName()
    {
        return $this -> fileName;
    }

    /**
     * @var String
     */
    public function getFileExtension()
    {
        return $this -> fileExtension;
    }

}
