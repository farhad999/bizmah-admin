@if($row->username != 'admin')
  <td>
    <a href="{{ route('users.edit', $row->id) }}" class="btn btn-primary btn-sm icon">
      <i class="ti ti-edit"></i>
    </a>
  </td>

  <td>
    <button data-href="{{ route('users.destroy', $row->id) }}" class="btn btn-danger btn-sm icon delete-item-btn">
      <i class="ti ti-trash"></i>
    </button>
  </td>
@endif
