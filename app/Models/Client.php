<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'code',
        'village_id',
        'can_have_invoice',
        'can_have_maintenance',
    ];    

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function files()
    {
        return $this->hasMany(File::class,'client_id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class,'village_id');
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

}
