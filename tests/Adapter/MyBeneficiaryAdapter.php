<?php
namespace Adapter;

use Simmatrix\ACHProcessor\Stringable;
use Simmatrix\ACHProcessor\Exceptions\ACHProcessorColumnException;
use Simmatrix\ACHProcessor\Adapter\Beneficiary\BeneficiaryAdapterAbstract;
use Simmatrix\ACHProcessor\Adapter\Beneficiary\BeneficiaryAdapterInterface;

class MyBeneficiaryAdapter extends BeneficiaryAdapterAbstract implements BeneficiaryAdapterInterface
{
    /**
     * @param Model
     */
    public function __construct($model)
    {
        $this -> model = $model;
        $this -> userID = $model -> testUser -> id;
        $this -> paymentAmount = $model -> amount;
        $this -> accountNumber = $model -> testUser -> account_number;
        $this -> bankCode = $model -> testUser -> bank_code;
        $this -> bankBranchCode = $model -> testUser -> bank_branch_code;
        $this -> payeeName = strtoupper($model -> testUser -> fullname);

        $this -> title = $model -> testUser -> title;
        $this -> email = $model -> testUser -> email;
        $this -> payeeIdentificationNumber = $model -> testUser -> ic_number;
    }
}
