<?php
namespace Evoxlab\CheckoutLabels\Plugin;

use Magento\Checkout\Block\Checkout\LayoutProcessor;

class LayoutProcessorPlugin
{
    public function afterProcess(LayoutProcessor $subject, array $jsLayout)
    {
        /** SHIPPING ADDRESS **/
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
            ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'])) {

            $fields = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
                ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];

            $this->customizeStreetFields($fields);
            $this->addTelephoneMask($fields);
            $this->addPostcodeMask($fields);
        }

        /** BILLING ADDRESS (todos os métodos de pagamento) **/
        $paymentsList = &$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']
            ['children']['payment']['children']['payments-list']['children'] ?? [];

        foreach ($paymentsList as $paymentFormCode => &$paymentForm) {
            if (isset($paymentForm['children']['form-fields']['children'])) {
                $billingFields = &$paymentForm['children']['form-fields']['children'];
                $this->customizeStreetFields($billingFields);
                $this->addTelephoneMask($billingFields);
                $this->addPostcodeMask($billingFields);
            }
        }

        return $jsLayout;
    }

    /**
     * Altera os rótulos dos campos de endereço (street)
     */
    private function customizeStreetFields(array &$fields): void
    {
        if (empty($fields['street']['children'])) {
            return;
        }

        $labels = ['Rua', 'Número', 'Complemento', 'Bairro'];

        foreach ($fields['street']['children'] as $index => &$child) {
            if (isset($labels[$index])) {
                $child['label'] = __($labels[$index]);
                $child['placeholder'] = __($labels[$index]);
            }
        }
    }

    /**
     * Adiciona máscara e validação de telefone/fax
     */
    private function addTelephoneMask(array &$fields): void
    {
        foreach (['telephone', 'fax'] as $fieldName) {
            if (isset($fields[$fieldName])) {
                $fields[$fieldName]['component'] = 'Evoxlab_CheckoutLabels/js/telephone';
                $fields[$fieldName]['required'] = true;
                $fields[$fieldName]['validation']['required-entry'] = true;
                $fields[$fieldName]['validation']['validate-cellphone'] = true;
            }
        }
    }

    private function addPostcodeMask(array &$fields): void
    {
        if (isset($fields['postcode'])) {
            $fields['postcode']['component'] = 'Evoxlab_CheckoutLabels/js/postcode';
        }
    }    
}
