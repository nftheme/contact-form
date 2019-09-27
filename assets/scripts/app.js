import { notify } from "vicoders/services";

import { Form } from "./form";

import "./admin";

(function($) {
  $(document).ready(function() {
    submitForm();
  });

  document.addEventListener("complaintFormLoaded", function() {
    submitForm();
  });

  function submitForm() {
    $(document)
      .find("[nf-contact]")
      .each(function(key, item) {
        let form = new Form(item);
        form.on("submit", event => {
          if (!form.isDisabled()) {
            form.disable();
            $.ajax({
              method: "POST",
              url: ajax_obj.ajax_url,
              data: Object.assign(form.getValues(), {
                action: "handle_contact_form"
              })
            })
              .done(response => {
                if (response.data.message !== undefined) {
                  notify.show("success", response.data.message, 5000);
                }
                if (response.data.redirect !== undefined) {
                  //   window.location.href = "/en/thanks";
                  //   window.location.href = "/cam-on";
                  window.location.href = response.data.redirect;
                }
              })
              .fail(() => {
                notify.show("warning", "An error is occur", 5000);
              })
              .always(() => {
                form.enable();
              });
          }
          return false;
        });
      });
  }
})(jQuery);
