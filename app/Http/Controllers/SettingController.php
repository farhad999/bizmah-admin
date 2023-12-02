<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
  function getSettings()
  {

    $settings = Setting::where('group', 'general')
      ->get();

    return view('settings.general.index', compact('settings'));
  }

  function updateSettings(Request $request)
  {
    $request->validate([
      'shipping_charge_inside_dhaka' => 'required',
      'shipping_charge_outside_dhaka' => 'required',
    ]);

    $group = $request->input('group');

    //update for each value

    $inputs = $request->only(['shipping_charge_inside_dhaka', 'shipping_charge_outside_dhaka']);

    DB::beginTransaction();

    try {

      foreach ($inputs as $key => $value) {
        Setting::updateOrCreate([
          'name' => $key,
        ], [
          'value' => $value,
          'group' => $group,
          'name' => $key
        ]);
      }

      DB::commit();
      toastr()->success('Settings updated successfully');
      return redirect()->back();

    } catch (\Exception $e) {
      DB::rollBack();
      toastr()->error($e->getMessage());
      return redirect()->back()->withErrors(['message' => $e->getMessage()]);
    }

  }

}
