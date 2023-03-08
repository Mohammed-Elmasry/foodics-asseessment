<?php

namespace App\Models;

use App\Enums\Constants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "available_amount_in_grams"
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('used_amount')->withTimestamps();
    }

    public function availableAmountInKilos()
    {
        return $this->available_amount_in_grams / Constants::KILO;
    }
}
