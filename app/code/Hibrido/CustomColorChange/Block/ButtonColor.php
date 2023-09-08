<?php
namespace Hibrido\CustomColorChange\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ButtonColor extends Template
{
    private $scopeConfig;

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
    }

    public function getButtonBackgroundColor()
    {
        return $this->scopeConfig->getValue('button_color/general/color');
    }
}