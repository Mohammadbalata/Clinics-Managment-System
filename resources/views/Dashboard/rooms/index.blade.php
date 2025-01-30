@extends('layouts/dashboard')

@section('title', 'Rooms')
@section('breadcrumb')
    @parent
    <li class="breadcrumb-item active">Rooms</li>
@endsection


@section('content')
    <div class="mb-5">
        <a href="{{ route('dashboard.rooms.create') }}" class="btn btn-outline-primary btn-sm">Create Room</a>
    </div>

    <x-alert type="success" />
    <x-alert type="info" />



    {{-- ! put the two filters in one form to reduce server hits. --}}
    <div class="mb-4">
        <form action="{{ route('dashboard.rooms.index') }}" method="GET" class="form-inline">
            <div class="form-group mr-2">
                <label for="status" class="mr-2">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="">All</option>
                    @foreach (App\Enums\RoomStatusEnum::cases() as $status)
                        <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group mr-2">
                <label for="type" class="mr-2">Type</label>
                <select name="type" id="type" class="form-control">
                    <option value="">All</option>
                    @foreach (App\Enums\RoomTypeEnum::cases() as $type)
                        <option value="{{ $type->value }}" {{ request('type') == $type->value ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-outline-primary">Filter</button>
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>type</th>
                <th>status</th>
                <th>capacity</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

            @forelse($rooms as $room)
                <tr>
                    <td> {{ $room?->id }} </td>
                    <td>{{ $room?->name }} </td>
                    <td> {{ $room?->type }} </td>
                    <td> {{ $room?->status }} </td>
                    <td> {{ $room?->capacity }} </td>
                    <td>
                        <a href="{{ route('dashboard.rooms.edit', $room->id) }}"
                            class="btn btn-sm btn-outline-success">Edit</a>

                    </td>
                    <td>
                        <form action="{{ route('dashboard.rooms.destroy', $room->id) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No Rooms</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $rooms->withQueryString()->links() }}

@endsection
