<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * DEPRECATED CONTROLLER
 * CustomerController ini sudah tidak digunakan lagi.
 * Gunakan OnlineCustomerController untuk semua manajemen pelanggan.
 * 
 * Semua fitur pelanggan sekarang menggunakan sistem online customer
 * yang terintegrasi dengan WhatsApp dan Public Orders.
 */
class CustomerController extends Controller
{
    /**
     * Redirect semua akses ke online customers
     */
    public function index()
    {
        return redirect()->route('online-customers.index')
            ->with('info', 'Fitur pelanggan telah dipindahkan ke menu Pelanggan Online');
    }

    public function create()
    {
        return redirect()->route('online-customers.index')
            ->with('info', 'Fitur pelanggan telah dipindahkan ke menu Pelanggan Online');
    }

    public function store(Request $request)
    {
        return redirect()->route('online-customers.index')
            ->with('info', 'Fitur pelanggan telah dipindahkan ke menu Pelanggan Online');
    }

    public function show($id)
    {
        return redirect()->route('online-customers.index')
            ->with('info', 'Fitur pelanggan telah dipindahkan ke menu Pelanggan Online');
    }

    public function edit($id)
    {
        return redirect()->route('online-customers.index')
            ->with('info', 'Fitur pelanggan telah dipindahkan ke menu Pelanggan Online');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('online-customers.index')
            ->with('info', 'Fitur pelanggan telah dipindahkan ke menu Pelanggan Online');
    }

    public function destroy($id)
    {
        return redirect()->route('online-customers.index')
            ->with('info', 'Fitur pelanggan telah dipindahkan ke menu Pelanggan Online');
    }

    public function restore($id)
    {
        return redirect()->route('online-customers.index')
            ->with('info', 'Fitur pelanggan telah dipindahkan ke menu Pelanggan Online');
    }

    public function forceDelete($id)
    {
        return redirect()->route('online-customers.index')
            ->with('info', 'Fitur pelanggan telah dipindahkan ke menu Pelanggan Online');
    }

    public function trashed()
    {
        return redirect()->route('online-customers.index')
            ->with('info', 'Fitur pelanggan telah dipindahkan ke menu Pelanggan Online');
    }

    public function orderHistory($id)
    {
        return redirect()->route('online-customers.index')
            ->with('info', 'Fitur pelanggan telah dipindahkan ke menu Pelanggan Online');
    }
}
