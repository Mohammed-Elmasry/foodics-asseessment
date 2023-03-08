<?php

namespace App\Mail;

use App\Models\Ingredient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use League\CommonMark\Extension\CommonMark\Renderer\Block\HeadingRenderer;

class StockDepletionEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Ingredient
     */
    public Ingredient $ingredient;

    /**
     * Create a new message instance.
     * @param Ingredient $ingredient
     */
    public function __construct(Ingredient $ingredient)
    {
        $this->ingredient = $ingredient;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Stock Depletion Warning',
            to: 'merchant@example.com'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'depletion_email',
        );
    }
}
