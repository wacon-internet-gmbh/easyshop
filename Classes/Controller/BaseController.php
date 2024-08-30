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

use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class BaseController extends ActionController
{
    /**
     * Create the checkout url
     * @param array $arguments
     * @return string
     */
    protected function createCheckoutUrl(array $arguments = []): string
    {
        $this->uriBuilder
            ->reset()
            ->setCreateAbsoluteUri(true)
            ->setTargetPageUid((int)$this->settings['pages']['checkout']);

        if (!empty($arguments)) {
            $this->uriBuilder->setArguments($arguments);
        }

        return $this->uriBuilder->build();
    }

    /**
     * Create the checkout url
     * @return string
     */
    protected function createOrderUrl(): string
    {
        return $this->uriBuilder
            ->reset()
            ->setCreateAbsoluteUri(true)
            ->setTargetPageType((int)$this->settings['pageTypes']['order'])
            ->build();
    }

    /**
     * When list action is called along with a product argument, we forward to detail action.
     */
    protected function forwardToDetailActionWhenRequested(): ?ForwardResponse
    {
        if (!$this->request->hasArgument('product')) {
            return null;
        }

        $forwardResponse = new ForwardResponse('detail');
        return $forwardResponse->withArguments(['product' => $this->request->getArgument('product')]);
    }

    /**
     * Fetch the post data from request body
     * triggered by ES6 fetch method
     * @return array
     */
    protected function getCartFromPostViaJSON(): array
    {
        $body = $this->request->getBody()->getContents();
        $body = $body ? \json_decode($body, true) : [];
        $body = array_key_exists('tx_easyshop_order', $body) ? $body['tx_easyshop_order'] : [];

        return $body;
    }
}
