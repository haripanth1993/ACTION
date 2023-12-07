<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
	protected $primaryKey = 'admin_customer_id';
	  protected $fillable = [
		'customer_name',
		'admin_customer_id',
		'created_at',
		'updated_at', 
	];
}
