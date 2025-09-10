<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionData extends Model
{
    protected $table = 'transaction_data';
    
    protected $fillable = [
        'transaction_id',
        'user_id',
        'transaction_value',
    ];
}