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

namespace Webkul\CloudflareTurnstile\Observer;

use Magento\Framework\Event\ObserverInterface;
use Webkul\CloudflareTurnstile\Model\TurnstileValidator;

/**
 * Class CheckForgotpasswordObserver
 */
class PredispatchObserver implements ObserverInterface
{
    /**
     * @var \Webkul\CloudflareTurnstile\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $redirect;

    /**
     * @var \Magento\Framework\App\ActionFlag
     */
    protected $_actionFlag;

    /**
     * @var TurnstileValidator
     */
    private $turnstileValidator;

    /**
     * Constructor
     *
     * @param \Webkul\CloudflareTurnstile\Helper\Data $helper
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\App\Response\RedirectInterface $redirect
     * @param \Magento\Framework\App\ActionFlag $actionFlag
     * @param TurnstileValidator $turnstileValidator
     */
    public function __construct(
        \Webkul\CloudflareTurnstile\Helper\Data $helper,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Framework\App\ActionFlag $actionFlag,
        TurnstileValidator $turnstileValidator
    ) {
        $this->helper = $helper;
        $this->messageManager = $messageManager;
        $this->request = $request;
        $this->redirect = $redirect;
        $this->_actionFlag = $actionFlag;
        $this->turnstileValidator = $turnstileValidator;
    }

    /**
     * Check Captcha On Forgot Password Page
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $isRemove = $observer->getRequest()->getParam('remove');
        if ($this->request->isPost()
            && ($this->request->getActionName() !== 'loginPost')
            && ($isRemove == null || $isRemove == 0)
            && $this->shouldValidateRequest()
        ) {
            $controller = $observer->getControllerAction();
            try {
                $this->turnstileValidator->validateRequest();
            } catch (\Magento\Framework\Exception\LocalizedException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
                $this->_actionFlag->set('', \Magento\Framework\App\Action\Action::FLAG_NO_DISPATCH, true);
                $this->redirect->redirect($controller->getResponse(), $this->redirect->getRefererUrl());
            }
        }
    }

    private function shouldValidateRequest()
    {
        switch (strtolower($this->request->getFullActionName())) {
            case 'customer_account_forgotpasswordpost':
                return $this->helper->isCaptchaEnableForForgotPass();
            case 'customer_account_createpost':
                return $this->helper->isCaptchaEnableForSignUp();
            case 'customer_account_editpost':
                return $this->helper->isCaptchaEnableForCustomerEdit();
            case 'contact_index_post':
                return $this->helper->isCaptchaEnableForContactUs();
            case 'review_product_post':
                return $this->helper->isCaptchaEnableForProductReview();
            case 'checkout_cart_couponpost':
                return $this->helper->isCaptchaEnableForCouponCode();
        }

        return (bool) $this->turnstileValidator->getTokenFromRequest();
    }
}
