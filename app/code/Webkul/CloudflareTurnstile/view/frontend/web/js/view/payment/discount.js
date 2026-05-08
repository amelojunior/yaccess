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
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_SalesRule/js/action/set-coupon-code',
    'Magento_SalesRule/js/action/cancel-coupon',
    'Magento_SalesRule/js/model/coupon',
    'Magento_Checkout/js/model/url-builder',
    'Magento_SalesRule/js/model/payment/discount-messages',
    'mage/storage',
    'mage/translate',
    'mage/url',
    'Magento_Checkout/js/model/full-screen-loader',
    'domReady!'
], function ($, ko, Component, quote, setCouponCodeAction, cancelCouponAction, coupon,urlBuilder,messageContainer,storage,$t,mageurl,fullScreenLoader) {
    'use strict';

    var totals = quote.getTotals(),
        couponCode = coupon.getCouponCode(),
        isApplied = coupon.getIsApplied();

    if (totals()) {
        couponCode(totals()['coupon_code']);
    }
    isApplied(couponCode() != null);

    return Component.extend({
        defaults: {
            template: 'Magento_SalesRule/payment/discount'
        },
        couponCode: couponCode,

        /**
         * Applied flag
         */
        isApplied: isApplied,

        /**
         * Coupon code application procedure
         */
        apply: function () {
            if(this.validate()){
                var validateTurnstile = false;
                fullScreenLoader.startLoader();
    
                var isCaptchaEnableForCouponCode = window.checkoutConfig.turnstileConfigData.isCaptchaEnableForCouponCode;
                var turnstileCheckotCouponToken =  window.turnstileCheckotCouponToken;
                var serviceUrl = urlBuilder.createUrl('/turnstile/validateturnstile', {});
                serviceUrl = mageurl.build(serviceUrl);
                if(isCaptchaEnableForCouponCode){
                    if(turnstileCheckotCouponToken){
                        storage.post(
                            serviceUrl,
                            JSON.stringify({
                                token: turnstileCheckotCouponToken
                            }),
                            false
                        ).done(function (response) {
                            var result = typeof response === 'string' ? $.parseJSON(response) : response;
                            if (!result.success) {
                                fullScreenLoader.stopLoader();
                                messageContainer.addErrorMessage({ message: $t('Cloudflare Turnstile validation failed, please reload the page and try again') });
                                
                            }
                            validateTurnstile = result.success;
                            if(result.success){
                                fullScreenLoader.stopLoader();
                                setCouponCodeAction(couponCode(), isApplied);
                            }
                            console.log(validateTurnstile);
                        }).fail(function (response) {
                            fullScreenLoader.stopLoader();
                            var result = $.parseJSON(response);
                            messageContainer.addErrorMessage({ message: $t('Cloudflare Turnstile validation failed, please reload the page and try again') });
                            return result.success;
                        });
                    } else {
                        fullScreenLoader.stopLoader();
                        messageContainer.addErrorMessage({ message: $t('Cloudflare Turnstile validation failed, please try again') });
                        return false;
                    }
                } else {
                    fullScreenLoader.stopLoader();
                    setCouponCodeAction(couponCode(), isApplied);
                }
            }
           
            
        },
        

        /**
         * Cancel using coupon
         */
        cancel: function () {
            if (this.validate()) {
                couponCode('');
                cancelCouponAction(isApplied);
            }
        },

        /**
         * Coupon form validation
         *
         * @returns {Boolean}
         */
        validate: function () {
            var form = '#discount-form';

            return $(form).validation() && $(form).validation('isValid');
        }
    });
});
