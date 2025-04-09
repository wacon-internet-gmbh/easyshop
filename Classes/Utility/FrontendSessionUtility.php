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

namespace Wacon\Easyshop\Utility;

use Psr\Http\Message\ServerRequestInterface;

class FrontendSessionUtility
{
    /**
     * Example how to store data in session
     * @param ServerRequestInterface $request
     * @param string $id
     * @param string|array $data
     */
    public static function storeSessionData(ServerRequestInterface $request, string $id, string|array $data): void
    {
        $frontendUser = $request->getAttribute('frontend.user');
        $frontendUser->setKey('ses', $id, $data);
        $frontendUser->storeSessionData();
    }

    /**
     * Get data from session
     * @param ServerRequestInterface $request
     * @param string $id
     * @return mixed
     */
    public static function getSessionData(ServerRequestInterface $request, string $id)
    {
        return $request->getAttribute('frontend.user')->getKey('ses', $id);
    }

    /**
     * Checks if session data is present
     * @param ServerRequestInterface $request
     * @param string $id
     * @return bool
     */
    public static function hasSessionData(ServerRequestInterface $request, string $id): bool
    {
        return $request->getAttribute('frontend.user')->getKey('ses', $id) ? true : false;
    }

    /**
     * Remove data in session
     * @param ServerRequestInterface $request
     * @param string $id
     */
    public static function removeSessionData(ServerRequestInterface $request, string $id): void
    {
        $frontendUser = $request->getAttribute('frontend.user');
        $frontendUser->setKey('ses', $id, null);
        $frontendUser->storeSessionData();
    }
}
