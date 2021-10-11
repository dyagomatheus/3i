<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\DevolutionStatuss;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Devolution extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'product_id',
        'value',
        'number_nf',
        'date_nf',
        'defect',
        'status',
        'number'
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

    /**
     * Get the product that owns the Devolution
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function status($devolutionId)
    {
        $status = DevolutionStatus::where('devolution_id', $devolutionId)->orderBy('id', 'desc')->first();

        return $status;
    }

    /**
     * Get all of the comments for the Devolution
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statuses(): HasMany
    {
        return $this->hasMany(DevolutionStatus::class, 'devolution_id', 'id');
    }

    /**
     * Get the last status associated with the Devolution
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function getLastStatus(): HasOne
    {
        return $this->hasOne(DevolutionStatus::class)->orderBy('id', 'desc');
    }
}
