<?php

use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('test_users') -> truncate();
        \DB::table('test_payments') -> truncate();

        $faker = Faker\Factory::create();
        //create 10 users with 1 payment each
        $i = 0;
        $user_seeds = [];
        while($i++ < 10 ){
            $user_seeds[]= [
                'fullname' => $faker -> firstname() . ' ' . $faker -> firstName() . ' ' . $faker -> lastName(),
                'title' => $faker -> title(),
                'email' => $faker -> safeEmail(),
                'account_number' => $this -> randomNumber(20),
                'bank_code' => $this -> randomNumber(4),
                'bank_branch_code' => $this -> randomNumber(4),
                'ic_number' => $this -> randomNumber(12),
            ];
        }

        \DB::table('test_users') -> insert($user_seeds);

        foreach( \DB::table('test_users') -> get() as $user ){
            $now = new DateTime();
            \DB::table('test_payments') -> insert([
                'amount' => $faker -> randomFloat($max_decimals = 2, 100, 100000),
                'test_user_id' => $user -> id,
                'payment_date' => $now -> format('Y-m-d H:i:s')
            ]);
        }

    }

    public function randomNumber($length) {
        $result = '';

        for($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }

        return $result;
    }
}
