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

    return function (loginAction) {
        var wrappedAction = wrapper.wrap(loginAction, function (originalAction, loginData, redirectUrl, isGlobal, messageContainer) {
            if (window.checkoutConfig &&
                window.checkoutConfig.turnstileConfigData &&
                window.checkoutConfig.turnstileConfigData.isCaptchaEnableForLogin == 1 &&
                window.turnstileLoginToken
            ) {
                loginData['cf-turnstile-response'] = window.turnstileLoginToken;
            }

            return originalAction(loginData, redirectUrl, isGlobal, messageContainer);
        });

        Object.keys(loginAction).forEach(function (key) {
            wrappedAction[key] = loginAction[key];
        });

        return wrappedAction;
    };
});
