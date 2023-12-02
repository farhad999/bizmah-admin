<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    function index(){

      if(\request()->ajax()){

        $query = Customer::query();

        return datatables()->of($query)
          ->addColumn('action', function($row){
            return view('customer.action-buttons', compact('row'));
          })->make(true);

      }

      return view('customer.index');
    }

    function create(){
      return view('customer.create');
    }

    function store(Request $request){

    }

    function show($id){
      $customer = Customer::find($id);

      $carts = Cart::with(['product', 'variation'])
        ->where('customer_id', $id)
        ->get();

      return view('customer.show', compact('customer', 'carts'));
    }

    function update(Request $request){

    }

    function destroy($id){

    }

}
