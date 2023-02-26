<?php

namespace App\Mail;

use App\Models\ParshaUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParshaQuset extends Mailable
{
    use Queueable, SerializesModels;

    private $parsha;
    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ParshaUser $user, $parsha)
    {
        $this->parsha = $parsha;
        $this->user = $user;
    }

    public function get_file ()
    {
        $files = scandir(storage_path('app/public/parsha_files/'));
        $filename = array_values(array_filter($files, function ($i)
        {
            return str_contains($i, $this->parsha);
        }))[0];
        return storage_path('app/public/parsha_files/'.$filename);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $file = $this->get_file();

        return $this->from(env("MAIL_PARSHA_USERNAME"), env("MAIL_PARSHA_FROM_NAME"))
                ->subject("שאלות בפרשת השבוע | פרשת {$this->parsha}")
                ->attach($file)
                ->view('emails.parsha' , ['user' => $this->user]);
    }
}
