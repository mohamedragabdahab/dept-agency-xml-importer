<?php

namespace App\Import;

use App\Entity\Product;
use App\Entity\ProductVariant;
use App\Import\Mapper\SizeMapper;
use App\Import\Transformer\Transform;
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
        $sizeMapper = new SizeMapper();
        $transform = new Transform($this->buildData($filePath));
        $transform->transform();
        $data = $transform->getTransformedData();

        foreach ($data as $sku => $item) {
            foreach ($item['colors'] as $color) {
                $product = $this->createProduct($sku, $color, $item);

                $this->createVariants($item['variants'][$color], $product, $sizeMapper, $item['group']);
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

    private function createVariants($variants, Product $product, SizeMapper $sizeMapper, string $group): void
    {
        foreach ($variants as $variant) {
            $productVariants = new ProductVariant();
            $productVariants->setSku($product->getSku() . '-' . $variant['size']);
            $productVariants->setName($variant['name']);
            $productVariants->setSize($sizeMapper->getMappedSize($variant['size'], $group));
            $productVariants->setProduct($product);

            $this->manager->persist($productVariants);
            $this->manager->flush();
        }
    }

}