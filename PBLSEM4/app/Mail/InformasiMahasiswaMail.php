<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InformasiMahasiswaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $nim;
    public $pesan;

    /**
     * @param array $dataMahasiswa
     *   [
     *     'nama'  => '...',
     *     'nim'   => '...',
     *     'pesan' => '...'
     *   ]
     */
    public function __construct(array $dataMahasiswa)
    {
        $this->nama  = $dataMahasiswa['nama'];
        $this->nim   = $dataMahasiswa['nim'];
        $this->pesan = $dataMahasiswa['pesan'];
    }

    public function build()
    {
        return $this
            // Subject awal di‐set di controller via ->subject(...)
            ->view('emails.informasi_mahasiswa')
            ->with([
                'nama'  => $this->nama,
                'nim'   => $this->nim,
                'pesan' => $this->pesan,
            ]);
    }
}
