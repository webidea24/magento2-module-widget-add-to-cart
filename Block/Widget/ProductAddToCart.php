<?php

namespace Webidea24\WidgetProductAddToCart\Block\Widget;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use Magento\Widget\Block\BlockInterface;

class ProductAddToCart extends AbstractProduct implements BlockInterface, IdentityInterface
{

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var UrlHelper
     */
    private $urlHelper;

    protected $_template = 'Webidea24_WidgetProductAddToCart::addToCart-block.phtml';

    public function __construct(
        Context $context,
        UrlHelper $urlHelper,
        ProductRepositoryInterface $productRepository,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->urlHelper = $urlHelper;
        $this->productRepository = $productRepository;
    }

    protected function _toHtml()
    {
        return $this->getProduct() ? parent::_toHtml() : '';
    }

    /**
     * Get post parameters
     *
     * @param Product $product
     * @return array
     */
    public function getAddToCartPostParams(Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED => $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }

    public function getProduct(): ?Product
    {
        if (!$product = $this->getData('_product')) {
            $idPath = $this->getData('id_path');
            $exploded = explode('/', $idPath);
            if (!isset($exploded[1])) {
                return null;
            }

            try {
                $product = $this->productRepository->getById((int)$exploded[1]);
            } catch (NoSuchEntityException $e) {
                return null;
            }
            $this->setData('_product', $product);
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $product;
    }

    public function getIdentities()
    {
        return ['widget_product_addToCart'] + $this->getProduct()->getIdentities();
    }
}
