@extends('layouts/dashboard')

@section('title', 'Edit Doctor')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Doctors</li>

    <li class="breadcrumb-item active">Edit Doctor</li>
@endsection



@section('content')
    <form action="{{ route('dashboard.doctors.update', $doctor->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        @include('dashboard.doctors._form', ['button_label' => 'Update'])
    </form>



@endsection
