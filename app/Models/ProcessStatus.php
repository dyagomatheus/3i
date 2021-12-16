<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessStatus extends Model
{
    use HasFactory;

    protected $table = 'process_statuses';

    protected $fillable = [
        'group_id',
        'status',
        'comment'
    ];
}
