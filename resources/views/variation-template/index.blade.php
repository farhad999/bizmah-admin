@php
    $title = 'Variation Template';
@endphp

@extends('layouts.layoutMaster')
@section('title', $title)
@section('content')
  <x-content :title="$title">
    <x-slot name="buttons">
      <a href="{{route('variation-templates.create')}}" class="btn btn-primary"><i class="ti ti-plus me-2"></i>Add New</a>
    </x-slot>

    <table class="table table-bordered" id="datatable">
      <thead>
      <tr>
        <th>Name</th>
        <th>Values</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
      </thead>
    </table>

  </x-content>
@endsection

@section('js')
  <script>
    $(document).ready(function (){
      $('#datatable').DataTable({
        ajax: window.location.pathname,
        columns: [
          {data: 'name'},
          {data: 'values'},
          {data: 'status'},
          {data: 'action'}
        ]
      })
    })
  </script>
@endsection
