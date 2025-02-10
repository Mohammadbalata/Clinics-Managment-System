@if ($errors->any())
    <div class="alert alert-danger">
        <h2>Errors</h2>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>
@endif

<div class="form-group">
    <x-form.input label="First Name" type="text" :value="$doctor->first_name" name="first_name" />
</div>
<div class="form-group">
    <x-form.input label="Last Name" type="text" :value="$doctor->last_name" name="last_name" />
</div>

<div class="form-group">
    <label for="specialty">Specialty</label>
    <select name="specialty" class="form-control form-select">
        <option value="">Select Specialty</option>
        @foreach(App\Constants\DoctorSpecialties::LIST as $specialty)
            <option value="{{ $specialty }}" @selected(old('specialty', $doctor->specialty) == $specialty)>
                {{ ucwords(str_replace('_', ' ', $specialty)) }}
            </option>
        @endforeach
    </select>
</div>




<div class="form-group">
    <x-form.input label="Email" type="email" :value="$doctor->email" name="email" />
</div>

<div class="form-group">
    <x-form.input label="Phone" type="number" :value="$doctor->phone" name="phone" />
</div>
<div class="form-group mr-2">
    <label for="clinic_id" class="mr-2">clinic</label>
    <select name="clinic_id" id="clinic_id" class="form-control">
        @foreach ($clinics as $clinic)
            <option value="{{ $clinic->id }}" @selected(old('clinic_id', $clinic->id ?? null) == $doctor->clinic_id)>
                {{ $clinic->office_name }}
            </option>
        @endforeach
    </select>
</div>


<button type="submit" class="btn btn-primary">{{ $button_label ?? 'Save' }}</button>
