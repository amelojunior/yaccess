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

namespace Webkul\CloudflareTurnstile\Api;

interface ValidateTurnstileInterface
{

    /**
     * Get payment information
     *
     * @param string $token
     * @return array
     */
    public function getValidateTurnstile(string $token): array;
}
