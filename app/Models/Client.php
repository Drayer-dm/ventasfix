<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * Cliente empresa de VentasFix.
 */
#[Fillable([
    'company_rut',
    'business_sector',
    'business_name',
    'phone',
    'address',
    'contact_name',
    'contact_email',
])]
class Client extends Model
{
    //
}
