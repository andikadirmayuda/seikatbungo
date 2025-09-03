<?php

namespace App\Services;

class CartService
{
    public function getCart()
    {
        return session()->get('cart', []);
    }

    public function clearCart()
    {
        session()->forget('cart');
    }

    public function createOrder($data)
    {
        // Buat order baru di database
        $order = \App\Models\Order::create([
            'customer_name' => $data['customer_name'],
            'wa_number' => $data['wa_number'],
            'receiver_name' => $data['receiver_name'],
            'receiver_wa' => $data['receiver_wa'],
            'pickup_date' => $data['pickup_date'],
            'pickup_time' => $data['pickup_time'],
            'delivery_method' => $data['delivery_method'],
            'destination' => $data['destination'],
            'notes' => $data['notes'],
            'custom_instructions' => $data['custom_instructions'],
            'voucher_code' => $data['voucher_code'],
            'voucher_discount' => $data['voucher_discount'],
            'total_amount' => $data['cart_data']['total'] - $data['voucher_discount'],
            'public_code' => $this->generatePublicCode(),
            'status' => 'pending',
        ]);

        // Simpan detail order items
        foreach ($data['cart_data']['items'] as $item) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['product_name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'type' => $item['type'] ?? 'regular',
                'greeting_card' => $item['greeting_card'] ?? null,
                'components' => isset($item['components_summary']) ? json_encode($item['components_summary']) : null,
                'ribbon_color' => $item['ribbon_color'] ?? null,
                'subtotal' => $item['price'] * $item['quantity']
            ]);
        }

        return $order;
    }

    protected function generatePublicCode()
    {
        $prefix = 'ORD';
        $date = now()->format('ymd');
        $random = strtoupper(substr(uniqid(), -4));
        return $prefix . $date . $random;
    }
}
