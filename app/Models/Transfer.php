<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;
    protected $table = 'transfers';
    protected $fillable = ['code', 'admins_id', 'investors_id', 'amount', 'payment_method', 'transfer_date', 'confirmation_date', 'notes', 'status'];

    public function admin() 
    {
        return $this->belongsTo(User::class, 'admins_id', 'id');    
    }

    public function investor() 
    {
        return $this->belongsTo(User::class, 'investors_id', 'id');    
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $date = now()->format('Ymd');
            $lastTransaction = self::whereDate('created_at', now()->toDateString())
                ->orderBy('id', 'desc')
                ->first();

            $lastNumber = $lastTransaction ? (int)substr($lastTransaction->code, -4) : 0;
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

            $model->code = "TRF{$date}{$newNumber}";
        });
    }
}
