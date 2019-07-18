import {
    notify
}
from 'vicoders/services';

import {
    Form
}
from './form';

import './admin';

(function($) {
    $(document).ready(function() {
        submitForm();
    });

    document.addEventListener('complaintFormLoaded', function() {
        submitForm();
    })

    function submitForm() {
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
                            window.location.href = self.location;
//                             if(response.data.current_lang === 'en') {
//                                window.location.href = "/en";
//                             }
//                             else {
//                                 window.location.href="/";
//                             }
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
    }

})(jQuery)
