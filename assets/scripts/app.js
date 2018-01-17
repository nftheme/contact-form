import {
    notify
}
from 'vicoders/services';

import {
    Form
}
from './form';

(function($) {
    let forms = [];
    $(document).find('[nf-contact]').each(function(key, item) {
        let form = new Form(item);
        form.on('submit', (event) => {
            if (!form.isDisabled()) {
                form.disable();
                $.ajax({
                        method: 'POST',
                        url: ajax_obj.ajax_url,
                        data: Object.assign(form.getValues(), {
                            action: 'handle_contact_form'
                        }),

                    })
                    .done((response) => {
                        notify.show('success', response.data.message, 5000)
                    })
                    .fail(() => {
                        notify.show('warning', 'An error is occur', 5000);
                    })
                    .always(() => {
                        form.enable();
                    });
            }
            return false;
        });
    })

    console.log(forms);


})(jQuery)
