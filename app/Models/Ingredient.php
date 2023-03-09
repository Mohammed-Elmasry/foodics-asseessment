<?php

namespace App\Models;

use App\Enums\Constants;
use App\Services\IngredientsService;
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

    public function notificationThreshold(): int
    {
        return (int)$this->original_total_amount * IngredientsService::NOTIFICATION_THRESHOLD_RATIO;
    }

    public function reachedNotificationThreshold(): bool
    {
        return $this->available_amount_in_grams < $this->notificationThreshold();
    }

    public function updateStockNotificationSent(bool $flag)
    {
        $this->stock_notification_sent = $flag;
        $this->save();
    }

    public function reachedThreshold(): bool
    {
        return $this->available_amount_in_grams < $this->notificationThreshold();
    }

    public function stockDepletionNotificationSent(): bool
    {
        return !$this->stock_notification_sent;
    }


}
