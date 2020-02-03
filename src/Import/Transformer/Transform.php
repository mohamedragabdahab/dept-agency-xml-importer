<?php

namespace App\Import\Transformer;

class Transform implements Transformable
{
    private $products;
    private $transformedData = [];

    public function __construct(array $data)
    {
        $this->products = $data['products'];
    }

    public function transform(): void
    {
        foreach ($this->products['product'] as $product) {
            $productTransformer = new ProductTransformer();
            $productTransformer->transform($product);

            $productSku = $productTransformer->getSku();
            $productName = $productTransformer->getName();

            $this->transformedData[$productSku]['name'] = $productName;
            $this->transformedData[$productSku]['group'] = $productTransformer->getGroup();

            $productVariantsTransformer = new ProductVariantsTransformer();

            foreach ($product['variants'] as $variants) {

                foreach ($variants as $variant) {
                    $productVariantsTransformer->transform($variant, $productTransformer);

                    $productVariants = [];
                    $productVariants['sku'] = $productVariantsTransformer->getSku();
                    $productVariants['size'] = $productVariantsTransformer->getSize();
                    $productVariants['name'] = $productVariantsTransformer->getName();

                    $this->transformedData[$productSku]['variants'][$productVariantsTransformer->getColor()][] = $productVariants;
                }
            }

            $this->transformedData[$productSku]['colors'] = $productVariantsTransformer->getColorCollection();
        }
    }

    public function getTransformedData(): array
    {
        return $this->transformedData;
    }
}