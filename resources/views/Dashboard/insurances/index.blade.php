@extends('layouts/dashboard')

@section('title','Insurances')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Insurances</li>
@endsection


@section('content')
<div class="mb-5">
    <a href="{{route('dashboard.insurances.create')}}" class="btn btn-outline-primary btn-sm">Create Insurance</a>
</div>

<x-alert type="success" />
<x-alert type="info" />


<form action="{{URL::current()}}" method="get" class="d-flex justify-content-between gap-4 mb-4">
    <x-form.input name="name" placeholder="Name"  class="mx-2" :value="request('name')" />
    <button type="submit" class="btn btn-dark mx-2">Search</button>
</form>
<table class="table">
    <thead>
        <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Created at</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        
        @forelse($insurances as $insurance)
        <tr>
            <td> <img src="{{asset('storage/' . $insurance->logo)}}" height="50" alt=""> </td>
            <td><a href="{{route('dashboard.insurances.show',$insurance->id)}}">{{$insurance?->name}} </a> </td>
            <td> {{$insurance?->created_at}}</td>
            <td>
                <a href="{{route('dashboard.insurances.edit',$insurance->id)}}" class="btn btn-sm btn-outline-success">Edit</a>
            </td>
            <td>
                <form action="{{ route('dashboard.insurances.destroy',$insurance->id) }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">No Insurances</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $insurances->withQueryString()->links()}}

@endsection