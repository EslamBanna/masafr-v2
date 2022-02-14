<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminSendEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $type_of_template;
    public $subject;
    private $email;
    public function __construct($type_of_template, $subject, $email)
    {
        $this->type_of_template = $type_of_template;
        $this->email = $email;
        $this->subject = $subject;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->type_of_template == 0) {
            return $this->view('mails.adminMail')->with(['subject' => $this->subject, 'email' => $this->email]);
        } else if ($this->type_of_template == 1) {
            return $this->view('mails.adminMail')->with(['subject' => $this->subject, 'email' => $this->email]);
        } else if ($this->type_of_template == 2) {
            return $this->view('mails.adminMail')->with(['subject' => $this->subject, 'email' => $this->email]);
        } else if ($this->type_of_template == 3) {
            return $this->view('mails.adminMail')->with(['subject' => $this->subject, 'email' => $this->email]);
        } else if ($this->type_of_template == 4) {
            return $this->view('mails.adminMail')->with(['subject' => $this->subject, 'email' => $this->email]);
        }
    }
}
