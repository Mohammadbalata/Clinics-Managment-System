@extends('layouts/dashboard')

@section('title','Create Room')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Create Room</li>
@endsection


@section('content')

<form action="{{route('dashboard.rooms.store')}}" method="post" enctype="multipart/form-data">
  @csrf
  @include('dashboard.rooms._form',['button_label' => 'Create'])


</form>



@endsection