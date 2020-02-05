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
        $data = $transformer->transform();

        foreach ($data as $sku => $item) {
            foreach ($item['variants'] as $color => $variants) {
                $product = $this->createProduct($sku, $color, $item);
                $this->createVariants($variants['sizes'], $product);
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

    private function createProduct($sku, $color, $item): Product
    {
        $product = new Product();

        $product->setSku($sku . '-' . $color);
        $product->setName($item['name']);
        $product->setColor($color);

        $this->manager->persist($product);
        $this->manager->flush();

        return $product;
    }

    private function createVariants($sizes, Product $product): void
    {
        foreach ($sizes as $size) {
            $productVariants = new ProductVariant();
            $productVariants->setSku($product->getSku() . '-' . $size);
            $productVariants->setName($product->getName() . ' ' . $product->getColor() . ' ' . $size);
            $productVariants->setSize($size);
            $productVariants->setProduct($product);

            $this->manager->persist($productVariants);
            $this->manager->flush();
        }
    }
}