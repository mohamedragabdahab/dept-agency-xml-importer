<?php

namespace App\Library;

class ConvertXmlToJson
{
    public function convert(string $xmlString): string
    {
        $xml = simplexml_load_string($xmlString);

        return json_encode($xml);
    }
}