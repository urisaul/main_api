<?php

namespace App\Mail;

use App\Models\ParshaUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParshaQuset extends Mailable
{
    use Queueable, SerializesModels;

    public $parsha;
    // private $user;
    public $template;
    public $views = [
        1 => "emails.parsha",
        2 => "emails.parsha2",
        3 => "emails.parsha3",
    ];
    public $subject;
    public $data_for_template;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($parsha, $template = 1, $data_for_template = [])
    {
        $this->parsha = $parsha;
        // $this->user = $user;
        $this->template = $template;
        $this->data_for_template = $data_for_template;
        $this->build_subject();
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

    public function build_subject ()
    {
        if ($this->template === 1) {
            $this->subject = "שאלות בפרשת השבוע | פרשת {$this->parsha}";
        } elseif ($this->template === 2) {
            $this->subject = "הודעה לחבורא | שאלות בפרשת השבוע | פרשת {$this->parsha}";
        } elseif ($this->template === 3) {
            $this->subject = "חבורא LIVE | שאלות בפרשת השבוע | פרשת {$this->parsha}";
        }
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
                ->subject($this->subject)
                ->attach($file)
                ->view($this->views[$this->template], $this->data_for_template);
    }
}
