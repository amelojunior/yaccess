/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_CloudflareTurnstile
 * @author    Webkul Software Private Limited
 * @copyright Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

define(
    [
        'jquery',
        'mage/translate',
        'Magento_Ui/js/model/messageList',
        'Magento_Checkout/js/model/url-builder',
        'mage/storage',
        'mage/url'
    ],
    function ($,$t, messageList,urlBuilder,storage,mageurl) {
        'use strict';
	        return {
	            validate: function () {
	                var turnstileToken =  window.turnstileToken;
	                if(window.isTurnstilePlaceOrderEnable ){
	                    if(!turnstileToken){
	                        messageList.addErrorMessage({ message: $t('Cloudflare Turnstile validation failed, please try again') });
	                        return false;
                    }
                }

                return true;
            }
        }
    }
);
