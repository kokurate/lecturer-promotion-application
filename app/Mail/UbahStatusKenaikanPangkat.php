<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UbahStatusKenaikanPangkat extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
         $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->markdown(base_path('resources/views/emails/ubah_status_kenaikan_pangkat.blade.php'))
        return $this->markdown('emails.ubah_status_kenaikan_pangkat')
                    ->subject('Pemberitahuan Kenaikan Pangkat Dosen!')
                    ->with('data', $this->data);
    }
}
