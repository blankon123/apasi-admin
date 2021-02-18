<?php

namespace App\Notifications;

use App\Models\Publikasi;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramFile;

class TelegramNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $publikasi;
    public $msg;
    public $link;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Publikasi $publikasi, String $msg, String $link)
    {
        $this->afterCommit = true;
        $this->user = $user;
        $this->publikasi = $publikasi;
        $this->msg = $msg;
        $this->link = $link;
    }

    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {
        $anchor = env("APP_URL", "http://127.0.0.1:8000") . '/publikasi/' . $this->publikasi->id;
        $giphy = [
            "baseURL" => "https://api.giphy.com/v1/gifs/",
            "apiKey" => env('GIPHY_TOKEN', 'YOUR GIPHY TOKEN HERE'),
            "tag" => "fail",
            "type" => "random",
            "rating" => "pg-13",
        ];
        $giphyURL =
            $giphy['baseURL'] .
            $giphy['type'] .
            "?api_key=" .
            $giphy['apiKey'] .
            "&tag=" .
            $giphy['tag'] .
            "&rating=" .
            $giphy['rating']
        ;
        $response = Http::timeout(120)->get($giphyURL)->json();
        $gifUrl = $response['data']['image_original_url'];

        return TelegramFile::create()
            ->to('@apasikalteng')
            ->content($this->msg . "\nPublikasi **" . $this->publikasi->judul_publikasi . "** \nOleh **" . $this->user->name . "**")
            ->animation($gifUrl)
            ->button('Lihat Publikasi', $anchor);
    }
}