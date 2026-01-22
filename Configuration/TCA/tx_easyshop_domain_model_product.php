<?php
defined('TYPO3') or die();

return call_user_func(function () {
    $tx_easyshop_domain_model_product = new \Wacon\Easyshop\Bootstrap\TCA\Product();
    $tx_easyshop_domain_model_product->invoke();
    return $tx_easyshop_domain_model_product->getTca();
});
