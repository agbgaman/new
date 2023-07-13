<h6>{{ __('City') }} </h6>
<select id="city" name="city" data-placeholder="Select City">
	<option value="" disabled selected> Select City</option>		
	@foreach($cities as $val)
	<option value="{{$val}}">{{$val}}</option>
	@endforeach
</select>