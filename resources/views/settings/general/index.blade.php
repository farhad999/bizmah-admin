@extends('layouts.layoutMaster')

@section('content')
  <x-content title="General Settings">

    <x-form action="{{ route('settings.update')}}"
    id="validate_form"
    >

      <input type="hidden" name="group" value="general">

      <div class="row">
        <div class="col-12">
          <h5>Shipping Charge</h5>
        </div>

        <div class="col-sm-6">
          <x-form.input
            name="shipping_charge_inside_dhaka"
            label="Shipping Charge Inside Dhaka"
            :required="true"
            data-rules="required|number"
            value="{{$settings->where('name', 'shipping_charge_inside_dhaka')->first()->value ?? ''}}"
          />
        </div>

        <div class="col-sm-6">
          <x-form.input
            name="shipping_charge_outside_dhaka"
            label="Shipping Charge Outside Dhaka"
            :required="true"
            data-rules="required|number"
            value="{{$settings->where('name', 'shipping_charge_outside_dhaka')->first()->value ?? ''}}"
          />
        </div>

        <div class="col-12 d-flex justify-content-end">
          <button class="btn btn-primary">Save</button>
        </div>

      </div>

    </x-form>

  </x-content>
@endsection
