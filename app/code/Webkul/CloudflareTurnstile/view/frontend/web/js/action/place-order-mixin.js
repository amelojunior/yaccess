/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_CloudflareTurnstile
 * @author    Webkul Software Private Limited
 * @copyright Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

define([
    'mage/utils/wrapper'
], function (wrapper) {
    'use strict';

    return function (placeOrderAction) {
        return wrapper.wrap(placeOrderAction, function (originalAction, paymentData, messageContainer) {
            var isEnabled = window.checkoutConfig &&
                window.checkoutConfig.turnstileConfigData &&
                window.checkoutConfig.turnstileConfigData.isCaptchaEnableForPlaceOrder == 1;

            if (isEnabled && window.turnstileToken) {
                paymentData.additional_data = paymentData.additional_data || {};
                paymentData.additional_data.cf_turnstile_response = window.turnstileToken;
                paymentData.additional_data['cf-turnstile-response'] = window.turnstileToken;
            }

            return originalAction(paymentData, messageContainer).fail(function () {
                if (isEnabled) {
                    window.turnstileToken = '';
                    if (window.turnstile && window.turnstilePlaceOrderWidgetId !== undefined) {
                        window.turnstile.reset(window.turnstilePlaceOrderWidgetId);
                    }
                }
            });
        });
    };
});
