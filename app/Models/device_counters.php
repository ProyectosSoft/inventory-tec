<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class device_counters extends Model
{
    protected $fillable = ['company_id','device_type_id','last_seq'];
}
