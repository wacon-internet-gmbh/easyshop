<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension: easyshop.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace Wacon\Easyshop\Domain\Validator;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use Wacon\Easyshop\Bootstrap\Traits\ExtensionTrait;

class OrderFormValidator extends AbstractValidator
{
    use ExtensionTrait;

    /**
     * Validating orderForm
     * @param mixed $value
     */
    protected function isValid(mixed $value): void
    {
        /**
         * @var \Wacon\Easyshop\Domain\Model\OrderForm $orderForm
         */
        $orderForm = $value;

        // If order form contains one value, then all fields are required
        if (!$orderForm->isEmpty()) {
            $requiredFields = ['firstname', 'lastname', 'street', 'postcode', 'city', 'country'];

            foreach($requiredFields as $requiredField) {
                $func = 'get' . \ucfirst($requiredField);

                if (\trim($orderForm->$func()) == '') {
                    $this->addErrorForProperty(
                        $requiredField,
                        LocalizationUtility::translate('plugin.easyshop_orderform.validation.error.' . $requiredField, $this->extensionKey),
                        time()
                    );
                }
            }
        }

        if ($orderForm->getPostcode() && !\preg_match('/^\d{5}$/', $orderForm->getPostcode())) {
            $this->addErrorForProperty(
                'postcode',
                LocalizationUtility::translate('plugin.easyshop_orderform.validation.error.postcode', $this->extensionKey),
                time()
            );
        }

        if ($orderForm->getCountry() && $orderForm->getCountry() != LocalizationUtility::translate('misc.countries.germany', $this->extensionKey)) {
            $this->addErrorForProperty(
                'country',
                LocalizationUtility::translate('plugin.easyshop_orderform.validation.error.country', $this->extensionKey),
                time()
            );
        }
    }
}
