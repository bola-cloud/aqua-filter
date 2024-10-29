<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = ['buyer_name', 'buyer_phone', 'invoice_code', 'subtotal', 'discount', 'total_amount', 'paid_amount', 'change', 'user_id','client_id','type','installment_amount'];

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function installments()
    {
        return $this->hasMany(SalesInstallment::class);
    }

    // Method to calculate the total paid so far
    public function getTotalPaidAttribute()
    {
        return $this->installments()->sum('amount_paid');
    }

    // Method to calculate the remaining amount (change)
    public function getChangeAttribute()
    {
        return $this->total_amount - $this->total_paid;
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function scopeMaintenance($query)
    {
        return $query->where('type', 'maintenance');
    }

    public function scopeProduct($query)
    {
        return $query->where('type', 'product');
    }

    // Invoice.php
    public function isFullyPaid()
    {
        // Calculate the total paid amount from all installments
        $totalPaid = $this->installments()->sum('amount_paid');
    
        // Return true if total paid amount equals the total invoice amount
        return $totalPaid >= $this->total_amount;
    }
    
    public function hasInstallmentExceededOneMonth()
    {
        // If the invoice is fully paid, no need to check for installment overdue
        if ($this->isFullyPaid()) {
            return false;
        }
    
        // Get the last installment for this invoice
        $lastInstallment = $this->installments()->latest('date_paid')->first();
    
        // If no installments exist or the last installment was paid more than one month ago
        if ($lastInstallment && \Carbon\Carbon::parse($lastInstallment->date_paid)->lt(\Carbon\Carbon::now()->subMonth())) {
            return true;
        }
    
        return false;
    }  
}
