@extends('layouts.layoutMaster')

@section('content')
  <x-content title="Featured Category">
    <x-slot name="buttons">
      <button class="btn btn-primary d-none" id="update_btn">
        <i class="ti ti-file me-2"></i>Save Changes</button>
    </x-slot>
    <div>
      <x-form id="form" action="{{route('featured-categories.update-order')}}" method="POST">
        <div class="row mb-4" id="sort">
          @foreach($featuredCategories as $category)
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3" draggable="false" style="">
              <input type="hidden" name="categories[]" value="{{$category->id}}">
              <div class="card drag-item cursor-move mb-lg-0 mb-4">
                <div class="card-header d-flex justify-content-end">
                  <button class="btn btn-icon delete-item-btn"
                          data-href="{{route('featured-categories.destroy', $category->id)}}"
                  >
                    <i class="ti ti-trash "></i>
                  </button>
                </div>
                <div class="card-body text-center">
                  <div style="height: 200px; overflow: hidden">
                    <img src="{{asset('/storage/'. $category->image)}}"
                         class="img-fluid w-100" alt="image">
                  </div>
                  <h4>{{$category->name}}</h4>
                </div>
              </div>
            </div>
          @endforeach
        </div>

      </x-form>

      <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-12">
          <div class="card">
            <div class="card-body">
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"
                        aria-expanded="false">
                  <i class="ti ti-plus fs-4"></i>Add New
                </button>
                <div class="dropdown-menu">
                  @forelse($categories as $category)
                    <x-form action="{{'/featured-categories/'.$category->id.'/add'}}" method="POST">
                      <button class="dropdown-item"
                              onclick="this.form.submit()"
                      >{{$category->name}}</button>
                    </x-form>
                  @empty
                    <div class="dropdown-item-text">No Category Left</div>
                  @endforelse
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </x-content>
@endsection

@section('vendor-script')
  <script src="{{asset(mix('assets/vendor/libs/sortablejs/sortable.js'))}}"></script>
@endsection

@section('js')
  <script>
    let sortable = document.getElementById('sort');
    new Sortable(sortable, {
      onEnd: function (e) {
        $('#update_btn').removeClass('d-none');
      }
    })

    $('#update_btn').on('click', function (e) {
      e.preventDefault();
      $('#form').submit();
    })

  </script>
@endsection
