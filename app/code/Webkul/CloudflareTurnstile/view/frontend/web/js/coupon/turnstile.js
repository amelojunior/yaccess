/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_CloudflareTurnstile
 * @author    Webkul Software Private Limited
 * @copyright Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

/* global grecaptcha */
define(
    [
        'uiComponent',
        'jquery',
        'domReady!'
    ], function (Component, $) {
        'use strict';
        window.turnstileCheckotCouponToken = '';
        
        //add validation using additional valiator
        return Component.extend({
            defaults: {
                template: 'Webkul_CloudflareTurnstile/coupon/turnstile',
                turnstileId: 'trunstile-checkout-coupon-apply'
            },

            initialize: function () {
                this._super();
            },
            /**
             * Render CloudFlare Turnstile Widget
             * @returns {String}
             */
             renderCouponTurnstileWidget: function() {
                if(this.isCaptchaEnableForCouponCode()){
                    turnstile.render('#turnstileCouponWidget', {  
                        sitekey: this.getSiteKey(),
                        callback: function(token) {    
                            window.turnstileCheckotCouponToken = token;
                        },
                        'expired-callback': function() {
                            window.turnstileCheckotCouponToken = '';
                        },
                        'error-callback': function() {
                            window.turnstileCheckotCouponToken = '';
                        }
                    });
                }
                
            },
            /**
             * @return {Boolean}
             */
            isCaptchaEnableForCouponCode: function () {
                return this.getTurnstileConfigData().isCaptchaEnableForCouponCode==1?true:false;
            },

            /**
             * Get Site Key
             * @returns {String}
             */
            getSiteKey: function () {
                return this.getTurnstileConfigData().siteKey;
            },
           

            /**
             * Get Site Key
             * @returns {Array}
             */
            getTurnstileConfigData: function () {
                return window.checkoutConfig.turnstileConfigData;
            },

            getIsInvisibleTurnstile: function(){
                if(this.getSiteKey() && this.isCaptchaEnableForCouponCode()){
                    return true;
                }
                return false;
            },

            /**
             * Get turnstileId ID
             * @returns {String}
             */
            getTurnstileId: function () {
                return this.turnstileId;
            }
        });
    });
