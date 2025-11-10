define([
    'jquery',
    'Magento_Ui/js/lib/validation/validator',
    'Magento_Ui/js/form/element/abstract'
], function ($, validator, Abstract) {
    'use strict';

    return Abstract.extend({
        initialize: function() {
            this._super();
            validator.addRule(
                'validate-cellphone',
                function(value) {
                    if (value) {
                        return value.length >= 14;
                    }
                    return true;
                },
                $.mage.__('Informe um número válido')
            );
            return this;
        },

        onUpdate: function () {
            $('input[name="telephone"]').attr('maxlength', '15');
            $('input[name="fax"]').attr('maxlength', '15');
            var v = this.value();
            v = v.replace(/\D/g, "");
            if (v === "") {
                this.value(v);
                return;
            }
            v = v.replace(/^0/, "");
            if (v.length > 10) {
                v = v.replace(/^(\d{2})(\d{5})(\d{4}).*/, "($1) $2-$3");
            } else if (v.length > 5) {
                v = v.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, "($1) $2-$3");
            } else if (v.length > 2) {
                v = v.replace(/^(\d{2})(\d{0,5})/, "($1) $2");
            } else {
                v = v.replace(/^(\d*)/, "($1");
            }
            this.value(v);
        }
    });
});
