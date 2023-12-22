@extends('layouts.layoutMaster')

@section('title', 'Create Customer')

@section('vendor-style')
  <link rel="stylesheet" href="{{asset(mix('assets/vendor/libs/flatpickr/flatpickr.css'))}}">
@endsection

@section('vendor-script')
  <script src="{{asset(mix('assets/vendor/libs/flatpickr/flatpickr.js'))}}"></script>
@endsection

@section('content')
  <x-content title="Create Customer">
    <x-form action="{{route('customers.store')}}"
    id="validate_form"
    >
      <div class="row">
        <div class="col-sm-6">
          <x-form.input
            name="name"
            label="Name"
            :required="true"
            data-rules="required"
          />
        </div>

        <div class="col-sm-6">
          <x-form.input
            name="mobile"
            label="Mobile"
            :required="true"
            data-rules="required"
          />
        </div>

        <div class="col-sm-6">
          <x-form.input
            name="email"
            label="Email"
          />
        </div>

        <div class="col-sm-6">
          <x-form.select
            name="city"
            label="City"
            :required="true"
            data-rules="required"
            :options="$cities"
          />
        </div>

        <div class="col-sm-6">
          <x-form.select
            name="zone"
            label="Zone"
            :required="true"
            data-rules="required"
            :options="[]"
            />
        </div>

        <div class="col-sm-6">
          <x-form.input
            name="address"
            label="Address"
            :required="true"
            data-rules="required"
          />
        </div>

        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-primary float-end">Submit</button>
        </div>

      </div>
    </x-form>
  </x-content>

@endsection

@section('js')
  <script>

    $(document).ready(function(){

      $('#city').select2({})

      $('#city').on('change', function () {
        $.ajax({
          url: '/get-zones?city_name=' + this.value,
          type: "GET",
          dataType: 'json',
          success: function (data) {
            let options = '<option value="">Select Zone</option>';

            $.each(data, function (key, value) {
              options += '<option value="' + value.name + '">' + value.name + '</option>';
            })
            $('#zone').html(options);
            $('#zone').select2({});
          }
        })
      })

    })

  </script>
@endsection
