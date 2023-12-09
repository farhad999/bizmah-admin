@extends('layouts.layoutMaster')

@section('title', 'Pages')

@section('content')
  <x-content title="Pages">

    <x-slot name="buttons">
      <a href="{{route('pages.create')}}"
      class="btn btn-primary"
      >Create Page</a>
    </x-slot>

    <div>
      <table class="table table-bordered">
        <thead>
        <tr>
          <th>Name</th>
          <th>Slug</th>
          <th>Action</th>
        </thead>
        <tbody>
        @foreach ($pages as $page)
          <tr>
            <td>{{ $page->title }}</td>
            <td>{{ $page->slug }}</td>
            <td>
              <a href="{{ route('pages.edit', $page->id) }}"
              class="btn btn-primary btn-icon"
              >
                <i class="ti ti-edit"></i>
              </a>
              <button class="btn btn-danger btn-icon delete-item-btn"
              data-href="{{route('pages.destroy', $page->id)}}"
              >
                <i class="ti ti-trash"></i>
              </button>
            </td>
          </tr>
        @endforeach
      </table>
    </div>
  </x-content>
@endsection
