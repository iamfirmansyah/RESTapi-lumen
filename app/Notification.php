<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $incrementing = false;
    protected $table = 'notifications';
    protected $fillable = [
        'id', 'title', 'user_id', 'role', 'description', 'created_by', 'updated_by'
    ];
}
