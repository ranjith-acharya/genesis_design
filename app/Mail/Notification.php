<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $head;
    public $body;
    public $button;
    public $link;

    /**
     * Notification constructor.
     * @param $user
     * @param $head
     * @param $body
     * @param $link
     * @param $button
     */
    public function __construct($user, $head, $body, $link, $button)
    {
        $this->user = $user;
        $this->head = $head;
        $this->body = $body;
        $this->link = $link;
        $this->button = $button;
    }
    /**
     * Create a new message instance.
     *
     * @return void
     */


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->head)->markdown('emails.notification', [
            "user" => $this->user,
            "head" => $this->head,
            "body" => $this->body,
            "link" => $this->link,
            "button" => $this->button,
        ]);
    }
}
