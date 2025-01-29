@extends('layouts/dashboard')

@section('title','Clinics')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Clinics</li>
@endsection


@section('content')
<div class="mb-5">
    <a href="{{route('dashboard.clinics.create')}}" class="btn btn-outline-primary btn-sm">Create Clinic</a>
</div>

<x-alert type="success" />
<x-alert type="info" />


<form action="{{URL::current()}}" method="get" class="d-flex justify-content-between gap-4 mb-4">
    <x-form.input name="office_name" placeholder="Name"  class="mx-2" :value="request('name')" />
    <button type="submit" class="btn btn-dark mx-2">Search</button>
</form>
<table class="table">
    <thead>
        <tr>
            <th>Id</th>
            <th>Office Name</th>
            <th>Office Address</th>
            <th>timezone</th>
            <th>secretary_number</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        
        @forelse($clinics as $clinic)
        <tr>
            <td> {{$clinic?->id}} </td>
            <td><a href="{{route('dashboard.clinics.show',$clinic->id)}}">{{$clinic?->office_name}} </a> </td>
            <td> {{$clinic?->office_address}} </td>
            <td> {{$clinic?->timezone }} </td>
            <td> {{$clinic?->secretary_number}} </td>
            <td>
                <a href="{{route('dashboard.clinics.edit',$clinic->id)}}" class="btn btn-sm btn-outline-success">Edit</a>

            </td>
            <td>
                <form action="{{ route('dashboard.clinics.destroy',$clinic->id) }}" method="post">
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

{{ $clinics->withQueryString()->links()}}

@endsection