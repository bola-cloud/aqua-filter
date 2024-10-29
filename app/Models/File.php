<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'client_id',
        'supplier_id',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class,'client_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }
}
