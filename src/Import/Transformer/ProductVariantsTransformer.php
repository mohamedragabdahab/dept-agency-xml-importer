<?php

namespace App\Import\Transformer;

use App\Entity\Product;
use App\Import\Mapper\SizeMapper;

class ProductVariantsTransformer
{
    private $color;
    private $sku;
    private $size;
    private $product;
    private $sizeMapper;

    private $data;

    public function __construct(Product $product, array $data)
    {
        $this->sizeMapper = new SizeMapper();
        $this->data = $data;
        $this->product = $product;

        $this->transform();
    }

    private function transform()
    {
        $item = $this->data;
        $this->sku = sprintf('%s-%s', $this->product->getSku(), $item['variant-code']);
        $this->color = $item['color'];
        $this->size = $this->sizeMapper->getMappedSize($item['size']);
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getSize(): string
    {
        return $this->size;
    }
}