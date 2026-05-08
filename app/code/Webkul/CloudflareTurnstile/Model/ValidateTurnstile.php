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

use Webkul\CloudflareTurnstile\Api\ValidateTurnstileInterface;

class ValidateTurnstile implements ValidateTurnstileInterface
{

    /**
     * @var \Webkul\CloudflareTurnstile\Helper\Data
     */
    public $helper;

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
     * Get Validate Turnstile
     *
     * @param string $token
     * @return array
     */
    public function getValidateTurnstile(string $token): array
    {
        $response = $this->helper->getTurnstileResponse($token);

        return ['success' => !empty($response['success'])];
    }
}
