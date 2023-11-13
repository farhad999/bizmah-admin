@props(['title'])


<div>
  <div>
    {{$breadcrumbs ?? ''}}
  </div>

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h4>{{$title}}</h4>
      <!--buttons-->
      @if(!empty($buttons))
        <div class="d-flex align-items-center">
          {{$buttons}}
        </div>
      @endif
    </div>
    <div class="card-body">
      {{$slot}}
    </div>
  </div>

</div>
