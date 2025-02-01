@extends('layouts/dashboard')

@section('title','Create FAQ')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Create FAQ</li>
@endsection


@section('content')

<form action="{{route('dashboard.faq.store',$clinicId)}}" method="post" enctype="multipart/form-data">
  @csrf
  @include('dashboard.faq._form',['button_label' => 'Create'])


</form>



@endsection