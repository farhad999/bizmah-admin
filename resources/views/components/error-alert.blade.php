@props(['errors' => []])

<div class="row">
  <div class="col-sm-12">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="text-center">
          @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </ul>
      </div>
    @endif
  </div>
</div>
