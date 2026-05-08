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
namespace Webkul\CloudflareTurnstile\Block;

class Turnstile extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\View\Element\Template\Context
     */
    protected $context;
    /**
     * @var \Webkul\CloudflareTurnstile\Helper\Data
     */
    protected $helper;

    /**
     * Block template.
     *
     * @var string
     */
    protected $_template = 'turnstile.phtml';

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Webkul\CloudflareTurnstile\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Webkul\CloudflareTurnstile\Helper\Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    /**
     * Get Site Key
     *
     * @return mixed
     */
    public function getSiteKey()
    {
        return $this->helper->getSiteKey();
    }
}
