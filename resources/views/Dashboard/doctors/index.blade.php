@extends('layouts/dashboard')

@section('title','Doctors')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Doctors</li>
@endsection


@section('content')
<div class="mb-5">
    <a href="{{route('dashboard.doctors.create')}}" class="btn btn-outline-primary btn-sm">Create Doctor</a>
</div>

<x-alert type="success" />
<x-alert type="info" />


<form action="{{URL::current()}}" method="get" class="d-flex justify-content-between gap-4 mb-4">
    <x-form.input name="first_name" placeholder="Name"  class="mx-2" :value="request('name')" />
    <select  name="specialty" class="form-control form-select">
        <option value="">Specialty</option>
        @foreach(App\Constants\DoctorSpecialties::LIST as $specialty)
            <option value="{{ $specialty }}" @selected(old('specialty', request('specialty')) == $specialty)>
                {{ ucwords(str_replace('_', ' ', $specialty)) }}
            </option>
        @endforeach
        
    </select>
    <select  name="id" class="form-control form-select">
        <option value="">Procedure</option>
        @foreach($procedures as $procedure)
            <option value="{{ $procedure->doctor_id }}">
                {{ $procedure->name }}
            </option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-dark mx-2">Search</button>
</form>

<table class="table">
    <thead>
        <tr>
            <th>Doctor Name</th>
            <th>Clinic Name</th>
            <th>Specialty</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        
        @forelse($doctors as $doctor)
        <tr>
            <td><a href="{{route('dashboard.doctors.show',$doctor->id)}}">Dr. {{$doctor->full_name}} </a> </td>
            <td> {{$doctor?->clinic->office_name}} </td>
            <td> {{   ucwords(str_replace('_', ' ', $doctor?->specialty))  }} </td>
            <td>
                <a href="{{route('dashboard.doctors.edit',$doctor->id)}}" class="btn btn-sm btn-outline-success">Edit</a>

            </td>
            <td>
                <form action="{{ route('dashboard.doctors.destroy',$doctor->id) }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">No Clinics</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $doctors->withQueryString()->links()}}

@endsection