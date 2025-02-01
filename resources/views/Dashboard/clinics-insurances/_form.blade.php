@if($errors->any())
<div class="alert alert-danger">
  <h2>Errors</h2>
  @foreach($errors->all() as $error)
  <li>{{$error}}</li>
  @endforeach
</div>
@endif

<!-- <x-form.input label="Office name" type="checkbox" value="" name="office_name" /> -->


<table class="table">
  <thead>
    <tr>
      <th class="text-center">Select</th>
      <th>Logo</th>
      <th>Name</th>
    </tr>
  </thead>
  <tbody>
    @forelse($insurances as $insurance)
      <tr class="{{ $loop->even ? 'table-light' : 'table-secondary' }}">
        <td class="text-center" >
          <input 
            type="checkbox" 
            class="form-check-input" 
            id="insurance-{{ $insurance->id }}" 
            name="insurances[]" 
            value="{{ $insurance->id }}" 
            @checked(in_array($insurance->id, old('insurances', $selected ?? [])))
          >
        </td>
        <td>
          <img src="{{ asset('storage/' . $insurance->logo) }}" alt="{{ $insurance->name }}" style="width: 50px; height: 50px; object-fit: contain;">
        </td>
        <td>{{ $insurance->name }}</td>
      </tr>
    @empty
      <tr>
        <td colspan="3" class="text-center">No insurance plans found.</td>
      </tr>
    @endforelse
  </tbody>
</table>
<button type="submit" class="btn btn-primary">{{$button_label ?? 'Save'}}</button>