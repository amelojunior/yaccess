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

namespace Webkul\CloudflareTurnstile\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\Data\PaymentInterface;
use Webkul\CloudflareTurnstile\Helper\Data;

class TurnstileValidator
{
    public const PARAM_NAME = 'cf-turnstile-response';
    public const ADDITIONAL_DATA_KEY = 'cf_turnstile_response';
    public const HEADER_NAME = 'X-Cf-Turnstile-Response';

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param Data $helper
     * @param RequestInterface $request
     */
    public function __construct(
        Data $helper,
        RequestInterface $request
    ) {
        $this->helper = $helper;
        $this->request = $request;
    }

    /**
     * @param string|null $token
     * @throws LocalizedException
     */
    public function validate(?string $token): void
    {
        if (!$token) {
            throw new LocalizedException(__('Cloudflare Turnstile validation is required.'));
        }

        $response = $this->helper->getTurnstileResponse($token);
        if (empty($response['success'])) {
            throw new LocalizedException(__('Cloudflare Turnstile validation failed, please try again.'));
        }
    }

    /**
     * @throws LocalizedException
     */
    public function validateRequest(): void
    {
        $this->validate($this->getTokenFromRequest());
    }

    /**
     * @param PaymentInterface $paymentMethod
     * @throws LocalizedException
     */
    public function validatePayment(PaymentInterface $paymentMethod): void
    {
        $this->validate($this->getTokenFromPayment($paymentMethod));
    }

    public function getTokenFromRequest(): ?string
    {
        $token = $this->request->getParam(self::PARAM_NAME);
        if (!$token) {
            $token = $this->request->getHeader(self::HEADER_NAME);
        }

        return is_string($token) && $token !== '' ? $token : null;
    }

    private function getTokenFromPayment(PaymentInterface $paymentMethod): ?string
    {
        $additionalData = $paymentMethod->getAdditionalData();
        if (!is_array($additionalData)) {
            return null;
        }

        $token = $additionalData[self::ADDITIONAL_DATA_KEY]
            ?? $additionalData[self::PARAM_NAME]
            ?? null;

        return is_string($token) && $token !== '' ? $token : null;
    }
}
