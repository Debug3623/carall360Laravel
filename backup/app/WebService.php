<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebService extends Model
{
    public static $snakeAttributes = false;

	protected $casts = [
		'created_at' => 'string',
		'updated_at' => 'string'
	];

	protected $fillable = [
		'title',
        'module',
		'url',
		'method',
        'method_name',
		'header_params',
		'body_params',
		'response_sample',
        'auth'
	];
}
