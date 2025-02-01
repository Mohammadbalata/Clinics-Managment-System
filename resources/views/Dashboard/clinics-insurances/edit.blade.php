@extends('layouts/dashboard')

@section('title','Edit Clinic')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Clinics</li>

<li class="breadcrumb-item active">Edit Clinic</li>
@endsection



@section('content')
<form action="{{route('dashboard.clinics-insurances.update',$clinic->id)}}" method="post" enctype="multipart/form-data">
  @csrf
  @method('put')
  @include('dashboard.clinics-insurances._form',['button_label' => 'Update'])
</form>



@endsection