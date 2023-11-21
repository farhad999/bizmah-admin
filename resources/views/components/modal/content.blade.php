@props(['hideFooter' => false, 'size' => 'md', 'title'])
<div class="modal-dialog modal-{{$size}}">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title fs-5">{{$title}}</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{$slot}}
        </div>
        @if(!$hideFooter)
            <div class="modal-footer">
                <button data-bs-dismiss="modal" class="btn btn-primary">Close</button>
            </div>
        @endif
    </div>
</div>
