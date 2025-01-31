@if($errors->any())
<div class="alert alert-danger">
  <h2>Errors</h2>
  @foreach($errors->all() as $error)
  <li>{{$error}}</li>
  @endforeach
</div>
@endif

<div class="form-group">
  <x-form.input label="Question" type="text" :value="$faq->question" name="question" />
</div>
<div class="form-group">
  <x-form.input label="Answer" type="text" :value="$faq->answer" name="answer" />
</div>






<button type="submit" class="btn btn-primary">{{$button_label ?? 'Save'}}</button>