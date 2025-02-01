@extends('layouts/dashboard')

@section('title','Create Doctor')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Create Doctor</li>
@endsection


@section('content')

<form action="{{route('dashboard.doctors.store')}}" method="post" enctype="multipart/form-data">
  @csrf
  @include('dashboard.doctors._form',['button_label' => 'Create'])


</form>



@endsection