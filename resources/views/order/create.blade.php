@extends('layouts.layoutMaster')

@section('title', 'Add Order')

@section('vendor-style')
  <link rel="stylesheet" href="{{asset(mix('assets/vendor/libs/flatpickr/flatpickr.css'))}}">
@endsection

@section('vendor-script')
  <script src="{{asset(mix('assets/vendor/libs/flatpickr/flatpickr.js'))}}"></script>
@endsection

@section('content')
  <x-content title="Add Order">

    <x-error-alert/>

    <!-- delivery charge -->

    <input type="hidden" name="shipping_charge_inside_dhaka"
           value="{{$settings->where('name', 'shipping_charge_inside_dhaka')->first()->value ?? 0}}">
    <input type="hidden" name="shipping_charge_outside_dhaka"
           value="{{$settings->where('name', 'shipping_charge_outside_dhaka')->first()->value ?? 0}}">


    <x-form action="{{route('orders.store')}}"
            id="validate_form"
    >

      <div class="row">
        <div class="col-sm-6">
          <x-form.select
            name="customer_id"
            id="customer_id"
            label="Customer"
            :options="[]"
          />
        </div>

        <div class="col-sm-6">
          <div>
            <x-form.input
              name="date"
              label="Date"
              id="date"
              autocomplete="off"
              value="{{now()->timezone('Asia/Dhaka')}}"
            />
          </div>
        </div>

        <div class="col-sm-6">
          <x-form.select
            name="status"
            label="Status"
            :options="$orderStatuses"
            :required="true"
            data-rules="required"
          />
        </div>

        <div class="col-sm-6">
          <x-form.select
            name="shipping_status"
            label="Shipping Status"
            :options="$shippingStatuses"
            :required="true"
            data-rules="required"
          />
        </div>

        <div class="col-12">
          <h4>Products</h4>

          <x-form.select
            id="product_selection"
            name="products"
            label="Product"
            :options="[]"
          />

        </div>


        <div class="col-12">
          <table id="order_table" class="table table-bordered">
            <thead>
            <tr>
              <th style="width: 25%">Product</th>
              <th style="width: 25%">Variant</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Sub Total</th>
              <th></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

          <hr/>

          <div class="col-md-12">
            <div class="row d-flex justify-content-end">
              <div class="col-md-4">
                <table class="table">

                  <tr>
                    <th>Sub Total</th>
                    <td>
                      <x-form.input
                        name="sub_total"
                        id="sub_total"
                        value="0"
                        readonly
                        data-rules="required"
                      />
                    </td>
                  </tr>

                  <tr>
                    <th>Shipping Charge</th>
                    <td>
                      <x-form.input
                        name="shipping_charge"
                        class="shipping-charge"
                        value="0"
                        readonly
                        data-rules="required"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td>Discount</td>
                    <th>
                      <x-form.input
                        name="discount"
                        class="discount"
                        value="0"
                      />
                    </th>
                  </tr>
                  <tr>
                    <th>Total</th>
                    <td>
                      <x-form.input
                        name="total_amount"
                        class="total-amount"
                        value="0"
                        readonly
                      />
                    </td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>

        <h3>Customer Information</h3>

        <div class="col-sm-6">
          <x-form.input
            name="customer_name"
            label="Customer Name"
            id="customer_name"
            :required="true"
            data-rules="required"
          />
        </div>

        <div class="col-sm-6">
          <x-form.input
            name="customer_mobile"
            id="customer_mobile"
            label="Customer Mobile"
            :required="true"
            data-rules="required"
          />
        </div>

        <div class="col-sm-6">
          <x-form.select
            name="customer_city"
            id="city"
            label="City"
            :options="$cities"
            :required="true"
            data-rules="required"
          />
        </div>

        <div class="col-sm-6">
          <x-form.select
            name="customer_zone"
            id="zone"
            label="Area"
            :options="[]"
            :required="true"
            data-rules="required"
          />
        </div>

        <div class="col-sm-6">
          <x-form.input
            name="customer_address"
            id="customer_address"
            label="Customer Address"
            :required="true"
            data-rules="required"
          />
        </div>

        <div class="col-sm-6">
          <x-form.select
            name="delivered_to"
            id="delivered_to"
            label="Delivered To"
            :options="$deliveredTo"
            :required="true"
            data-rules="required"
          />
        </div>

        <div class="col-12">
          <x-form.textarea
            name="note"
            label="Note"
          />
        </div>

        <div class="col-12">
          <button type="submit" class="btn btn-primary float-end">Submit</button>
        </div>

      </div>

    </x-form>

  </x-content>
@endsection

