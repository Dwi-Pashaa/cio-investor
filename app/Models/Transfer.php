<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;
    protected $table = 'transfers';
    protected $fillable = [
        'code', 
        'admins_id', 
        'investors_id', 
        'amount', 
        'gross_amount', 
        'admin_fee', 
        'payment_method', 
        'balance_type', 
        'finance_reference_id', 
        'xendit_disbursement_id', 
        'xendit_status', 
        'transfer_date', 
        'confirmation_date', 
        'notes', 
        'status'
    ];

    protected $casts = [
        'amount' => 'double',
        'gross_amount' => 'double',
        'admin_fee' => 'double',
    ];

    public function admin() 
    {
        return $this->belongsTo(User::class, 'admins_id', 'id');    
    }

    public function investor() 
    {
        return $this->belongsTo(User::class, 'investors_id', 'id');    
    }

    /**
     * Generate secure HMAC token for accessing invoice publicly without login.
     */
    public function generateInvoiceToken(): string
    {
        $appKey = config('app.key') ?: 'cio-investor-default-secret-key';
        return substr(hash_hmac('sha256', ($this->code ?? '') . '|' . $this->id . '|' . ($this->investors_id ?? ''), $appKey), 0, 32);
    }

    /**
     * Validate an invoice token.
     */
    public function verifyInvoiceToken(?string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        return hash_equals($this->generateInvoiceToken(), $token);
    }

    /**
     * Get full public invoice URL with transferCode and token.
     */
    public function getInvoiceUrl(): string
    {
        $token = $this->generateInvoiceToken();
        return url('/show-dividen?transferCode=' . urlencode($this->code ?? ('INV-TRF-' . $this->id)) . '&token=' . urlencode($token));
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->code)) {
                $date = now()->format('Ymd');
                $lastTransaction = self::whereDate('created_at', now()->toDateString())
                    ->orderBy('id', 'desc')
                    ->first();

                $lastNumber = $lastTransaction ? (int)substr($lastTransaction->code, -4) : 0;
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

                // Standar prefix INV- untuk Multi-Web Central Router
                $model->code = "INV-TRF{$date}{$newNumber}";
            }
        });
    }
}

