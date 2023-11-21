<div class="dropdown">
  <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
    Action
  </button>
  <ul class="dropdown-menu">
    <li>
      <button data-href="{{route('products.show', $row->id)}}" class="dropdown-item view-modal-btn">View</button>
    </li>
    <li><a href="{{route('products.edit', $row->id)}}" class="dropdown-item">Edit</a></li>
    <li>
      <button data-href="{{route('products.destroy', $row->id)}}" class="dropdown-item delete-item-btn">Delete</button>
    </li>
    <li>
      <button data-href="{{route('products.image-gallery', $row->id)}}" class="dropdown-item image-gallery-btn">Image Gallery</button>
    </li>
  </ul>
</div>



