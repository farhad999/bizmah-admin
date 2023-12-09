@props(['method' => 'POST', 'action', 'class' => '', 'files', 'id' => '', 'novalidate' => false])
<form method="POST" action="{{$action}}"
      enctype='multipart/form-data'
      class="{{$class}}"
      @if($id) id="{{$id}}" @endif
      @if($novalidate) novalidate="novalidate" @endif

>
    @csrf
    @method($method)
    {{$slot}}
</form>
