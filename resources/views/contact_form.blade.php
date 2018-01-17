<div id="nf-contact-form">
    <form name="contact_form" nf-contact>
    	@if(!empty($fields)) 
    		@foreach($fields as $field)
                {!! $field->render() !!}
            @endforeach
    	@endif
    	<input type="hidden" name="type" value="{{ (!empty($type)) ? $type : 'contact' }}">
        <input type="submit" class="btn btn-primary" value="Submit">
    </form>
</div>
