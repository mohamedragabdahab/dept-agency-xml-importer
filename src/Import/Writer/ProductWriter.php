<?php

namespace App\Import\Writer;

use App\Entity\Product;
use App\Entity\ProductVariant;
use Doctrine\ORM\EntityManagerInterface;

class ProductWriter
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ProductWriter constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Product $product
     */
    public function saveProduct(Product $product): void
    {
        $existingProduct = $this->entityManager
            ->getRepository(Product::class)
            ->findOneBySku($product->getSku());

        if (!$existingProduct) {
            $this->entityManager->persist($product);
            $this->entityManager->flush();
            return;
        }

        $product->setId($existingProduct->getId());

        $existingProduct->setName($product->getName());
        $this->entityManager->merge($existingProduct);
        $this->entityManager->flush();
    }

    /**
     * @param ProductVariant $productVariant
     */
    public function saveProductVariant(ProductVariant $productVariant): void
    {
        $existingProductVariant = $this->entityManager
            ->getRepository(ProductVariant::class)
            ->findOneBySku($productVariant->getSku());

        $product = $productVariant->getProduct();
        if ($product->getId()) {
            $product = $this->entityManager->getRepository(Product::class)
                ->find($product->getId());
            $productVariant->setProduct($product);
        }

        if (!$existingProductVariant) {
            $this->entityManager->persist($productVariant);
            $this->entityManager->flush();
            return;
        }

        $productVariant->setId($existingProductVariant->getId());

        $existingProductVariant->setName($productVariant->getName());
        $existingProductVariant->setSize($productVariant->getSize());
        $this->entityManager->merge($existingProductVariant);
        $this->entityManager->flush();
    }
}
