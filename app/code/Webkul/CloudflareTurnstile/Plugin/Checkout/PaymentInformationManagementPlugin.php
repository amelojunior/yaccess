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

namespace Webkul\CloudflareTurnstile\Plugin\Checkout;

use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Api\Data\PaymentInterface;
use Webkul\CloudflareTurnstile\Helper\Data;
use Webkul\CloudflareTurnstile\Model\TurnstileValidator;

class PaymentInformationManagementPlugin
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
     * @param Data $helper
     * @param TurnstileValidator $turnstileValidator
     */
    public function __construct(
        Data $helper,
        TurnstileValidator $turnstileValidator
    ) {
        $this->helper = $helper;
        $this->turnstileValidator = $turnstileValidator;
    }

    /**
     * @param \Magento\Checkout\Model\PaymentInformationManagement $subject
     * @param mixed $cartId
     * @param PaymentInterface $paymentMethod
     * @param AddressInterface|null $billingAddress
     * @return array
     */
    public function beforeSavePaymentInformationAndPlaceOrder(
        \Magento\Checkout\Model\PaymentInformationManagement $subject,
        $cartId,
        PaymentInterface $paymentMethod,
        AddressInterface $billingAddress = null
    ): array {
        if ($this->helper->isCaptchaEnableForPlaceOrder()) {
            $this->turnstileValidator->validatePayment($paymentMethod);
        }

        return [$cartId, $paymentMethod, $billingAddress];
    }
}
