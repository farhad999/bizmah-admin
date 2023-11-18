<a href="{{ route('categories.edit', $row) }}" class="btn btn-sm btn-primary">
  <i class="ti ti-edit"></i>
</a>
<button data-href="{{ route('categories.destroy', $row) }}" class="btn btn-sm btn-danger delete-item-btn">
<i class="ti ti-trash"></i>
</button>
