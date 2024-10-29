<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class,'supplier_id');
    }

    public function files()
    {
        return $this->hasMany(File::class,'supplier_id');
    }
}