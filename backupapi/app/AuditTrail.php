<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    protected $guarded = ['id'];
	protected $table = 'audit_trails';
}
