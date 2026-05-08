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

namespace Webkul\CloudflareTurnstile\Plugin\Quote;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Webkul\CloudflareTurnstile\Helper\Data;
use Webkul\CloudflareTurnstile\Model\TurnstileValidator;

class CouponManagementPlugin
{
    /**
     * @var Data
     */
    private $helper;

    /**
     * @var TurnstileValidator
     */
    private $turnstileValidator;

    /**
     * @var State
     */
    private $appState;

    /**
     * @param Data $helper
     * @param TurnstileValidator $turnstileValidator
     * @param State $appState
     */
    public function __construct(
        Data $helper,
        TurnstileValidator $turnstileValidator,
        State $appState
    ) {
        $this->helper = $helper;
        $this->turnstileValidator = $turnstileValidator;
        $this->appState = $appState;
    }

    /**
     * @param \Magento\Quote\Model\CouponManagement $subject
     * @param mixed $cartId
     * @param string $couponCode
     * @return array
     */
    public function beforeSet(
        \Magento\Quote\Model\CouponManagement $subject,
        $cartId,
        $couponCode
    ): array {
        if ($this->helper->isCaptchaEnableForCouponCode() && !$this->isAdminArea()) {
            $this->turnstileValidator->validateRequest();
        }

        return [$cartId, $couponCode];
    }

    private function isAdminArea()
    {
        try {
            return $this->appState->getAreaCode() === Area::AREA_ADMINHTML;
        } catch (\Magento\Framework\Exception\LocalizedException $exception) {
            return false;
        }
    }
}
