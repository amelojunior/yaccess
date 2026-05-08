/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_CloudflareTurnstile
 * @author    Webkul Software Private Limited
 * @copyright Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

define([], function () {
    'use strict';

    return function (setCouponCodeAction) {
        setCouponCodeAction.registerDataModifier(function (headers) {
            if (window.checkoutConfig &&
                window.checkoutConfig.turnstileConfigData &&
                window.checkoutConfig.turnstileConfigData.isCaptchaEnableForCouponCode == 1 &&
                window.turnstileCheckotCouponToken
            ) {
                headers['X-Cf-Turnstile-Response'] = window.turnstileCheckotCouponToken;
            }
        });

        return setCouponCodeAction;
    };
});
