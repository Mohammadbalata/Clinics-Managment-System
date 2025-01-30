@if ($errors->any())
    <div class="alert alert-danger">
        <h2>Errors</h2>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>
@endif

<div class="form-group">
    <x-form.input label="name" type="text" :value="$room->name" name="name" />
    <x-form.input label="capacity" type="number" :value="$room->capacity" name="capacity" />
</div>

<div class="form-group mr-2">
    <label for="status" class="mr-2">Status</label>
    <select required name="status" id="status" class="form-control">
        @foreach (App\Enums\RoomStatusEnum::cases() as $status)
            <option value="{{ $status->value }}" {{ $room->status == $status->value ? 'selected' : '' }}>
                {{ $status->name }}
            </option>
        @endforeach
    </select>
</div>



<div class="form-group mr-2">
    <label for="type" class="mr-2">Type</label>
    <select name="type" id="type" class="form-control">
        @foreach (App\Enums\RoomTypeEnum::cases() as $type)
            <option value="{{ $type->value }}" {{ $room->type == $type->value ? 'selected' : '' }}>
                {{ $type->name }}
            </option>
        @endforeach
    </select>
</div>



<div class="form-group mr-2">
    <label for="clinic_id" class="mr-2">Clinic</label>
    <select name="clinic_id" id="clinic_id" class="form-control">
        @foreach (App\Models\Clinic::all() as $clinic)
            <option value="{{ $clinic->id }}" @selected(old('clinic_id', $room->clinic_id ?? null) == $clinic->id)>
                {{ $clinic->office_name }}
            </option>
        @endforeach
    </select>
</div>

<button type="submit" class="btn btn-primary">{{ $button_label ?? 'Save' }}</button>
