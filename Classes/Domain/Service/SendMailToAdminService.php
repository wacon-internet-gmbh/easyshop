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

namespace Wacon\Easyshop\Domain\Service;

use Symfony\Component\Mime\Address;
use TYPO3\CMS\Core\Mail\FluidEmail;
use TYPO3\CMS\Core\Mail\MailerInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Wacon\Easyshop\Bootstrap\Traits\ExtensionTrait;
use Wacon\Easyshop\Service\PaymentGateway\PayPalService;

class SendMailToAdminService extends SendMailService
{
    use ExtensionTrait;

    /**
     * Send mail to user
     * @param array $orderDetails
     * @param array $orderForm
     */
    public function send(array $orderDetails, array $orderForm): void
    {
        $fluidEmail = $this->getFluidEmailInstance();
        $fluidEmail
            ->to(new Address($this->settings['emails']['orderConfirmation']))
            ->from($this->getSystemFromAddress())
            ->subject(LocalizationUtility::translate('plugin.easyshop_checkout.email.touser.subject', $this->extensionKey, [$orderDetails['id']]))
            ->format(FluidEmail::FORMAT_BOTH);

        if ($orderDetails['status'] == PayPalService::ORDER_STATUS_COMPLETED) {
            $fluidEmail
                ->setTemplate('CheckoutToAdmin');
        } else {
            $fluidEmail
                ->setTemplate('CheckoutErrorToAdmin');
        }

        $fluidEmail
            ->assign('orderDetails', $orderDetails)
            ->assign('orderForm', $orderForm)
            ->assign('contactEmail', $this->settings['emails']['orderContact']);

        GeneralUtility::makeInstance(MailerInterface::class)->send($fluidEmail);
    }
}
