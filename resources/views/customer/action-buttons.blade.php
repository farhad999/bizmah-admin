<div class="dropdown">
  <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
    Action
  </button>
  <ul class="dropdown-menu">
    <li>
      <a href="{{route('customers.show', $row->id)}}" class="dropdown-item view-modal-btn">View</a>
    </li>

    <li>
      <a href="{{route('customers.edit', $row->id)}}" class="dropdown-item">Edit</a>
    </li>

    <li>
      <button data-href="{{route('customers.destroy', $row->id)}}" class="dropdown-item delete-item-btn">Delete</button>
    </li>

  </ul>
</div>
