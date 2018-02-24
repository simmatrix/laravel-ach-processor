<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_users', function (Blueprint $table) {
            $table -> increments('id');
            $table -> string('fullname');
            $table -> string('title');
            $table -> string('email');
            $table -> string('account_number');
            $table -> string('bank_code');
            $table -> string('bank_branch_code');
            $table -> string('ic_number');
        });

        Schema::create('test_payments', function(Blueprint $table){
            $table -> increments('id');
            $table -> decimal('amount', 10, 2);
            $table -> integer('test_user_id');
            $table -> dateTime('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('test_users');
        Schema::drop('test_payments');
    }
}
