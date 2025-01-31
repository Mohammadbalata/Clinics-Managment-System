@extends('layouts/dashboard')

@section('title','Clinics')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Clinics</li>
@endsection


@section('content')
<div class="mb-5">
<a href="{{route('dashboard.faq.create',$clinic->id)}}" class="btn btn-outline-primary btn-sm">Create FAQ for this clinic</a>
</div>

<x-alert type="success" />
<x-alert type="info" />


<table class="table">
    <thead>
        <tr>
            <th>Question</th>
           
            <th>Answer</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        
        @forelse($faqs as $faq)
        <tr>
            <td> {{$faq?->question}} </td>
            <td> {{$faq?->answer }} </td>
            <td class="d-flex ">
            <a href="{{ route('dashboard.faq.edit', ['clinic' => $faq->clinic->id, 'faq' => $faq->id]) }}" class="btn btn-sm btn-outline-success mr-3">Edit</a>
        
           
                <form action="{{ route('dashboard.faq.destroy', ['clinic' => $faq->clinic->id, 'faq' => $faq->id])  }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7">No FAQ</td>
        </tr>
        @endforelse
    </tbody>
</table>


@endsection