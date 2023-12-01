<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Otp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AuthController extends ApiController
{

  function getCode()
  {
    request()->validate([
      'mobile' => 'required',
    ]);
    $mobile = \request()->input('mobile');

    $otpTimeOut = 60;

    $otp1 = Otp::where('mobile', '=', $mobile)->orderByDesc('created_at')->first();
    //time diff with last otp time
    if ($otp1) {
      $timeDiff = now()->diffInSeconds($otp1->last_otp_time);

      if ($timeDiff < $otpTimeOut) {
        return $this->responseWithFailed($otpTimeOut - $timeDiff);
      }
    }


    $code = mt_rand(1000, 9999);


    $otp = new Otp();
    $otp['mobile'] = \request()->input('mobile');
    $otp['code'] = $code;
    $otp['last_otp_time'] = now();
    $otp['timeout'] = 5 * 60;
    $otp->save();

    return $this->responseWithData(compact('code'));

  }


  function login()
  {

    \request()->validate([
      'mobile' => 'required|string',
      'code' => 'required',
    ]);
    //create another column valid until
    $otp = Otp::where('mobile', '=', \request()->input('mobile'))
      ->whereNull('is_tries')
      ->orderByDesc('created_at')
      ->first();

    $code = \request()->input('code');

    if ($otp && $otp['code'] == $code) {

      $otp['is_tries'] = true;
      $otp->save();

      //create a contacts type user

      $contact = Customer::where('mobile', '=', \request()->input('mobile'))
        ->first();

      if (empty($contact)) {
        $contact = new Customer();
        $contact['mobile'] = \request()->input('mobile');
        $contact['name'] = 'online-customer';
        $contact->save();
      }

      $token = $contact->createToken('token')->plainTextToken;

      return response(['status' => 'success', 'token' => $token], 200);

    }

    return $this->responseWithFailed('Invalid Otp');
  }

  function getUser()
  {
    $user = \auth()->user();
    $user->load('addresses');
    return response($user, 200);
  }

  function setProfile()
  {

    $user = Auth::user();

    \request()->validate([
      'firstName' => 'required',
      'lastName' => 'required',
      /*'city' => 'required',
      'zone' => 'required',
      'address' => 'required',*/
    ]);

    //   $city = City::where('name', '=', \request()->input('city'))->first();

    //  return $city;

    DB::beginTransaction();

    try {

      $user['first_name'] = \request()->input('firstName');
      $user['last_name'] = \request()->input('lastName');
      $user['name'] = $user['first_name'] . ' ' . $user['last_name'];
      $user['type'] = 'customer';
      // $user['location'] = \request()->input('location');
      // $user['city_id'] = $city->id;
      // $user['zone_id'] = request()->input('zone_id');
      //$user['shipping_address'] = \request()->input('address');
      $user->save();

      /*DB::table('contact_addresses')
          ->insert([
             'contact_id' => $user->id,
             'city_id' => $city->id,
              'zone_id' => request()->input('zone'),
              'address' => \request()->input('address'),
              'is_default' =>1,
              'created_at' => now(),
              'updated_at' => now(),
          ]);*/

      DB::commit();

      return response(['status' => 'success', 200]);

    } catch (\Exception $exception) {

      return response($exception);

    }

  }

  function logout()
  {
    auth()->user()->token()->delete();
    return response(['status' => "success", 'message' => 'Log out successful'], 200);
  }


}
