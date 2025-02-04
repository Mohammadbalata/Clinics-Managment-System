@if ($errors->any())
    <div class="alert alert-danger">
        <h2>Errors</h2>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>
@endif

<div class="form-group">
    <x-form.input label="name" type="text" :value="$procedure->name" name="name" />
</div>

<div class="form-group">
    <x-form.textarea label="description" title="description" name="description" value="{{ $procedure->description }}" />
</div>

<div class="form-group">
    <x-form.input label="duration" type="number" :value="$procedure->duration" name="duration" />
</div>

<div class="form-group">
    <x-form.input label="coast" type="number" :value="$procedure->coast" name="coast" />
</div>

<div class="form-group mr-2">
    <label for="doctor_id" class="mr-2">doctor</label>
    <select name="doctor_id" id="doctor_id" class="form-control">
        @foreach (App\Models\Doctor::all() as $doctor)
            <option value="{{ $doctor->id }}" @selected(old('doctor_id', $doctor->doctor_id ?? null) == $doctor->id)>
                {{ $doctor->first_name }} {{ $doctor->last_name }}
            </option>
        @endforeach
    </select>
</div>
<div class="form-group mr-2">
    <label for="room_id" class="mr-2">Room</label>
    <select name="room_id" id="room_id" class="form-control">
        @foreach (App\Models\Room::all() as $room)
            <option value="{{ $room->id }}" @selected(old('room_id', $room->room_id ?? null) == $room->id)>
                {{ $room->name }}
            </option>
        @endforeach
    </select>
</div>

<button type="submit" class="btn btn-primary">{{ $button_label ?? 'Save' }}</button>
