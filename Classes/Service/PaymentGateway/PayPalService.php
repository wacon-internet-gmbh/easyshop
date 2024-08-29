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

namespace Wacon\Easyshop\Service\PaymentGateway;

use Braintree\Gateway;

class PayPalService
{
    /**
     * Settings
     * @var array
     */
    protected array $settings;

    /**
     * Braintree\Gateway
     * @var Gateway
     */
    private Gateway $gateway;

    /**
     * Client token of current session
     * @var string
     */
    private string $clientToken;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Authorize into paypal
     * @return void
     * @throws \InvalidArgumentException
     */
    public function authorize()
    {
        $this->gateway = new Braintree\Gateway([
            'environment' => $this->settings['environment'],
            'merchantId' => $this->settings['merchantId'],
            'publicKey' => $this->settings['publicKey'],
            'privateKey' => $this->settings['privateKey'],
        ]);

        // pass $clientToken to your front-end
        $this->clientToken = $this->gateway->clientToken()->generate([
            "customerId" => $this->settings['customerId'],
        ]);
    }
}

