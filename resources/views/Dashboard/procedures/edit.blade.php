@extends('layouts/dashboard')

@section('title', 'Edit Procedure')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Procedures</li>

    <li class="breadcrumb-item active">Edit Procedure</li>
@endsection



@section('content')
    <form action="{{ route('dashboard.procedures.update', $procedure->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        @include('dashboard.procedures._form', ['button_label' => 'Update'])
    </form>



@endsection
