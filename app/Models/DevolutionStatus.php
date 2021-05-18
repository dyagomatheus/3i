<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevolutionStatus extends Model
{
    use HasFactory;

    protected $table = 'devolution_status';

    protected $fillable = [
        'devolution_id',
        'status',
        'comment'
    ];
}
