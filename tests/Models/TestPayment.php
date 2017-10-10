<?php namespace Models;

use Illuminate\Database\Eloquent\Model;

use Models\TestUser;

class TestPayment extends Model
{
	protected $guarded = [];
    public $timestamps = false;

    public function testUser()
    {
        return $this -> belongsTo(TestUser::class);
    }
}
