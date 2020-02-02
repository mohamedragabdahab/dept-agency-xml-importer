<?php

namespace App\Import\Transformer;

class Transform
{
    private $productGroups;
    private $products;

    public function __construct(array $data)
    {
        $this->productGroups = $data['product-groups'];
        $this->products = $data['products'];
    }

    public function transform()
    {
        $productsData = [];

        foreach ($this->products['product'] as $product) {
            $productTransformer = new ProductTransformer();
            $productTransformer->validate($product);

            $productSku = $productTransformer->getSku();
            $productName = $productTransformer->getName();

            $productsData[$productSku]['name'] = $productName;
            $productsData[$productSku]['group'] = $productTransformer->getGroup();

            $productVariantsTransformer = new ProductVariantsTransformer();

            foreach ($product['variants'] as $variants) {

                foreach ($variants as $variant) {
                    $productVariantsTransformer->validate($variant, $productTransformer);

                    $productVariants = [];
                    $productVariants['sku'] = $productVariantsTransformer->getSku();
                    $productVariants['size'] = $productVariantsTransformer->getSize();
                    $productVariants['name'] = $productVariantsTransformer->getName();

                    $productsData[$productSku]['variants'][$productVariantsTransformer->getColor()][] = $productVariants;
                }

            }

            $productsData[$productSku]['colors'] = $productVariantsTransformer->getColorCollection();
        }

        return $productsData;
    }

}