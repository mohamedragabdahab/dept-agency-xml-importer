<?php


namespace App\Import\Transformer;


class ProductTransformer
{
    private $name;
    private $sku;
    private $color;

    public function getName(): string
    {
        return $this->name;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getColor(): string
    {
        return $this->color;
    }


}