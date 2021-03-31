<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $incrementing = false;
    protected $table = 'products';
    protected $fillable = [
        'id', 'name', 'stock', 'status', 'reason', 'created_by', 'updated_by', 'cheked_by', 'cheked_at'
    ];
}
