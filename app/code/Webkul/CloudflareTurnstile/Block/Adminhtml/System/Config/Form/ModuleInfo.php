<?php

/**
 * Webkul_CloudflareTurnstile IncrementalPriceRange CloudflareTurnstile Form Block.
 * @category    Webkul
 * @package     Webkul_CloudflareTurnstile
 * @author      Webkul Software Private Limited
 * @copyright   Webkul Software Private Limited (https://webkul.com)
 * @license     https://store.webkul.com/license.html
 */

namespace Webkul\CloudflareTurnstile\Block\Adminhtml\System\Config\Form;

use Magento\Config\Block\System\Config\Form\Field as FormField;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Module\PackageInfoFactory;

class ModuleInfo extends FormField
{
    /**
     * @var string $_template
     */
    protected $_template = 'system/config/moduleinfo.phtml';

    public const MODULE_NAME = 'Webkul_CloudflareTurnstile';

    /**
     * @var Magento\Framework\Module\PackageInfoFactory
     */
    private $packageInfo;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param PackageInfoFactory                    $packageInfo
     * @param array                                 $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        PackageInfoFactory $packageInfo,
        array  $data = []
    ) {
        $this->packageInfo = $packageInfo;
        parent::__construct($context, $data);
    }

    /**
     * Set template to itself.
     *
     * @return $this
     */

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate($this->_template);
        }
        return $this;
    }

    /**
     * Render button.
     *
     * @param AbstractElement $element
     * @return string
     */

    public function render(AbstractElement $element)
    {
        // Remove scope label
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }
    /**
     * Get Module Version
     */
    public function getModuleVeriosn()
    {
        $packageInfo = $this->packageInfo->create();
        $version = $packageInfo->getVersion(self::MODULE_NAME);
       
        return $version;
    }

    /**
     * Get the button and scripts contents.
     *
     * @param AbstractElement $element
     * @return string
     */
    
    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->_toHtml();
    }
}
