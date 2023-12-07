<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer_url extends Model
{
    use HasFactory;
		protected $primaryKey = 'url_id';
	  protected $fillable = [
		'customer_id',
		'url_link',
		'created_at',
		'updated_at',
		];
}
