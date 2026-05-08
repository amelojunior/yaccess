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

use Magento\Framework\UrlInterface;
use Magento\Framework\Exception\LocalizedException;
use Webkul\CloudflareTurnstile\Model\TurnstileValidator;

class LoginPost
{
    /**
     * @var \Webkul\CloudflareTurnstile\Helper\Data
     */
    public $helper;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    public $messageManager;
    /**
     * @var UrlInterface
     */
    public $urlInterface;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    public $request;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    public $resultRedirectFactory;

    /**
     * @var TurnstileValidator
     */
    private $turnstileValidator;
    
    /**
     * Constructor
     *
     * @param \Webkul\CloudflareTurnstile\Helper\Data $helper
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param UrlInterface $urlInterface
     * @param \Magento\Framework\Controller\Result\RedirectFactory $resultRedirect
     * @param \Magento\Framework\App\RequestInterface $request
     * @param TurnstileValidator $turnstileValidator
     */
    public function __construct(
        \Webkul\CloudflareTurnstile\Helper\Data $helper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        UrlInterface $urlInterface,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirect,
        \Magento\Framework\App\RequestInterface $request,
        TurnstileValidator $turnstileValidator
    ) {
        $this->helper = $helper;
        $this->messageManager = $messageManager;
        $this->urlInterface = $urlInterface;
        $this->request = $request;
        $this->resultRedirectFactory =  $resultRedirect;
        $this->turnstileValidator = $turnstileValidator;
    }

    /**
     * Before Authenticate
     *
     * @param \Magento\Customer\Model\AccountManagement $accountManagement
     * @param string $username
     * @param string $password
     * @return array
     * @throws LocalizedException
     */
    public function beforeAuthenticate(
        \Magento\Customer\Model\AccountManagement $accountManagement,
        $username,
        $password
    ) {
        if ($this->helper->isCaptchaEnableForLogin()
            && !$this->request->isXmlHttpRequest()
            && $this->request->getActionName() === 'loginPost'
        ) {
            $this->turnstileValidator->validateRequest();
        }

        return [$username, $password];
    }
}
