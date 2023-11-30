<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'date',
        'commission',
        'seller_id',
    ];

    public $casts = [
        'value' => 'float',
        'date' => 'datetime:Y-m-d',
        'commission' => 'float',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }
}
