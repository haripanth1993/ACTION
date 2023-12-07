<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
	 
	  protected $fillable = [
		'project_name',  
		'description',
		'project_url',
		'customer_id',
		'created_at',
		'updated_at'
	  ];
}
