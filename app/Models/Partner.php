<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'partner_name',
        'type',
        'logo',
    ];

    protected $casts = [
        'type' => 'string',
    ];
}
