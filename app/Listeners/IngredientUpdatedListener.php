<?php

namespace App\Listeners;

use App\Events\IngredientUpdatedEvent;
use App\Mail\StockDepletionEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class IngredientUpdatedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(IngredientUpdatedEvent $event): void
    {
        $ingredient = $event->ingredient;
        if ($ingredient->reachedThreshold() && $ingredient->stockDepletionNotificationSent()) {
            Mail::send(new StockDepletionEmail($ingredient));
            $ingredient->updateStockNotificationSent(true);
        }
    }
}
