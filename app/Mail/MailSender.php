<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailSender extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $view_file;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $view_file, $subject)
    {
        $this->data = $data;
        $this->subject = $subject;
        $this->view_file = $view_file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view($this->view_file, ['data' => $this->data])->subject($this->subject);
    }
}
