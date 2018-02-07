export class Form {
    constructor(el) {
        this.el = $(el);
        this.disabled = false;
    }

    getValues() {
        let values = new Object;
        this.el.find(':input').each((k, i) => {
            if (!$(i).is('button') && !$(i).is('input[type="submit"]')) {
                values[$(i).attr('name')] = $(i).val();
            }
        });
        return values;
    }
    on(event, callable) {
        this.el.on(event, callable);
    }
    disable() {
        this.disabled = true;
        this.el.addClass('disabled');
    }
    enable() {
        this.disabled = false;
        this.el.removeClass('disabled');
    }
    isDisabled() {
        return this.disabled;
    }
}
