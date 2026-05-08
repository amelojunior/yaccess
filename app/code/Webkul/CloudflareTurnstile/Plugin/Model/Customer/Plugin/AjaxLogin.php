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

namespace Webkul\CloudflareTurnstile\Plugin\Model\Customer\Plugin;

use Webkul\CloudflareTurnstile\Helper\Data as TurnstileHelper;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Webkul\CloudflareTurnstile\Model\TurnstileValidator;

/**
 * Around plugin for login action.
 */
class AjaxLogin
{
    /**
     * @var \Webkul\CloudflareTurnstile\Helper\Data
     */
    protected $helper;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $serializer;

    /**
     * @var TurnstileValidator
     */
    private $turnstileValidator;

    /**
     * Constructor
     *
     * @param TurnstileHelper $helper
     * @param JsonFactory $resultJsonFactory
     * @param \Magento\Framework\Serialize\Serializer\Json $serializer
     * @param TurnstileValidator $turnstileValidator
     */
    public function __construct(
        TurnstileHelper $helper,
        JsonFactory $resultJsonFactory,
        \Magento\Framework\Serialize\Serializer\Json $serializer,
        TurnstileValidator $turnstileValidator
    ) {
        $this->helper = $helper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->serializer = $serializer;
        $this->turnstileValidator = $turnstileValidator;
    }

    /**
     * Check captcha data on login action.
     *
     * @param \Magento\Customer\Controller\Ajax\Login $subject
     * @param \Closure $proceed
     * @return $this
     */
    public function aroundExecute(
        \Magento\Customer\Controller\Ajax\Login $subject,
        \Closure $proceed
    ) {
        /** @var \Magento\Framework\App\RequestInterface $request */
        $request = $subject->getRequest();
        $loginParams = [];
        $content = $request->getContent();
        if ($content) {
            try {
                $loginParams = $this->serializer->unserialize($content);
            } catch (\InvalidArgumentException $exception) {
                return $this->returnJsonError(__('Invalid login request.'));
            }
        }
        if (!$this->helper->isCaptchaEnableForLogin()) {
            return $proceed();
        }

        $token = $loginParams[TurnstileValidator::PARAM_NAME] ?? null;
        try {
            $this->turnstileValidator->validate(is_string($token) ? $token : null);
        } catch (LocalizedException $exception) {
            return $this->returnJsonError(__($exception->getMessage()));
        }

        return $proceed();
    }

    /**
     * Format JSON response.
     *
     * @param \Magento\Framework\Phrase $phrase
     * @return \Magento\Framework\Controller\Result\Json
     */
    private function returnJsonError(\Magento\Framework\Phrase $phrase): \Magento\Framework\Controller\Result\Json
    {
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData(['errors' => true, 'message' => $phrase]);
    }
}
