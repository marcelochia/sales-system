<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'commission_percentage'
    ];

    public $casts = [
        'commission_percentage' => 'float'
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
