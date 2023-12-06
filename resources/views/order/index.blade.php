@extends('layouts.layoutMaster')

@section('title', 'Orders')

@section('vendor-style')
  <link rel="stylesheet"
        href="{{asset(mix('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css'))}}">
@endsection

@php

  if(!empty($type)){
      $title = $type . ' Orders';
  }else{
      $title = 'Orders';
  }
@endphp

@section('content')

  <!--Filters -->

  <div class="card mb-2">
    <div class="card-header">
      <h4 class="card-title">Filters</h4>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-sm-4">
          <x-form.input
            label="Date Range"
            id="date_range"
          />
        </div>
        @empty($type)
          <div class="col-sm-4">
            <x-form.select
              name="status"
              label="Order Status"
              placeholder="All"
              :options="$orderStatuses"
              id="status"
            />
          </div>
        @endif
        <div class="col-sm-4">
          <x-form.select
            name="shipping_status"
            label="Shipping Status"
            placeholder="All"
            :options="$shippingStatuses"
            id="shipping_status"
          />
        </div>
      </div>
    </div>
  </div>

  <x-content :title="$title">

    <x-slot name="buttons">
      <a href="{{route("orders.create")}}" class="btn btn-primary">Create</a>
    </x-slot>

    <div>
      <table class="table table-bordered" id="datatable">
        <thead>
        <tr>
          <th>Action</th>
          <th>Order No</th>
          <th>Date</th>
          <th>Customer Name</th>
          <th>Mobile</th>
          <th>Status</th>
          <th>Shipping Status</th>
          <th>Total</th>
        </tr>
        </thead>
      </table>
    </div>
  </x-content>

  <x-modal.fade id="view_modal"></x-modal.fade>

@endsection

@section('vendor-script')
  <script src="{{asset(mix('assets/vendor/libs/moment/moment.js'))}}"></script>
  <script src="{{asset(mix('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js'))}}"></script>
@endsection

@section('js')
  <script>
    $(document).ready(function () {

      $('#date_range').daterangepicker({
        locale: {
          format: 'DD/MM/YYYY',
        },
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
        },
        //set today value
        startDate: moment(),
        endDate: moment()
      });

      let table = $('#datatable').DataTable({
        responsive: true,
        ajax: {
          url: window.location.pathname,
          data: function (d) {
            d.status = $('#status').val();
            d.shipping_status = $('#shipping_status').val();
            d.date_range = $('#date_range').val();
          }
        },
        columns: [
          {data: 'action'},
          {data: 'order_no'},
          {data: 'date'},
          {data: 'customer_name'},
          {data: 'customer_mobile'},
          {data: 'status'},
          {data: 'shipping_status'},
          {data: 'total_amount'},
        ]
      })

      $(document).on('click', '.view-modal-btn', function () {
        let url = $(this).data('href');
        reloadViewModal(url)
      })

      function reloadViewModal(url) {
        $.ajax({
          url,
          success: function (html) {
            $('#view_modal').html(html);
            $('#view_modal').modal('show');
          }
        })

      }

      $(document).on('click', '.order-confirm-btn', function () {
        updateStatus('Do you want to confirm this order?', 'confirmed')
      })

      $(document).on('click', '.order-cancel-btn', function () {
        updateStatus('Do you want to cancel this order?', 'cancelled', 'warning')
      })

      function updateStatus(title, type, icon = 'question') {

        let order_id = $('#order_id').val();

        Swal.fire({
          title: `${title}?`,
          icon: icon,
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Confirm"
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: `/orders/${order_id}/update-status`,
              method: "POST",
              data: {
                status: type,
              },
              success: function () {
                reloadViewModal('/orders/' + order_id)
                table.ajax.reload();
                toastr('success', 'Order status updated successfully');
              }
            })
          }
        })
      }

      $(document).on('click', '.shipping-status-btn', async function () {

        let order_id = $('#order_id').val();

        const {value: shippingStatus} = await Swal.fire({
          title: "Update Shipping Status?",
          input: "select",
          inputValue: $('#shipping_status').val(),
          inputOptions: {
            processing: "Processing",
            shipped: "Shipped",
            delivered: "Delivered",
          },
          icon: "question",
          confirmButtonColor: "#3085d6",
          showDenyButton: false,
          confirmButtonText: "Update"
        });

        if (shippingStatus) {
          $.ajax({
            url: `/orders/${order_id}/update-shipping-status`,
            method: "POST",
            data: {
              shipping_status: shippingStatus
            },
            success: function () {
              reloadViewModal('/orders/' + order_id)
              table.ajax.reload();
            }
          })
        }

      })

      //Filtering

      $('#status, #shipping_status, #date_range').on('change', function () {
        table.ajax.reload();
      })

    })

  </script>
@endsection
