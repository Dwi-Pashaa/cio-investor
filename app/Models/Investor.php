<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    use HasFactory;
    protected $table = 'investors';
    protected $fillable = ['users_id', 'types_id', 'categories_id', 'bussines_funds', 'persentase', 'monthly_income'];

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
