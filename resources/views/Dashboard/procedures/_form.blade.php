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
    <label for="clinic_id" class="mr-2">clinic</label>
    <select name="clinic_id" id="clinic_id" class="form-control">
        @foreach (App\Models\Clinic::all() as $clinic)
            <option value="{{ $clinic->id }}" @selected(old('clinic_id', $clinic->clinic_id ?? null) == $clinic->id)>
                {{ $clinic->office_name }}
            </option>
        @endforeach
    </select>
</div>

<button type="submit" class="btn btn-primary">{{ $button_label ?? 'Save' }}</button>
