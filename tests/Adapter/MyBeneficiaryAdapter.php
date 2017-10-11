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
        $this -> accountNumber = $model -> testUser -> accountNumber;
        $this -> bankCode = $model -> testUser -> bank_code;
        $this -> bankBranchCode = $model -> testUser -> bank_branch_code;
        $this -> payeeName = strtoupper($model -> testUser -> fullname);
        $this -> secondPartyReference = $model -> testUser -> icNumber;
    }
}
