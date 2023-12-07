<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Button extends Model
{
    use HasFactory;
	 
	  protected $fillable = [
		'button_code',  
		'button_name',
		'project_id',
		'customer_id',
		'created_at',
		'updated_at'
	  ];
}
