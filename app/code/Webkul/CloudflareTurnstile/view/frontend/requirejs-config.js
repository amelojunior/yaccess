/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_CloudflareTurnstile
 * @author    Webkul Software Private Limited
 * @copyright Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/place-order': {
                'Webkul_CloudflareTurnstile/js/action/place-order-mixin': true
            },
            'Magento_SalesRule/js/action/set-coupon-code': {
                'Webkul_CloudflareTurnstile/js/action/set-coupon-code-mixin': true
            },
            'Magento_Customer/js/action/login': {
                'Webkul_CloudflareTurnstile/js/action/login-mixin': true
            }
        }
    }
};
