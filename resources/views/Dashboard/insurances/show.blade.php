@extends('layouts/dashboard')

@section('title', 'Insurance Details')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Insurance</li>
<li class="breadcrumb-item active">{{ $insurance->name }}</li>
@endsection

@section('content')

<div class="container mt-2">

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h1 class="card-title">{{ $insurance->name }}</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <!-- Display Insurance Logo -->
                    <img src="{{asset('storage/' . $insurance->logo)}}" height="50" alt="" style="max-height: 150px; max-width: 150px;">
                </div>
                <div class="col-md-6">
                    <!-- Insurance Name -->
                    <p class="h5"><strong>Name:</strong> {{ $insurance->name }}</p>
                    <!-- Insurance Description -->
                    <p class="h6"><strong>Description:</strong> {{ $insurance->description }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
