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

use GuzzleHttp\RequestOptions;
use GuzzleHttp\Exception\ClientException;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Http\RequestFactory;

class PayPalService
{
    /**
     * Settings
     * @var array
     */
    protected array $settings;

    /**
     * Client token of current session
     * @var string
     */
    private string $clientToken;

    public function __construct(
        protected RequestFactory $requestFactory,
        private readonly Context $context
    ) {}

    /**
     * Authorize into paypal
     * @param array $settings
     * @throws \InvalidArgumentException
     */
    public function authorize(array $settings)
    {
        $this->settings = $settings;
        $endpoint = $this->getPayPalAPIDomain();
        $username = $this->settings['clientid'];
        $password = $this->settings['secret'];

        $response = $this->requestFactory->request(
            $endpoint . '/v1/oauth2/token',
            'POST',
            [
                'auth' => [
                    $username, $password
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                ]
            ]
        );

        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            throw new \RuntimeException(
                'Returned status code is ' . $response->getStatusCode()
            );
        }

        if (preg_match('/^application\/json/', $response->getHeaderLine('Content-Type')) !== 1) {
            throw new \RuntimeException(
                'The request did not return JSON data'
            );
        }


        try {
            $this->accessToken = \json_decode($response->getBody()->getContents(), true, JSON_THROW_ON_ERROR);
        }catch(\Exception $e) {
            throw new \RuntimeException('The service returned an unexpected format.', 1666413230);
        }

        return !empty($this->accessToken) && is_array($this->accessToken);
    }

    /**
     * Create order via /v2/checkout/orders
     * @param array $orderDetails
     * @return \stdClass
     */
    public function createOrder(array $orderDetails)
    {
        $result = false;
        $endpoint = $this->getPayPalAPIDomain();

        $data = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                $orderDetails['product']
            ],
            'payment_source' => [
                'paypal' => [
                    'experience_context' => [
                        'payment_method_preference' => 'IMMEDIATE_PAYMENT_REQUIRED',
                        'brand_name' => $orderDetails['brand']['name'],
                        'locale' => $orderDetails['locale'],
                        'landing_page' => 'LOGIN',
                        'user_action' => 'PAY_NOW'
                    ]
                ]
            ]
        ];

        if (array_key_exists('return_url', $orderDetails)) {
            $data['payment_source']['paypal']['return_url'] = $orderDetails['return_url'];
        }

        if (array_key_exists('cancel_url', $orderDetails)) {
            $data['payment_source']['paypal']['cancel_url'] = $orderDetails['cancel_url'];
        }

        try {
            $response = $this->requestFactory->request(
                $endpoint . '/v2/checkout/orders',
                'POST',
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => 'bearer ' . $this->accessToken['access_token']
                    ],
                    RequestOptions::JSON => $data
                ]
            );
        }catch(ClientException $e) {
            throw new ClientException($e->getResponse()->getBody()->getContents(), $e->getRequest(), $e->getResponse(), $e);
        }

        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            throw new \RuntimeException(
                'Returned status code is ' . $response->getStatusCode()
            );
        }

        if (preg_match('/^application\/json/', $response->getHeaderLine('Content-Type')) !== 1) {
            throw new \RuntimeException(
                'The request did not return JSON data'
            );
        }


        try {
            $result = \json_decode($response->getBody()->getContents(), true, JSON_THROW_ON_ERROR);
        }catch(\Exception $e) {
            throw new \RuntimeException('The service returned an unexpected format.', 1666413230);
        }

        return $result;
    }

    /**
     * Deliver the current paypal api domain
     * @return string
     */
    protected function getPayPalAPIDomain(): string
    {
        $endpoint = 'https://api.sandbox.paypal.com';

        if ($this->settings['environment'] != 'sandbox') {
            $endpoint = 'https://api.paypal.com';
        }

        return $endpoint;
    }
}
