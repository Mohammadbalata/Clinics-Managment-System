@extends('layouts/dashboard')

@section('title','Create Insurance')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Create Insurance</li>
@endsection


@section('content')

<form action="{{route('dashboard.insurances.store')}}" method="post" enctype="multipart/form-data">
  @csrf
  @include('dashboard.insurances._form',['button_label' => 'Create'])


</form>



@endsection