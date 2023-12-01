<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\City;
use App\Models\Zone;
use Illuminate\Http\Request;

class AddressController extends ApiController
{
  function store(Request $request)
  {

    $validData = $request->validate([
      'address' => 'required',
      'city' => 'required',
      'zone' => 'required',
      'mobile' => 'required',
      'customer_name' => 'required',
    ]);

    $id = \request()->input('id');

    if($id){
      $address = Address::find($id);
      $address->update($validData);
      return $this->responseWithSuccess('Address Updated successfully.');

    }

    $validData['customer_id'] = $request->user()->id;
    Address::create($validData);

    return $this->responseWithSuccess('Address Added successfully.');

  }

  function update()
  {

  }

  function destroy($id)
  {
    $address = Address::find($id);
    $address->delete();
    return $this->responseWithSuccess('Address Deleted successfully.');
  }

  function getCities()
  {
    $cities = City::with('zones')
      ->select('id', 'name')
      ->orderBy('name')
      ->get();

    return response()->json($cities);
  }

  function getZones($cityId)
  {
    $zones = Zone::select('id', 'name')
      ->where('city_id', $cityId)
      ->orderBy('name')
      ->get();

    return response()->json($zones);
  }

}
