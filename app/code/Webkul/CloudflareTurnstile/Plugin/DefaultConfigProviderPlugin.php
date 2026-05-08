<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_CloudflareTurnstile
 * @author    Webkul Software Private Limited
 * @copyright Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\CloudflareTurnstile\Plugin;

class DefaultConfigProviderPlugin
{

    /**
     * @var \Webkul\CloudflareTurnstile\Helper\Data
     */
    private $helper;

    /**
     * Constructor
     *
     * @param \Webkul\CloudflareTurnstile\Helper\Data $helper
     */
    public function __construct(
        \Webkul\CloudflareTurnstile\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }
    /**
     * After Add config
     *
     * @param \Magento\Checkout\Model\DefaultConfigProvider $subject
     * @param array $result
     * @return array
     */
    public function afterGetConfig(
        \Magento\Checkout\Model\DefaultConfigProvider $subject,
        $result
    ) {
        $siteKey = $this->helper->getSiteKey();
        $isCaptchaEnableForPlaceOrder = $this->helper->isCaptchaEnableForPlaceOrder();
        $isCaptchaEnableForCouponCode = $this->helper->isCaptchaEnableForCouponCode();
        $isCaptchaEnableForLogin = $this->helper->isCaptchaEnableForLogin();
        $configData = ['siteKey'=> $siteKey,
         'isCaptchaEnableForPlaceOrder' => $isCaptchaEnableForPlaceOrder,
         'isCaptchaEnableForLogin'=>$isCaptchaEnableForLogin,
         'isCaptchaEnableForCouponCode'=>$isCaptchaEnableForCouponCode];
        $result['turnstileConfigData'] = $configData;
        return $result;
    }
}
