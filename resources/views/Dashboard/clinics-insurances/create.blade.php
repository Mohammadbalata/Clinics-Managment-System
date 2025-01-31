@extends('layouts/dashboard')

@section('title','Choose your Insurances')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Insurances</li>
@endsection


@section('content')

<form action="{{route('dashboard.clinics-insurances.store',$clinicId)}}" method="post" enctype="multipart/form-data">
  @csrf
  @include('dashboard.clinics-insurances._form',['button_label' => 'Add'])


</form>



@endsection