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
     * @return string
     */
    protected function createCheckoutUrl(): string
    {
        return $this->uriBuilder
            ->reset()
            ->setCreateAbsoluteUri(true)
            ->setTargetPageType((int)$this->settings['pageTypes']['checkout'])
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
}
