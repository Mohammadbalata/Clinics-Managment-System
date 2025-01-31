@if($errors->any())
<div class="alert alert-danger">
  <h2>Errors</h2>
  @foreach($errors->all() as $error)
  <li>{{$error}}</li>
  @endforeach
</div>
@endif

<div class="form-group">
  <x-form.input label="Insurance name" type="text" :value="$insurance->name" name="name" />
</div>

<div class="form-group">
  <x-form.textarea name="description" :value="$insurance->description" label="Description" />
</div>
@if($insurance->logo)
<img class="mt-5" height="200" src="{{asset('storage/' . $insurance->logo)}}" />
@endif
<div class="form-group">
  <x-form.input label='Logo' type="file" value="" name="logo" />

</div>

<button type="submit" class="btn btn-primary">{{$button_label ?? 'Save'}}</button>