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
use Wacon\Easyshop\Domain\Model\Product;
use Wacon\Easyshop\Domain\Repository\ProductRepository;
use Wacon\Easyshop\PageTitle\ProductPageTitleProvider;

class ProductController extends BaseController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly ProductPageTitleProvider $pageTitleProvider,
    ) {}

    /**
     * Show list and detail view
     * @return ResponseInterface
     */
    public function listwithdetailAction(): ResponseInterface
    {
        $possibleRedirect = $this->forwardToDetailActionWhenRequested();
        if ($possibleRedirect) {
            return $possibleRedirect;
        }

        $this->view->assign('products', $this->productRepository->findAll());

        return $this->htmlResponse();
    }
    /**
     * Show detail view
     * @param Product $product
     * @return ResponseInterface
     */
    public function detailAction(Product $product): ResponseInterface
    {
        $this->pageTitleProvider->setTitle($product);

        $this->view->assign('product', $product);
        $this->view->assign('orderurl', $this->createOrderUrl());
        $this->view->assign('successurl', $this->createCheckoutUrl(['tx_easyshop_checkout' => ['mode' => 'return']]));
        $this->view->assign('errorurl', $this->createCheckoutUrl(['tx_easyshop_checkout' => ['mode' => 'error']]));

        return $this->htmlResponse();
    }
}
