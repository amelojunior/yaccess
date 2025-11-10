/**
 * Copyright © Evoxlab.
 */
define([
  "uiRegistry",
  "Magento_Ui/js/form/element/abstract",
  "jquery",
  "mage/url",
  "Magento_Checkout/js/model/shipping-service",
  "Magento_Checkout/js/model/quote",
  "Magento_Checkout/js/model/resource-url-manager",
  "Magento_Checkout/js/model/shipping-rate-registry",
  "Magento_Checkout/js/model/error-processor",
  "mage/storage"
], function (
  registry,
  Abstract,
  $,
  url,
  shippingService,
  quote,
  resourceUrlManager,
  rateRegistry,
  errorProcessor,
  storage
) {
  "use strict";

  return Abstract.extend({
    /**
     * Formata o CEP conforme o usuário digita e busca o endereço se completo
     */
    onUpdate() {
      this._super();
      const element = this;


      console.log('entrou');
      
      $("#" + this.uid).attr("maxlength", "9");

      let v = this.value() || "";
      v = v.replace(/\D/g, "");

      if (v.length > 5) {
        v = v.replace(/^(\d{5})(\d)/, "$1-$2");
      }

      this.value(v);

    },
  });
});

