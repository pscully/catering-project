<?php

namespace App\Notifications;

use App\Mail\CateringOrderPlacedInt;
use App\Models\CateringOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CateringOrderPlaced extends Notification
{
    use Queueable;

    public CateringOrder $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(CateringOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)
    {
        return (new CateringOrderPlacedInt($this->order))->to($notifiable->email);
    }
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
