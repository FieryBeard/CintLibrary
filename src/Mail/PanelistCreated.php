<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PanelistCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $template;
    private $info;

    /**
     * PanelistCreated constructor.
     * @param $template: HTML template that contains '[USER-NAME]', '[PASSWORD]', '[UNSUBSCRIBE]'
     * @param $content: Associative array with fields: 'username', 'password', 'unsubscribe'
     * @param $info
     */
    public function __construct($template, $content, $info)
    {
        $this->info = $info;
        //replace
        $search		= ['[USER-NAME]', '[PASSWORD]', '[UNSUBSCRIBE]'];
        $replace	= [
            !empty($content['username']) ?  $content['username'] : '',
            !empty($content['password']) ?  $content['password'] : '',
            !empty($content['unsubscribe']) ?  $content['unsubscribe'] : ''
        ];
        $template	= str_replace( $search, $replace, $template );

        $this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->info['from_email'], $this->info['from_name'])
            ->subject($this->info['subject'])
            ->replyTo($this->info['reply_to_email'], $this->info['reply_to_name'])
            ->view('mail.email');
    }
}
