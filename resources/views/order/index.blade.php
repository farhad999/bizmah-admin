@extends('layouts.layoutMaster')

@section('content')
  <x-content title="Orders">

    <x-slot name="buttons">
      <a href="{{route("orders.create")}}">Create</a>
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

@section('js')
  <script>
    $(document).ready(function () {
      let table = $('#datatable').DataTable({
        ajax: window.location.pathname,
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

      function updateStatus(title, type, icon = 'question'){

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

    })

  </script>
@endsection
