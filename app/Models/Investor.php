<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    use HasFactory;
    protected $table = 'investors';
    protected $fillable = [
        'users_id', 'types_id', 'categories_id', 'bussines_funds', 'persentase', 'monthly_income', 
        'first_money_received_at', 'first_dividend_at', 'last_dividend_at', 
        'party_1_name', 'party_1_address', 'party_1_bank', 'party_1_account_number',
        'party_2_name', 'witness_1_name', 'witness_2_name',
        'party_1_signature', 'party_2_signature', 'witness_1_signature', 'witness_2_signature',
        'file'
    ];

    public function user() 
    {
        return $this->belongsTo(User::class, 'users_id', 'id');      
    }

    public function type() 
    {
        return $this->belongsTo(Type::class, 'types_id', 'id');    
    }

    public function categorie() 
    {
        return $this->belongsTo(Kategori::class, 'categories_id', 'id');    
    }
}
