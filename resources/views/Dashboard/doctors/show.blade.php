@extends('layouts/dashboard')

@section('title', 'Doctor Details')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Doctor</li>
<li class="breadcrumb-item active">{{ $doctor->name }}</li>
@endsection

@section('content')

<div class="container mt-2">
<div class="card">
    <div class="card-header bg-primary text-white">
        <h1 class="card-title">Dr. {{ $doctor->full_name }}</h1>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p class="h5"><strong>Specialty:</strong></p>
                <p class="h6">{{ $doctor->specialty }}</p>
            </div>
            <div class="col-md-6">
                <p class="h5"><strong>Email:</strong></p>
                <p class="h6">{{ $doctor->email }}</p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <p class="h5"><strong>Phone:</strong></p>
                <p class="h6">{{ $doctor->phone }}</p>
            </div>
            <div class="col-md-6">
                <p class="h5"><strong>Clinic:</strong></p>
                <p class="h6">{{ $doctor->clinic->name }}</p>
            </div>
        </div>
    </div>
</div>

    
</div>

@endsection
