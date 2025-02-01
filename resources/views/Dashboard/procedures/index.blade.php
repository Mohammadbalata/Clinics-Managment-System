@extends('layouts/dashboard')

@section('title', 'Procedures')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Procedures</li>
@endsection


@section('content')
    <div class="mb-5">
        <a href="{{ route('dashboard.procedures.create') }}" class="btn btn-outline-primary btn-sm">Create Procedure</a>
    </div>

    <x-alert type="success" />
    <x-alert type="info" />


    <div class="mb-4">
        <form method="GET" class="form-inline" action="{{ route('dashboard.procedures.index') }}">
            <div class="form-group mr-2">
                <label for="sort_by" class="mr-2">Sort by</label>
                <select name="sort_by" id="sort_by" class="form-control" onchange="this.form.submit()">
                    <option value="id" {{ $sortBy === 'id' ? 'selected' : '' }}>Default</option>
                    <option value="name" {{ $sortBy === 'name' ? 'selected' : '' }}>Name</option>
                    <option value="duration" {{ $sortBy === 'duration' ? 'selected' : '' }}>Duration (min)</option>
                    <option value="coast" {{ $sortBy === 'coast' ? 'selected' : '' }}>Cost</option>
                </select>
            </div>
            <div class="form-group mr-2">
                <label for="sort_dir" class="mr-2">Sort Direction</label>
                <select name="sort_dir" id="sort_dir" class="form-control" onchange="this.form.submit()">
                    <option value="asc" {{ $sortBy === 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ $sortBy === 'desc' ? 'selected' : '' }}>Descending</option>
                </select>
            </div>
        </form>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Clinic</th>
                <th>Description</th>
                <th>Duration (min)</th>
                <th>Coast</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

            @forelse($procedures as $pro)
                <tr>
                    <td> {{ $pro?->id }} </td>
                    <td> {{ $pro?->name }} </td>
                    <td> {{ $pro->clinic->office_name }} </td>
                    <td> {{ $pro->description }} </td>
                    <td> {{ $pro?->duration }} </td>
                    <td> {{ $pro?->coast }} </td>
                    <td>
                        <a href="{{ route('dashboard.procedures.edit', $pro->id) }}"
                            class="btn btn-sm btn-outline-success">Edit</a>

                    </td>
                    <td>
                        <form action="{{ route('dashboard.procedures.destroy', $pro->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No Procedures</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $procedures->withQueryString()->links() }}

@endsection
