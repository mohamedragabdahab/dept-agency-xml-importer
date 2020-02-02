<?php

namespace App\Import\Mapper;

class SizeMapper
{
    const SIZE_SMALL = 'S';
    const SIZE_MEDIUM = 'M';
    const SIZE_LARGE = 'L';
    const SIZE_SEPARATOR = '/';

    const GROUPS = [
        '1000' => 'DE',
        '1010' => 'US'
    ];


    const SIZES = [
        'US' => [
            self::SIZE_SMALL => '36-38',
            self::SIZE_MEDIUM => '38-40',
            self::SIZE_LARGE => '40-42',
        ],
        'DE' => [
            self::SIZE_SMALL => '91-96',
            self::SIZE_MEDIUM => '96-101',
            self::SIZE_LARGE => '101-106',
        ],
    ];

    public function getMappedSize(string $size, $group): string
    {
        if (!is_numeric($size)) {
            return $size;
        }

        $sizes = self::SIZES[$this->getGroup($group)];
        $mappedSize = [];

        foreach ($sizes as $sizeLabel => $sizeRange) {
            list($min, $max) = explode('-', $sizeRange);
            if ($size >= $min && $size <= $max) {
                $mappedSize[] = $sizeLabel;
            }
        }

        return implode(self::SIZE_SEPARATOR, $mappedSize);
    }

    private function getGroup(string $group): string
    {
        return self::GROUPS[$group];
    }
}