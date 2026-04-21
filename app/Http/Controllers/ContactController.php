<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        $contact = [
            'nama_toko' => '[Nama Toko]',
            'alamat' => 'Jl. [Alamat Toko], Sidoarjo, Jawa Timur',
            'nomor_wa' => '085649150049',
            'jam_buka' => 'Senin–Sabtu: 08.00–20.00 | Minggu: 09.00–17.00',
            'maps_embed' => 'https://maps.google.com/maps?q=Sidoarjo&output=embed',
            'maps_url' => 'https://maps.google.com/?q=Sidoarjo',
            'instagram' => '@[handle_instagram]',
        ];

        return view('pages.contact', compact('contact'));
    }
}
