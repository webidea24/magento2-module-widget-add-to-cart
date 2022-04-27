<?php

namespace Webidea24\WidgetProductAddToCart\Block;

use Magento\Catalog\Block\Product\View;
use Magento\Catalog\Model\Product;

class ProductView extends View
{
    public function setProduct(Product $product)
    {
        $this->setData('product', $product);

        return $this;
    }

    public function getProduct()
    {
        return $this->getData('product');
    }

}
