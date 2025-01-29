@extends('layouts/dashboard')

@section('title','Create Clinic')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Create Clinic</li>
@endsection


@section('content')

<form action="{{route('dashboard.clinics.store')}}" method="post" enctype="multipart/form-data">
  @csrf
  @include('dashboard.clinics._form',['button_label' => 'Create'])


</form>



@endsection