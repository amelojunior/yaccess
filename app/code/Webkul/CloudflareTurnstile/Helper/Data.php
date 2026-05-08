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

namespace Webkul\CloudflareTurnstile\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public const TURNSTILE_URL = "https://challenges.cloudflare.com/turnstile/v0/siteverify";
    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    public $encryptor;

    /**
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    public $curl;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    public $jsonSerializer;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptor
     * @param \Magento\Framework\HTTP\Client\Curl $curl
     * @param \Magento\Framework\Serialize\Serializer\Json $jsonSerializer
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Magento\Framework\HTTP\Client\Curl $curl,
        \Magento\Framework\Serialize\Serializer\Json $jsonSerializer
    ) {
        $this->encryptor = $encryptor;
        $this->curl = $curl;
        $this->jsonSerializer = $jsonSerializer;
        parent::__construct($context);
    }
    /**
     * Get Site Key
     *
     * @return mixed
     */
    public function getSiteKey()
    {
        return $this->encryptor->decrypt($this->scopeConfig->getValue(
            'cloudflareturnstile/general/site_key',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        ));
    }

    /**
     * Get Secret Key
     *
     * @return mixed
     */
    public function getSecretKey()
    {
        return $this->encryptor->decrypt($this->scopeConfig->getValue(
            'cloudflareturnstile/general/secret_key',
            ScopeInterface::SCOPE_STORE
        ));
    }

    /**
     * Is Captcha enable in login
     *
     * @return boolean
     */
    public function isCaptchaEnableForLogin()
    {
        return $this->scopeConfig->getValue(
            'cloudflareturnstile/type_for/customer_login',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is Captcha enable for forgot password
     *
     * @return boolean
     */
    public function isCaptchaEnableForForgotPass()
    {
        return $this->scopeConfig->getValue(
            'cloudflareturnstile/type_for/customer_forgot_password',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is Captcha enable for sign up
     *
     * @return boolean
     */
    public function isCaptchaEnableForSignUp()
    {
        return $this->scopeConfig->getValue(
            'cloudflareturnstile/type_for/customer_create',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is Captcha enable for customer edit
     *
     * @return boolean
     */
    public function isCaptchaEnableForCustomerEdit()
    {
        return $this->scopeConfig->getValue(
            'cloudflareturnstile/type_for/customer_edit',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is Captcha enable for contact us
     *
     * @return boolean
     */
    public function isCaptchaEnableForContactUs()
    {
        return $this->scopeConfig->getValue(
            'cloudflareturnstile/type_for/contact',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is Captcha enable for product review
     *
     * @return boolean
     */
    public function isCaptchaEnableForProductReview()
    {
        return $this->scopeConfig->getValue(
            'cloudflareturnstile/type_for/product_review',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is Captcha enable for place order
     *
     * @return boolean
     */
    public function isCaptchaEnableForPlaceOrder()
    {
        return $this->scopeConfig->getValue(
            'cloudflareturnstile/type_for/place_order',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is Captcha enable for coupon code
     *
     * @return boolean
     */
    public function isCaptchaEnableForCouponCode()
    {
        return $this->scopeConfig->getValue(
            'cloudflareturnstile/type_for/coupon_code',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get TurnstileResponse
     *
     * @param string $responseKey
     * @return array
     */
    public function getTurnstileResponse($responseKey)
    {
        if (!$responseKey) {
            return ['success' => false];
        }

        $serverKey = $this->getSecretKey();
        if (!$serverKey) {
            return ['success' => false];
        }

        $params = [
            'secret' => $serverKey,
            'response' => $responseKey
        ];

        try {
            $this->curl->setTimeout(5);
            $this->curl->post(self::TURNSTILE_URL, $params);
            $response = $this->curl->getBody();
            $decodeResponse = $this->jsonSerializer->unserialize($response);
        } catch (\Throwable $exception) {
            return ['success' => false];
        }

        if (!is_array($decodeResponse) || !isset($decodeResponse['success'])) {
            return ['success' => false];
        }

        return $decodeResponse;
    }
}
