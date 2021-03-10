<?php

namespace App\Notifications;

use app\Models\TabelDinamis;
use app\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TabelDinamisNotif extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $tabel;
    public $msg;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, TabelDinamis $tabel, String $msg)
    {
        $this->user = $user;
        $this->tabel = $tabel;
        $this->msg = $msg;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user' => $this->user->nama_bidang,
            'tabel' => $this->tabel,
            'message' => $this->msg,
        ];
    }
}