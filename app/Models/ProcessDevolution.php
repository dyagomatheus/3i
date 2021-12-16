<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcessDevolution extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'client_id',
        'status'
    ];

    /**
     * Get the client that owns the Devolution
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public static function status($groupId)
    {
        $status = ProcessStatus::where('group_id', $groupId)->orderBy('id', 'desc')->first();

        return $status;
    }


    /**
     * Get all of the comments for the Devolution
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statuses(): HasMany
    {
        return $this->hasMany(ProcessStatus::class, 'group_id', 'id');
    }

    /**
     * Get all of the comments for the ProcessDevolution
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function devolutions(): HasMany
    {
        return $this->hasMany(Devolution::class, 'group_id', 'id');
    }
}
