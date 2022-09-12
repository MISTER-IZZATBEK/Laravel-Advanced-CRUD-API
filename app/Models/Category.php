<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    /**
     * Override fillable property data.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'status',
    ];

}
