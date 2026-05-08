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

namespace Webkul\CloudflareTurnstile\ViewModel;

class Turnstile implements \Magento\Framework\View\Element\Block\ArgumentInterface
{

    /**
     * @var \Webkul\CloudflareTurnstile\Helper\Data
     */
    protected $helperData;

    /**
     * Constructor
     *
     * @param \Webkul\CloudflareTurnstile\Helper\Data $helperData
     */
    public function __construct(
        \Webkul\CloudflareTurnstile\Helper\Data $helperData
    ) {
        $this->helperData = $helperData;
    }

    /**
     * Get Site Key
     *
     * @return mixed
     */
    public function getSiteKey()
    {
        return $this->helperData->getSiteKey();
    }

    /**
     * Get Site Key
     *
     * @return mixed
     */
    public function isCaptchaEnableForCouponCode()
    {
        return $this->helperData->isCaptchaEnableForCouponCode();
    }
}
