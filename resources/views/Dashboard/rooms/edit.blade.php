@extends('layouts/dashboard')

@section('title', 'Edit Room')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Rooms</li>

    <li class="breadcrumb-item active">Edit Room</li>
@endsection



@section('content')
    <form action="{{ route('dashboard.rooms.update', $room->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        @include('dashboard.rooms._form', ['button_label' => 'Update'])
    </form>



@endsection
