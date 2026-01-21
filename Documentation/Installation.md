# Installation of easyshop
Download and install the [extension][1] with the extension manager module.

## 1.0 Installation with composer
`composer req wacon/easyshop`

## 1.1 Create needed TYPO3 pages
1. You need a folder where the products are stored.
2. You need a page where you list your products. Therefore is the plugin: **Easyshop - List with Detail view**. Create the TYPO3 page, if needed and place the plugin. On this list page, you need to add a TypoScript record extension and add the Static File **Easyshop - List and detail view**.
3. You need a checkout page, where you add the plugin **Easyshop -Checkout**.
4. You need a order form page, where you add the plugin **Easyshop - Order Form**

Make sure to assign the product folder as **Record Storage Page** in all plugins.

## 1.2 Add Site set to your site
Assign the Site Set: wacon/easyshop (Easyshop) to your [Site Management][2]. Then edit the site set with your data in the [Site Set Editor][3].
