<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\DevolutionStatuss;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Devolution extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'product_id',
        'qty',
        'value',
        'number_nf',
        'date_nf',
        'defect',
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
}
