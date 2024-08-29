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
use Wacon\Easyshop\Domain\Repository\ProductRepository;
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
        $paypalService = GeneralUtility::makeInstance(PayPalService::class, $this->settings['checkout']['paypal']);

        try {
            $paypalService->authorize();
        }catch(\InvalidArgumentException $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ];
        }

        $this->view->assign('response', $response);
        return $this->jsonResponse();
    }
}
