@extends('layouts/dashboard')

@section('title','Create Procedure')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Create Procedure</li>
@endsection


@section('content')

<form action="{{route('dashboard.procedures.store')}}" method="post" enctype="multipart/form-data">
  @csrf
  @include('dashboard.procedures._form',['button_label' => 'Create'])


</form>



@endsection