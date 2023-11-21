<div class="mb-3">
  <h5>Product Price</h5>
  <table class="table table-bordered">
    <thead>
    <tr>
      <th>Old Price</th>
      <th>Price</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>
        <input type="hidden"
               name="{{'variations[0][name]'}}"
               value="single"
        />
        <x-form.input
          name="{{'variations[0][old_price]'}}"
          placeholder="Old Price"
        />
      </td>
      <td>
        <x-form.input
          name="{{'variations[0][price]'}}"
          placeholder="Price"
        />
      </td>
    </tr>
    </tbody>
  </table>
</div>
