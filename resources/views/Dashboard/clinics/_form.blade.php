@if($errors->any())
<div class="alert alert-danger">
  <h2>Errors</h2>
  @foreach($errors->all() as $error)
  <li>{{$error}}</li>
  @endforeach
</div>
@endif

<div class="form-group">
  <x-form.input label="Office name" type="text" :value="$clinic->office_name" name="office_name" />

</div>
<div class="form-group">
  <x-form.input label="Office address" type="text" :value="$clinic->office_address" name="office_address" />
</div>

<div class="form-group">
  <label for="name">Office timezone</label>
  <select name="timezone" class="form-control form-select">
    <option value="">Primary timezone</option>
    <option value="UTC">UTC</option>
    <option value="Asia/Dubai">Asia/Dubai (GST)</option>
    <option value="America/New_York">America/New_York (EST)</option>
    <option value="Europe/London">Europe/London (GMT)</option>
    <option value="Asia/Riyadh">Asia/Riyadh (AST)</option>
  </select>
</div>

<div class="form-group">
  <x-form.input label="Secretary Number" type="text" :value="$clinic->secretary_number" name="secretary_number" />
</div>

<!-- ////////////////////////////////////////////////////////////////// -->
<table class="table">
  <thead>
    <tr>
      <th>Day</th>
      <th>Open Time</th>
      <th>Close Time</th>
      <th>Lunch Start</th>
      <th>Lunch End</th>
    </tr>
  </thead>
  <tbody>
    @php
    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    @endphp

    @foreach ($days as $day)
   
    <tr>
      <td>{{ $day }}</td>
      <td>
        <x-form.input type="time" :value="($business_hours[$day]['open_time'] ?? '08:00')" name="business_hours[{{ $day }}][open_time]" />
      </td>
      <td>
        <x-form.input type="time" :value="($business_hours[$day]['close_time'] ?? '18:00')" name="business_hours[{{ $day }}][close_time]" />
      </td>
      <td>
        <x-form.input type="time" :value="($business_hours[$day]['lunch_start'] ?? '10:00')" name="business_hours[{{ $day }}][lunch_start]" />
      </td>
      <td>
        <x-form.input type="time" :value="($business_hours[$day]['lunch_end'] ?? '11:00')" name="business_hours[{{ $day }}][lunch_end]" />
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
<button type="submit" class="btn btn-primary">{{$button_label ?? 'Save'}}</button>