@section('js')
  <script>
    $(document).ready(function () {

      $('#date').flatpickr({
        format: 'yyyy-mm-dd',
        enableTime: true,
        enableSeconds: true,
        minuteIncrement: 1,
      })

      $('#customer_id').select2({
        minimumInputLength: 2,
        ajax: {
          url: '/search-customer',
          dataType: 'json',
          type: "GET",
          quietMillis: 50,
          data: function (params) {
            return {
              q: params.term,
            };
          },
          processResults: function (data) {
            return {
              results: $.map(data, function (item) {
                return {
                  text: item.name + ' (' + item.mobile + ')',
                  id: item.id
                }
              })
            };
          }
        }
      });

      $('#customer_id').on('change', function () {
        let id = this.value;
        $.ajax({
          url: '/get-customer-details',
          type: "GET",
          data: {
            id
          },
          dataType: 'json',
          success: function (data) {
            let {customer, address} = data;
            $('#customer_name').val(address.customer_name);
            $('#customer_mobile').val(address.mobile);
            $('#customer_address').val(address.address);
            $('#city').val(address.city).trigger('change');

            updateZones(address.city, function(){
              $('#zone').val(address.zone).trigger('change');
            });


          }
        })
      })

      $('#product_selection').select2({
        minimumInputLength: 2,
        ajax: {
          url: '/search-products',
          dataType: 'json',
          type: "GET",
          quietMillis: 50,
          data: function (params) {
            return {
              q: params.term,
            };
          },
          processResults: function (data) {
            return {
              results: $.map(data, function (item) {
                return {
                  text: item.name,
                  slug: item.slug,
                  id: item.id
                }
              })
            };
          }
        }
      });

      $('#product_selection').on('change', function (e) {

        let id = this.value;

        //get last index

        let indexEl = $('#order_table tbody tr:last').find('.td-index')
        let index = 0;

        if (indexEl.length > 0) {
          index = Number(indexEl.val());
        }

        //clear product select box

        $('#product_selection').val('');

        $.ajax({
          url: '/get-order-row',
          type: "GET",
          data: {
            id,
            index: index + 1,
          },
          success: function (html) {
            $('#order_table tbody').append(html)
            calculateSubTotal(html)
          }
        })

      })

      $(document).on('change', '.td-variation', function () {
        let id = $(this).val();
        let tr = $(this).closest('tr');

        //get variation info

        $.ajax({
          url: '/get-variation',
          data: {
            id
          },
          type: "GET",
          dataType: 'json',
          success: function (data) {
            let {price} = data;
            $(tr).find('.td-price').val(price);
            calculateSubTotal(tr);
          }
        })
      })

      $(document).on('change', '.td-quantity, .td-price', function () {
        let tr = $(this).closest('tr');
        calculateSubTotal(tr);
      });

      $('#delivered_to').on('change', function () {
        let value = $(this).val();
        if (value === 'inside_dhaka') {
          $('.shipping-charge').val($('input[name="shipping_charge_inside_dhaka"]').val());
        } else {
          $('.shipping-charge').val($('input[name="shipping_charge_outside_dhaka"]').val());
        }

        calculateTotal();

      })

      //discount

      $('.discount').on('change', function () {
        calculateTotal();
      })

      function calculateSubTotal(tr) {
        let quantity = Number($(tr).find('.td-quantity').val());
        let price = Number($(tr).find('.td-price').val());
        let total = quantity * price;
        $(tr).find('.td-total').val(total);
        calculateTotal();
      }


      function calculateTotal() {
        let total = 0;
        $('#order_table tbody tr').each(function () {
          total += Number($(this).find('.td-total').val())
        })

        $('#sub_total').val(total);

        //get shipping charge
        let shippingCharge = Number($('.shipping-charge').val());

        //subtract discount

        let discount = Number($('.discount').val());
        total = total - discount;

        total += Number(shippingCharge);
        $('.total-amount').val(total);
      }

      $('#city').select2({})

      $(document).on('click', '.remove-item-btn', function () {
        $(this).closest('tr').remove();
      })

      $('#city').on('change', function (event) {

        console.log('event', event, 'this', this);

        updateZones(this.value);
      })

      function updateZones(city, callback) {
        $.ajax({
          url: '/get-zones?city_name=' + city,
          type: "GET",
          dataType: 'json',
          success: function (data) {
            let options = '<option value="">Select Zone</option>';

            $.each(data, function (key, value) {
              options += '<option value="' + value.name + '">' + value.name + '</option>';
            })
            $('#zone').html(options);
            $('#zone').select2({});

            if (typeof callback === 'function') {
              callback();
            }

          }
        })
      }

    })
  </script>
@endsection
