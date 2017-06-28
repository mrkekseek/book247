<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Paypal extends Model{

    protected $table = 'paypal_log';

    protected $fillable = [
        'invoice_id',
        'paypal_response'
    ];

}
