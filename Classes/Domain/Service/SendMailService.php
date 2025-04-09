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
use TYPO3\CMS\Core\Utility\MailUtility;

class SendMailService
{
    /**
     * TypoScript settings
     * @var array
     */
    protected array $settings = [];

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Return a new FluidEmail instance.
     * @return FluidEmail
     */
    public function getFluidEmailInstance()
    {
        $fluidEmail = new FluidEmail();
        $fluidEmail->from($this->getSystemFromAddress());

        return $fluidEmail;
    }

    /**
     * Return system from address
     * @return Address
     */
    protected function getSystemFromAddress(): Address
    {
        $from = MailUtility::getSystemFrom();

        if (\count($from) == 2) {
            return new Address($from[0], $from[1]);
        }

        return new Address($from[0]);
    }
}
