<h6>{{ __('Users') }} </h6>
<select id="user" name="user[]" data-placeholder="Select Users" multiple="multiple" data-live-search="true">		
	@foreach($users as $val)
		<option value="{{ $val['id'] }}" >{{ $val['name'] }}</option>
	@endforeach
</select>