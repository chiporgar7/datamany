<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionData extends Model
{
   protected $fillable = ['transaction_id', 'user_id','transaction_value'];
}
