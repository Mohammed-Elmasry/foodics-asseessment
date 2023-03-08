<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ["product_name"];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity')->withTimestamps();
    }

    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class)->withPivot('used_amount')->withTimestamps();
    }
}
