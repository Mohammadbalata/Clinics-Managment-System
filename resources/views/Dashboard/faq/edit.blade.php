@extends('layouts/dashboard')

@section('title','Edit FAQ')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">FAQs</li>

<li class="breadcrumb-item active">Edit FAQ</li>
@endsection



@section('content')
<form action="{{route('dashboard.faq.update',['clinic' => $faq->clinic->id, 'faq' => $faq->id])}}" method="post" enctype="multipart/form-data">
  @csrf
  @method('put')
  @include('dashboard.faq._form',['button_label' => 'Update'])
</form>



@endsection