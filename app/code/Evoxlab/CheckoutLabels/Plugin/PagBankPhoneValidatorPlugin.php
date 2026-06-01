<?php
namespace Evoxlab\CheckoutLabels\Plugin;

use Magento\Framework\Exception\LocalizedException;
use PagBank\PaymentMagento\Gateway\Request\CustomerDataRequest;

class PagBankPhoneValidatorPlugin
{
    public function afterStructurePhone(CustomerDataRequest $subject, array $result): array
    {
        $number = (int) ($result['number'] ?? 0);

        if ($number < 10000000 || $number > 999999999) {
            throw new LocalizedException(
                __('O número de telefone informado é inválido. Informe um número com DDD válido (ex: 11 99999-9999).')
            );
        }

        return $result;
    }
}
