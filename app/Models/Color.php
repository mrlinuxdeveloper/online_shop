<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Color extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'hex',
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}