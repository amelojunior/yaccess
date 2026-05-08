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
        'Magento_Checkout/js/model/payment/additional-validators',
        'Webkul_CloudflareTurnstile/js/webapiTurnstileValidator',
    ], function (Component, $,additionalValidators,webapiTurnstileValidator) {
        'use strict';
        window.turnstileToken = '';
        window.isTurnstilePlaceOrderEnable = false;
        
        //add validation using additional valiator
        additionalValidators.registerValidator(webapiTurnstileValidator);
        return Component.extend({
            defaults: {
                template: 'Webkul_CloudflareTurnstile/turnstile',
                turnstileId: 'turnstileId'
            },

            initialize: function () {
                this._super();
            },
            /**
             * Render CloudFlare Turnstile Widget
             * @returns {String}
             */
            renderTurnstileWidget: function() {
                window.isTurnstilePlaceOrderEnable = this.isCaptchaEnableForPlaceOrder();
                if(this.isCaptchaEnableForPlaceOrder()){
                    window.turnstilePlaceOrderWidgetId = turnstile.render('#turnstileWidget', {  
                        sitekey: this.getSiteKey(),
                        callback: function(token) {    
                            window.turnstileToken = token;
                        },
                        'expired-callback': function() {
                            window.turnstileToken = '';
                        },
                        'error-callback': function() {
                            window.turnstileToken = '';
                        }
                    });
                }
                
            },
            /**
             * @return {Boolean}
             */
            isCaptchaEnableForPlaceOrder: function () {
                return this.getTurnstileConfigData().isCaptchaEnableForPlaceOrder==1?true:false;
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
                if(this.getSiteKey() && this.isCaptchaEnableForPlaceOrder()){
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
