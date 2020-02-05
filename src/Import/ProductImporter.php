<?php

namespace App\Import;

use App\Import\Transformer\DataProcessing;
use App\Import\Transformer\Transformer;
use App\Library\ConvertXmlToJson;

class ProductImporter implements ProductImporterInterface
{
    protected $dataProcessing;

    public function __construct(DataProcessing $dataProcessing)
    {
        $this->dataProcessing = $dataProcessing;
    }

    public function import(string $filePath): void
    {
        $transformer = new Transformer($this->buildData($filePath));
        $productData = $transformer->transform();
        $this->dataProcessing->process($productData);
    }

    private function buildData(string $filePath): array
    {
        $xmlString = file_get_contents($filePath);
        $convertXmlToJson = new ConvertXmlToJson();
        $data = $convertXmlToJson->convert($xmlString);

        return json_decode($data, true);
    }
}