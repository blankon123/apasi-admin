<?php

namespace App\Notifications;

use App\Models\Pekerjaan;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PekerjaanNotif extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $pekerjaan;
    public $msg;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Pekerjaan $pekerjaan, String $msg)
    {
        $this->user = $user;
        $this->pekerjaan = $pekerjaan;
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
            'publikasi' => $this->pekerjaan,
            'message' => $this->msg,
        ];
    }
}