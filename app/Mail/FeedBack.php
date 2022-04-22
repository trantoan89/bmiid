<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedBack extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Typically contains 'email', 'name', 'message'.
     *
     * @var array
     */
    public $params;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.feedback')
                    ->with([
                        'email' => $this->params['email'],
                        'name' => $this->params['name'],
                        'msg' => $this->params['message'],
                    ]);
    }
}
