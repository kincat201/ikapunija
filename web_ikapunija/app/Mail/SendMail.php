<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $dataUser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($dataUser)
    {
        $this->data = $dataUser;
        return response()->json([
                    'StatusCode' =>200,
                    'Error'=>false,
                    'Message'=>'Email Verifikasi Telah Dikirim',
                    'Data' => $this->data
                ]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->from('cs@ikapunija.com', 'Ikapunija')
        ->subject($this->data['subject']);

        if($this->data['subject'] == 'Web Ikapunija')
        { $mail = $mail->view('kontak'); }

        else
        { $mail = $mail->view('email'); }
        
        $mail = $mail->with('data', $this->data);
        return $mail;
    }
}