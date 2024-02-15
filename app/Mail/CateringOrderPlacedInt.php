<?php

namespace App\Mail;

use App\Models\CateringOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CateringOrderPlacedInt extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(CateringOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Catering Order Placed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.int.new-catering-order',
        );
    }

    public function build()
    {
        return $this->markdown('emails.int.new-catering-order')
            ->with([
                'orderId' => $this->order->id,
                'customerEmail' => $this->order->user->email,
                'delivery' => $this->order->delivery,
                'customerPhone' => $this->order->user->phone,
                'orderTotal' => $this->order->total,
                'orderCreatedAt' => $this->order->created_at,
                'orderDate' => $this->order->order_date,
                'orderTime' => $this->order->order_time,
            ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
