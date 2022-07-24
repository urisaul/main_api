<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GeneralEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $email_subject;

    public $email_content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data = [])
    {
        $this->email_subject = $data['subject']?? "" ;
        $this->email_content = $data['body']?? "" ;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env("MAIL_USERNAME"), env("MAIL_FROM_NAME"))
                ->subject($this->email_subject)
                ->view('emails.blank' , ['data' => $this->email_content]);
    }
}
