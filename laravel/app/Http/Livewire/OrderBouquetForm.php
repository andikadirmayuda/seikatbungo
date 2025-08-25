<?php   

// PENTING: Agar tidak error di Livewire v3,
// 1. Pindahkan file ini ke folder: app/Livewire
// 2. Pastikan namespace: App\Livewire;
// 3. Hapus file ini dari app/Http/Livewire setelah dipindah.

namespace App\Livewire;

use Livewire\Component;

class OrderBouquetForm extends Component
{
    public $customer_name, $receiver_name, $pickup_datetime, $delivery_method, $delivery_address, $greeting_card;

    public function submitOrder()
    {
        // Simpan data ke database nanti di sini
        dd([
            'customer' => $this->customer_name,
            'receiver' => $this->receiver_name,
            'pickup' => $this->pickup_datetime,
            'method' => $this->delivery_method,
            'address' => $this->delivery_address,
            'card' => $this->greeting_card,
        ]);
    }

    public function render()
    {
        return view('livewire.order-bouquet-form');
    }
}
