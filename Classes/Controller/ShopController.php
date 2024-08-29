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

namespace Wacon\Easyshop\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Wacon\Easyshop\Domain\Repository\ProductRepository;
use Wacon\Easyshop\Exception\PayPalAuthException;
use Wacon\Easyshop\Exception\ProductNotFoundException;
use Wacon\Easyshop\Service\PaymentGateway\PayPalService;

class ShopController extends BaseController
{
    public function __construct(
        private readonly ProductRepository $productRepository
    ) {}

    /**
     * Show list and detail view
     * @return ResponseInterface
     */
    public function checkoutAction(): ResponseInterface
    {
        $response = [];
        $paypalService = GeneralUtility::makeInstance(PayPalService::class);
        $arguments = $this->getCartFromPostViaJSON();

        try {
            if (!is_array($arguments) || !array_key_exists('cart', $arguments)) {
                $response = [
                    'status' => 'error',
                    'code' => -1,
                    'message' => 'Cart is empty',
                ];
            }else {
                if (!$paypalService->authorize($this->settings['checkout']['paypal'])) {
                    throw new PayPalAuthException();
                }

                $productData = current($arguments['cart']);
                $product = $this->productRepository->findByUid($productData['id']);

                if (!$product) {
                    throw new ProductNotFoundException('Product with id: ' . $productData['id'] . ' could not be found.', time());
                }

                $orderDetails = [
                    'locale' => $this->request->getAttribute('language')->getLocale()->getName(),
                    'brand' => [
                        'name' => $this->settings['brand']['name'],
                    ],
                    'product' => [
                        'reference_id' => $product->getUid(),
                        'amount' => [
                            'currency_code' => $product->getCurrency(),
                            'value' => $product->getGrossPrice(),
                            'breakdown' => [
                                'item_total' => [
                                    'currency_code' => $product->getCurrency(),
                                    'value' => $product->getNetPrice(),
                                ],
                                'tax_total' => [
                                    'currency_code' => $product->getCurrency(),
                                    'value' => $product->getVatInCurrency() ,
                                ],
                            ],
                        ],
                        'items' => [
                            [
                                'name' => $product->getName(),
                                'quantity' => 1,
                                'sku' => $product->getUid(),
                                'unit_amount' => [
                                    'currency_code' => $product->getCurrency(),
                                    'value' => $product->getNetPrice(),
                                ],
                                'tax' => [
                                    'currency_code' => $product->getCurrency(),
                                    'value' => $product->getVatInCurrency(),
                                ],
                            ],
                        ],
                    ],
                ];

                $transaction = $paypalService->createOrder($orderDetails);

                // Handle the transaction result
                if ($transaction) {
                    $response = [
                        'status' => $transaction['status'],
                        'id' => $transaction['id'],
                    ];
                } else {
                    $response = $transaction;
                }
            }
        } catch(\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        }

        $this->view->assign('response', \json_encode($response));
        return $this->jsonResponse();
    }
}
