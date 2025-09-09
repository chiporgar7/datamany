<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folio extends Model
{

    protected $fillable = [
        'name',
        'amount',
        'folio',
        'fecha_transaccion',
        'extra_field1',
        'extra_field2',
    ];

}
