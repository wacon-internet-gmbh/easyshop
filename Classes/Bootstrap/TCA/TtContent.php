<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension: easyshop_package.
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

namespace Wacon\Easyshop\Bootstrap\TCA;

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Wacon\Easyshop\Bootstrap\Base;

class TtContent extends Base
{
    protected int $typo3MajorVersion = 13;

    /**
     * Does the main class purpose
     */
    public function invoke()
    {
        $this->typo3MajorVersion = GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion();
        $this->registerPlugins();
    }

    /**
     * Register new frontend plugins
     */
    private function registerPlugins()
    {
        if ($this->typo3MajorVersion >= 14) {
            $pluginSignature = ExtensionUtility::registerPlugin(
                $this->getExtensionKeyAsNamespace(),
                'ListWithDetail',
                $this->getLLL('locallang_plugins.xlf:listwithdetail'),
                'tx_easyshop_domain_model_product',
                'plugins',
                '',
                $this->getFlexformPath('ListWithDetail.xml')
            );

            ExtensionManagementUtility::addToAllTCAtypes(
                'tt_content',
                'pages',
                $pluginSignature,
                'after:pi_flexform'
            );

            $pluginSignature = ExtensionUtility::registerPlugin(
                $this->getExtensionKeyAsNamespace(),
                'Checkout',
                $this->getLLL('locallang_plugins.xlf:checkout'),
                'tx_easyshop_domain_model_product',
            );

            ExtensionManagementUtility::addToAllTCAtypes(
                'tt_content',
                '--div--;Plugin, pages',
                $pluginSignature,
                'after:palette:headers'
            );

            $pluginSignature = ExtensionUtility::registerPlugin(
                $this->getExtensionKeyAsNamespace(),
                'OrderForm',
                $this->getLLL('locallang_plugins.xlf:orderForm'),
                'tx_easyshop_domain_model_product',
            );

            ExtensionManagementUtility::addToAllTCAtypes(
                'tt_content',
                '--div--;Plugin, pages',
                $pluginSignature,
                'after:palette:headers'
            );
        } else {
            $pluginSignature = ExtensionUtility::registerPlugin(
                $this->getExtensionKeyAsNamespace(),
                'ListWithDetail',
                $this->getLLL('locallang_plugins.xlf:listwithdetail'),
            );

            $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

            ExtensionManagementUtility::addPiFlexFormValue(
                $pluginSignature,
                $this->getFlexformPath('ListWithDetail.xml'),
            );

            $pluginSignature = ExtensionUtility::registerPlugin(
                $this->getExtensionKeyAsNamespace(),
                'Checkout',
                $this->getLLL('locallang_plugins.xlf:checkout'),
            );

            $pluginSignature = ExtensionUtility::registerPlugin(
                $this->getExtensionKeyAsNamespace(),
                'OrderForm',
                $this->getLLL('locallang_plugins.xlf:orderForm'),
            );
        }
    }
}
