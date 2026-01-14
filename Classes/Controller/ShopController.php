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
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Wacon\Easyshop\Bootstrap\Traits\ExtensionTrait;
use Wacon\Easyshop\Domain\Model\OrderForm;
use Wacon\Easyshop\Domain\Model\Product;
use Wacon\Easyshop\Domain\Repository\ProductRepository;
use Wacon\Easyshop\Domain\Service\SendMailToAdminService;
use Wacon\Easyshop\Domain\Service\SendMailToUserService;
use Wacon\Easyshop\Domain\Validator\OrderFormValidator;
use Wacon\Easyshop\Exception\PayPalAuthException;
use Wacon\Easyshop\Exception\ProductNotFoundException;
use Wacon\Easyshop\Service\PaymentGateway\PayPalService;
use Wacon\Easyshop\Utility\FrontendSessionUtility;

class ShopController extends BaseController
{
    use ExtensionTrait;

    public function __construct(
        private readonly ProductRepository $productRepository
    ) {}

    /**
     * Action when paypal payment is done or aborted
     * @return \Psr\Http\Message\ResponseInterface
     * @throws PayPalAuthException
     */
    public function checkoutAction(): ResponseInterface
    {
        $mode = $this->request->getArgument('mode');
        $transaction = FrontendSessionUtility::getSessionData($this->request, self::class . '->orderAction');
        $orderForm = FrontendSessionUtility::getSessionData($this->request, self::class . '->orderFormCheckoutAction');

        $paypalService = GeneralUtility::makeInstance(PayPalService::class);

        if (!$paypalService->authorize($this->settings['gateways']['paypal'])) {
            throw new PayPalAuthException();
        }

        $orderDetails = $paypalService->getOrderDetails($transaction['id']);

        // Send mail to user
        if ($orderDetails['status'] == PayPalService::ORDER_STATUS_COMPLETED) {
            $sendMailToUserService = GeneralUtility::makeInstance(SendMailToUserService::class, $this->settings);
            $sendMailToUserService->send($orderDetails, $orderForm);
        }

        // Send mail to shop owner
        $sendMailToAdminService = GeneralUtility::makeInstance(SendMailToAdminService::class, $this->settings);
        $sendMailToAdminService->send($orderDetails, $orderForm);

        // Delete session
        FrontendSessionUtility::removeSessionData($this->request, self::class . '->orderAction');
        FrontendSessionUtility::removeSessionData($this->request, self::class . '->orderFormCheckoutAction');

        $this->view->assign('status', $orderDetails['status']);
        return $this->htmlResponse();
    }

    /**
     * Show list and detail view
     * @return ResponseInterface
     * @throws PayPalAuthException
     */
    public function orderAction(): ResponseInterface
    {
        $response = [];
        $paypalService = GeneralUtility::makeInstance(PayPalService::class);
        $arguments = $this->getCartFromSession($this->request);

        try {
            if (!is_array($arguments) || !array_key_exists('cart', $arguments)) {
                $response = [
                    'status' => 'error',
                    'code' => -1,
                    'message' => 'Cart is empty',
                ];
            } else {
                if (!$paypalService->authorize($this->settings['gateways']['paypal'])) {
                    throw new PayPalAuthException();
                }

                $orderForm = current($arguments['cart']);
                $product = $this->productRepository->findByUid((int)$orderForm['product']);

                if (!$product) {
                    throw new ProductNotFoundException('Product with id: ' . $orderForm['id'] . ' could not be found.', time());
                }

                $orderDetails = [
                    'locale' => $this->request->getAttribute('language')->getLocale()->getName(),
                    'brand' => [
                        'name' => $this->settings['brand']['name'],
                    ],
                    'product' => [
                        'reference_id' => $product->getUid() . '-' . time(),
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

                FrontendSessionUtility::storeSessionData($this->request, self::class . '->' . __FUNCTION__, $transaction);
            }
        } catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        }

        $this->view->assign('response', \json_encode($response));
        return $this->jsonResponse();
    }

    /**
     * Show order form
     * @param \Wacon\Easyshop\Domain\Model\Product $product
     * @param \Wacon\Easyshop\Domain\Model\OrderForm $orderForm
     * @return ResponseInterface
     */
    public function orderFormAction(Product $product, OrderForm $orderForm = null): ResponseInterface
    {
        if (!$orderForm) {
            $orderForm = GeneralUtility::makeInstance(OrderForm::class);
            $orderForm->setProduct($product);
            $orderForm->setCountry(LocalizationUtility::translate('misc.countries.germany', $this->extensionKey));
        }

        $this->view->assign('product', $product);
        $this->view->assign('orderForm', $orderForm);

        return $this->htmlResponse();
    }

    /**
     * Show overview of the order form input
     * @param \Wacon\Easyshop\Domain\Model\OrderForm $orderForm
     * @return ResponseInterface
     */
    #[Validate([
        'param' => 'orderForm',
        'validator' => OrderFormValidator::class,
    ])]
    public function orderFormOverviewAction(OrderForm $orderForm): ResponseInterface
    {
        $this->view->assign('orderForm', $orderForm);
        return $this->htmlResponse();
    }

    /**
     * Show overview of the order form input
     * @param \Wacon\Easyshop\Domain\Model\OrderForm $orderForm)
     * @return ResponseInterface
     */
    public function orderFormCheckoutAction(OrderForm $orderForm): ResponseInterface
    {
        FrontendSessionUtility::storeSessionData($this->request, self::class . '->' . __FUNCTION__, $orderForm->exportForSession());

        $this->view->assign('orderurl', $this->createOrderUrl());
        $this->view->assign('successurl', $this->createCheckoutUrl(['tx_easyshop_checkout' => ['mode' => 'return']]));
        $this->view->assign('errorurl', $this->createCheckoutUrl(['tx_easyshop_checkout' => ['mode' => 'error']]));
        $this->view->assign('orderForm', $orderForm);

        return $this->htmlResponse();
    }

    /**
     * Show basket
     * @return ResponseInterface
     */
    public function basketAction(): ResponseInterface
    {
        // $arguments = $this->getCartFromSession($this->request);
        // $this->view->assign('cart', $arguments['cart'] ?? []);
        return $this->htmlResponse();
    }

    /**
     * Update basket
     * @param Product $product
     * @param int $quantity
     * @return ResponseInterface
     */
    public function updateBasketAction(Product $product, int $quantity = 1): ResponseInterface
    {
        return $this->redirect('basket');
    }

    /**
     * Remove product from basket
     * @param Product $product
     * @return ResponseInterface
     */
    public function removeFromBasketAction(Product $product): ResponseInterface
    {
        return $this->redirect('basket');
    }
}
