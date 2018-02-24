<?php
namespace Simmatrix\ACHProcessor\Adapter\Beneficiary;

use Simmatrix\ACHProcessor\Stringable;
use Simmatrix\ACHProcessor\Exceptions\ACHProcessorColumnException;

class ExampleBeneficiaryAdapter extends BeneficiaryAdapterAbstract implements BeneficiaryAdapterInterface
{
    /**
     * @param Model The Laravel model for the beneficiary record
     */
    public function __construct($model)
    {
        $this -> model = $model;

        $this -> userId = $model -> testUser -> id;
        $this -> paymentAmount = $model -> amount;
        $this -> accountNumber = $model -> testUser -> accountNumber;
        $this -> bankCode = $model -> testUser -> bank_code;
        $this -> bankBranchCode = $model -> testUser -> bank_branch_code;
        $this -> payeeName = strtoupper( $model -> testUser -> fullname );

        $this -> title = $model -> testUser -> title;
        $this -> email = $model -> testUser -> email;
        $this -> payeeIdentificationNumber = $model -> testUser -> payeeIdentificationNumber;
    }
}
