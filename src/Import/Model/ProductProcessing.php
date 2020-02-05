<?php

namespace App\Import\Model;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductProcessing
{
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function createProduct(string $sku, string $name, string $color): Product
    {
        $product = new Product();

        $product->setSku($sku);
        $product->setName($name);
        $product->setColor($color);

        $this->manager->persist($product);
        $this->manager->flush();

        return $product;
    }
}