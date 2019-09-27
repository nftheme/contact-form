<div id="nf-contact-form">
	<form class="{!! $style !!}" name="contact_form" nf-contact>
		@if(!empty($fields))
		@foreach($fields as $field)
		{!! $field->render() !!}
		@endforeach
		@endif
		<input type="hidden" name="type" value="{{ (!empty($type)) ? $type : 'contact' }}">
		<input type="hidden" name="status" value="{{ (!empty($status)) ? $status : '0' }}">
		<input type="hidden" name="name_slug" value="{{ (!empty($name_slug)) ? $name_slug : '0' }}">
		<input type="hidden" name="nf-form-name" value="{{ (!empty($name)) ? $name : '' }}">
	</form>
</div>