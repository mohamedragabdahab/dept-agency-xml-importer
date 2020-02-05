<?php

namespace App\Import\Model;

use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Import\Mapper\SizeMapper;
use Doctrine\ORM\EntityManagerInterface;

class VariantProcessing
{
    protected $manager;
    protected $sizeMapper;

    public function __construct(EntityManagerInterface $manager, SizeMapper $sizeMapper)
    {
        $this->manager = $manager;
        $this->sizeMapper = $sizeMapper;
    }

    public function processVariants(array $variantSizes, string $productGroup, Product $productEntity): void
    {
        foreach ($variantSizes as $size) {

            $size = $this->sizeMapper->getMappedSize($size, $productGroup);
            $sku = sprintf('%s-%s', $productEntity->getSku(), $size);
            $name = sprintf('%s %s %s', $productEntity->getName(), $productEntity->getColor(), $size);

            $this->createVariants($sku, $name, $size, $productEntity);
        }
    }

    private function createVariants(string $sku, string $name, string $size, Product $product): void
    {
        $productVariants = new ProductVariant();
        $productVariants->setSku($sku);
        $productVariants->setName($name);
        $productVariants->setSize($size);
        $productVariants->setProduct($product);

        $this->manager->persist($productVariants);
        $this->manager->flush();
    }
}