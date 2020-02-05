<?php

namespace App\Import;

use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Import\Transformer\Transformer;
use App\Library\ConvertXmlToJson;
use Doctrine\ORM\EntityManagerInterface;

class ProductImporter implements ProductImporterInterface
{
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function import(string $filePath): void
    {
        $transformer = new Transformer($this->buildData($filePath));
        $productData = $transformer->transform();

        foreach ($productData as $sku => $product) {
            foreach ($product['variants'] as $color => $variants) {

                $productEntity = $this->createProduct($sku, $product['name'], $color);

                foreach ($variants['sizes'] as $size) {
                    $this->createVariants($size, $productEntity);
                }
            }
        }
    }

    private function buildData(string $filePath): array
    {
        $xmlString = file_get_contents($filePath);
        $convertXmlToJson = new ConvertXmlToJson();
        $data = $convertXmlToJson->convert($xmlString);

        return json_decode($data, true);
    }

    private function createProduct(string $sku, string $name, string $color): Product
    {
        $product = new Product();

        $product->setSku($sku . '-' . $color);
        $product->setName($name);
        $product->setColor($color);

        $this->manager->persist($product);
        $this->manager->flush();

        return $product;
    }

    private function createVariants(string $size, Product $product): void
    {
        $productVariants = new ProductVariant();
        $productVariants->setSku($product->getSku() . '-' . $size);
        $productVariants->setName($product->getName() . ' ' . $product->getColor() . ' ' . $size);
        $productVariants->setSize($size);
        $productVariants->setProduct($product);

        $this->manager->persist($productVariants);
        $this->manager->flush();
    }
}