@extends('layouts/dashboard')

@section('title',$clinic->name)
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Clinics</li>
<li class="breadcrumb-item active">{{$clinic->office_name}}</li>
@endsection


@section('content')



<div class="container mt-2">
    <a href="{{route('dashboard.clinics.edit',$clinic->id)}}" class="btn btn-sm btn-outline-success mb-2">Edit</a>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h1 class="card-title">{{ $clinic->office_name }}</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="h5"><strong>Address:</strong></p>
                    <p class="h6">{{ $clinic->office_address }}</p>
                </div>
                <div class="col-md-6">
                    <p class="h5"><strong>Timezone:</strong></p>
                    <p class="h6">{{ $clinic->timezone }}</p>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <p class="h5"><strong>Secretary Number:</strong></p>
                    <p class="h6">{{ $clinic->secretary_number }}</p>
                </div>
            </div>
        </div>
    </div>


    <table class="table">
        <thead>
            <tr>
                <th>Day</th>
                <th>Open Time</th>
                <th>Close Time</th>
                <th>Lunch Start</th>
                <th>Lunch End</th>
            </tr>
        </thead>
        <tbody>
            @php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            @endphp

            @foreach ($days as $day)
            <tr>
                <td>{{ $day }}</td>
                <td>{{ $business_hours[$day]['open_time'] ?? '08:00' }}</td>
                <td>{{ $business_hours[$day]['close_time'] ?? '18:00' }}</td>
                <td>{{ $business_hours[$day]['lunch_start'] ?? '10:00' }}</td>
                <td>{{ $business_hours[$day]['lunch_end'] ?? '11:00' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>








@endsection