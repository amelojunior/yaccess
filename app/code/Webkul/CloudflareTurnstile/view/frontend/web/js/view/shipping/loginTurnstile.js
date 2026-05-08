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
        'Magento_Customer/js/customer-data',
        'domReady!'
    ], function (Component, $,customerData) {
        'use strict';
        
        return Component.extend({
            defaults: {
                template: 'Webkul_CloudflareTurnstile/view/shipping/loginTurnstile',
                turnstileId: 'turnstileShippingWidget'
            },

            initialize: function () {
                this._super();
            },
            /**
             * Render CloudFlare Turnstile Widget
             * @returns {String}
             */
            renderTurnstileWidget: function() {
                if(this.isCaptchaEnableForLogin()){
                    turnstile.render('#turnstileShippingWidget', {  
                        sitekey: this.getSiteKey(),
                        callback: function(token) {
                            window.turnstileLoginToken = token;
                        }, 
                    });
                }
                
            },
            /**
             * @return {Boolean}
             */
            isCaptchaEnableForLogin: function () {
                return this.getTurnstileConfigData().isCaptchaEnableForLogin==1?true:false;
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
                if(this.getSiteKey() && this.isCaptchaEnableForLogin()){
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
