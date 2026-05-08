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

    var addTurnstileHeader = function (headers) {
        if (window.checkoutConfig &&
            window.checkoutConfig.turnstileConfigData &&
            window.checkoutConfig.turnstileConfigData.isCaptchaEnableForCouponCode == 1 &&
            window.turnstileCheckotCouponToken
        ) {
            headers['X-Cf-Turnstile-Response'] = window.turnstileCheckotCouponToken;
        }
    };

    return function (setCouponCodeAction) {
        var wrappedAction;

        if (typeof setCouponCodeAction.registerDataModifier === 'function') {
            setCouponCodeAction.registerDataModifier(addTurnstileHeader);

            return setCouponCodeAction;
        }

        wrappedAction = wrapper.wrap(setCouponCodeAction, function (originalAction, couponCode, isApplied) {
            return originalAction(couponCode, isApplied);
        });

        Object.keys(setCouponCodeAction).forEach(function (key) {
            wrappedAction[key] = setCouponCodeAction[key];
        });

        return wrappedAction;
    };
});
