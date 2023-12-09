@php
  $configData = Helper::appClasses();
@endphp

@section('vendor-style')
  <link rel="stylesheet"
        href="{{asset(mix('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css'))}}">
@endsection

@extends('layouts.layoutMaster')

@section('title', 'Home')

@section('content')
  <div class="d-flex justify-content-between align-items-center">
    <h3>Dashboard</h3>
    <x-form.input
      name="date_range"
      id="date_range"
    />
  </div>

  <div>

    <div class="row">

      <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card h-100">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div class="card-title mb-0">
              <h5 class="mb-0 me-2" id="total_orders"></h5>
              <small>All Orders</small>
            </div>
            <div class="card-icon">
          <span class="badge bg-label-primary rounded-pill p-2">
            <i class="ti ti-shopping-cart ti-sm"></i>
          </span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card h-100">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div class="card-title mb-0">
              <h5 class="mb-0 me-2" id="total_confirmed_orders"></h5>
              <small>Confirmed Orders</small>
            </div>
            <div class="card-icon">
          <span class="badge bg-label-success rounded-pill p-2">
            <i class="ti ti-check ti-sm"></i>
          </span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card h-100">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div class="card-title mb-0">
              <h5 class="mb-0 me-2" id="total_pending_orders"></h5>
              <small>Pending Orders</small>
            </div>
            <div class="card-icon">
          <span class="badge bg-label-warning rounded-pill p-2">
            <i class="ti ti-clock ti-sm"></i>
          </span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card h-100">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div class="card-title mb-0">
              <h5 class="mb-0 me-2" id="total_cancelled_orders"></h5>
              <small>Cancelled Orders</small>
            </div>
            <div class="card-icon">
          <span class="badge bg-label-danger rounded-pill p-2">
            <i class="ti ti-shopping-cart-x ti-sm"></i>
          </span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-header">
            <h4>Top Products</h4>
          </div>
          <div class="card-body">
            <div id="top_product_container"></div>
          </div>
        </div>
      </div>

      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-header">
            <h4>Orders Pie Chart</h4>
          </div>
          <div class="card-body">
            <canvas id="order_pie_chart"></canvas>
          </div>
        </div>
      </div>

      <div class="col-12 mb-4">
        <div class="card">
          <div class="card-header">
            <h4>Orders Last 30 days</h4>
          </div>
          <div class="card-body">
            <canvas id="last_30_days_bar_chart"></canvas>
          </div>
        </div>
      </div>

      <div class="col-12 mb-4">
        <div class="card">
          <div class="card-header">
            <h4>Order Comparison with Last Month</h4>
          </div>
          <div class="card-body">
            <canvas id="last_month_comparison_line_chart"></canvas>
          </div>
        </div>
      </div>

      @endsection

      @section('vendor-script')
        {{--add moment js--}}
        <script src="{{asset(mix('assets/vendor/libs/moment/moment.js'))}}"></script>
        <script
          src="{{asset(mix('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js'))}}"></script>
        {{-- Add chart js --}}
        <script src="{{asset(mix('assets/vendor/libs/chartjs/chartjs.js'))}}"></script>
      @endsection

      @section('js')
        <script>
          $(document).ready(function () {

            //orders pie chart

            let ctx = document.getElementById('order_pie_chart').getContext('2d');
            let orderPieChart = new Chart(ctx, {
              type: 'pie',
              data: {
                labels: ['Confirmed', 'Pending', 'Cancelled'],
                datasets: [{
                  data: [0, 0, 0],
                  backgroundColor: ['#36a2eb', '#ffcc56', '#ff6384']
                }]
              }
            });

            //bar chart

            let ctx2 = document.getElementById('last_30_days_bar_chart').getContext('2d');
            let last30DaysBarChart = new Chart(ctx2, {
              type: 'bar',
              data: {
                labels: [],
                datasets: [{
                  label: 'Orders',
                  data: [],
                  backgroundColor: 'rgba(75, 192, 192, 0.2)', // Set a single color here
                  borderColor: 'rgba(75, 192, 192, 1)',
                  borderWidth: 1
                }]
              },
              options: {
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              }
            });

            //last 3 month line chart

            let ctx3 = document.getElementById('last_month_comparison_line_chart').getContext('2d');
            let lastMonthOrderComparison = new Chart(ctx3, {
              type: 'line',
              data: {
                labels: [],
                datasets: [{
                  label: 'Current Month',
                  data: [],
                  backgroundColor: 'rgba(75, 192, 192, 0.2)', // Set a single color here
                  borderColor: 'rgba(75, 192, 192, 1)',
                  borderWidth: 1,
                  tension: 0.3
                },
                  {
                    label: 'Last Month',
                    data: [],
                    backgroundColor: 'rgb(10,136,82)', // Set a single color here
                    borderColor: 'rgb(10,136,82)',
                    borderWidth: 1,
                    tension: 0.3
                  },
                ]
              },
              options: {
                scales: {
                  y: {
                    beginAtZero: true
                  }
                }
              }
            })

            $('#date_range').daterangepicker({
              startDate: moment().subtract(6, 'days'),
              endDate: moment(),
              locale: {
                format: 'DD/MM/YYYY',
              },
              ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 days': [moment().subtract(6, 'days'), moment()],
                'Last 30 days': [moment().subtract(29, 'days'), moment()],
              }
            });

            $('#date_range').on('change', function () {
              loadHomeCards()
            });


            loadHomeCards()

            function loadHomeCards() {
              let date_range = $('#date_range').val();

              $.ajax({
                type: "GET",
                url: window.location.pathname,
                data: {
                  date_range
                },
                success: function (data) {
                  let {
                    totalOrders, totalConfirmedOrders, totalPendingOrders,
                    totalCancelledOrders,
                    topOrderedProductsTable,
                  } = data;

                  $('#total_orders').text(totalOrders)
                  $('#total_confirmed_orders').text(totalConfirmedOrders)
                  $('#total_pending_orders').text(totalPendingOrders)
                  $('#total_cancelled_orders').text(totalCancelledOrders)

                  //html data
                  $('#top_product_container').html(topOrderedProductsTable)

                  //update pie chart data

                  orderPieChart.data.datasets[0].data = [totalConfirmedOrders, totalPendingOrders, totalCancelledOrders]
                  orderPieChart.update()

                }
              });
            }

            //load last 30 days data

            $.ajax({
              type: "GET",
              url: '/get-last-30-days-orders-data',
              success: function (data) {
                last30DaysBarChart.data.labels = data.map(item => item.date)
                last30DaysBarChart.data.datasets[0].data = data.map(item => item.total)
                last30DaysBarChart.update()
              }
            })

            //get comparison data
            $.ajax({
              type: "GET",
              url: '/get-comparison-orders',
              success: function (data) {

                //let labels = data[0].map(item => item.date)

                let {currentMonth, lastMonth} = data

                lastMonthOrderComparison.data.labels = currentMonth.map(item => item.date)

                //.log({labels})

               lastMonthOrderComparison.data.datasets[0].data = currentMonth.map(item => item.total)
                //console.log({currentMonthData})
                lastMonthOrderComparison.data.datasets[1].data = lastMonth.map(item => item.total)
                lastMonthOrderComparison.update()
              }
            })

          })
        </script>
@endsection
