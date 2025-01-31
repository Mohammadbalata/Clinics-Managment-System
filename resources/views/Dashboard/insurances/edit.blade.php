@extends('layouts/dashboard')

@section('title','Edit Insurance')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Categories</li>

<li class="breadcrumb-item active">Edit Insurance</li>
@endsection



@section('content')

<form action="{{route('dashboard.insurances.update',$insurance->id)}}" method="post" enctype="multipart/form-data">
  @csrf
  @method('put')
  @include('dashboard.insurances._form',['button_label' => 'Update'])
</form>



@endsection