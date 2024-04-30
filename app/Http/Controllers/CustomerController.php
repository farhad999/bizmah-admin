<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\City;
use App\Models\Customer;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
  function index()
  {

    if (\request()->ajax()) {

      $query = Customer::orderBy('created_at', 'desc');

      return datatables()->of($query)
        ->addColumn('action', function ($row) {
          return view('customer.action-buttons', compact('row'));
        })->make(true);

    }

    return view('customer.index');
  }

  function create()
  {

    $cities = City::getForDropdown();

    return view('customer.create', compact('cities'));
  }

  function store(Request $request)
  {
    $request->validate([
      'name' => 'required',
      'mobile' => 'required|unique:customers',
      'email' => 'nullable|unique:customers',
      'city' => 'required',
      'address' => 'required',
      'zone' => 'required',
    ]);

    $customerData = $request->only([
      'name', 'mobile', 'email',
    ]);

    $addressData = $request->only([
      'mobile', 'city', 'zone', 'address',
    ]);

    $addressData['customer_name'] = $request->input('name');

    $customerData['added_by'] = auth()->id();

    DB::beginTransaction();

    try {

      $customer = Customer::create($customerData);

      $customer->addresses()->create($addressData);

      toastr()->success('Customer created successfully');

      DB::commit();

      return redirect()->route('customers.index');
    } catch (\Exception $e) {
      DB::rollBack();
      toastr()->error($e->getMessage());
      return redirect()->back()->withErrors(['message' => $e->getMessage()]);

    }

  }

  function show($id)
  {
    $customer = Customer::with('addresses')
      ->find($id);

    $carts = Cart::with(['product', 'variation'])
      ->where('customer_id', $id)
      ->get();

    return view('customer.show', compact('customer', 'carts'));
  }

  function edit($id)
  {
    $customer = Customer::findOrFail($id);

    $address = $customer->addresses()->first();

    $cities = City::getForDropdown();

    $zones = Zone::join('cities', 'zones.city_id', '=', 'cities.id')
      ->where('cities.name', $address->city)
      ->pluck('zones.name', 'zones.name');

    return view('customer.edit', compact('customer', 'cities', 'zones', 'address'));
  }

  function update(Request $request, $id)
  {
    $request->validate([
      'name' => 'required',
      'mobile' => 'required|unique:customers,mobile,' . $id,
      'email' => 'nullable|unique:customers,email,' . $id,
      'city' => 'required',
      'address' => 'required',
      'zone' => 'required',
    ]);

    $customer = Customer::findOrFail($id);

    $customerData = $request->only([
      'name', 'mobile', 'email',
    ]);

    $addressData = $request->only([
      'mobile', 'city', 'zone', 'address',
    ]);

    $addressData['customer_name'] = $request->input('name');

    $customerData['added_by'] = auth()->id();

    DB::beginTransaction();

    try {

      $customer->update($customerData);

      $customer->addresses()->first()->update($addressData);

      toastr()->success('Customer Updated successfully');

      DB::commit();

      return redirect()->route('customers.index');
    } catch (\Exception $e) {
      DB::rollBack();
      toastr()->error($e->getMessage());
      return redirect()->back()->withErrors(['message' => $e->getMessage()]);

    }
  }

  function destroy($id)
  {
    $customer = Customer::findOrFail($id);
    $customer->delete();

    return response()->json(['status' => 'success', 'message' => 'Customer deleted successfully']);
  }

  function search()
  {
    $q = \request()->input('q');
    $customers = Customer::where('name', 'like', "%$q%")
      ->orWhere('mobile', 'like', "%$q%")
      ->get();

    return response()->json($customers);
  }

  function getCustomerDetails()
  {
    $id = \request()->input('id');
    $customer = Customer::find($id);

    //if customer has orders

    $lastOrder = $customer->orders->last();

    $address = $customer->addresses->first();

    if ($lastOrder && $lastOrder->address_id) {
      $address = $customer->addresses->where('id', $lastOrder->address_id)
        ->first();
    }

    return response()->json(['customer' => $customer, 'address' => $address]);

  }

}
