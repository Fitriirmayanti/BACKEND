<?php

namespace App\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Str;

class CustomerController extends Controller
{

    public function index()
    {
        $customer = Customer::all();
        return view('admin_pusat.customer', [
            'title' => 'Customer',
            'active' => 'Index',
            'customer' => $customer,
            'user' => auth()->user(),
        ]);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, string $id)
    {
        //
    }


    public function destroy(string $id)
    {
        $customer = Customer::findOrFail($id);

        $customer->delete();

        return redirect()->back()->with('success', 'Customer berhasil dihapus.');
    }
}
