<?php

namespace App\Import\Transformer;

class DataStructure
{
    public function test()
    {
        return [
            '123' => [
                'name' => 'Product A',
                'group' => '',
                'variants' => [
                    'red' => [
                        'sizes' => ['S', 'M', 'L']
                    ],
                    'blue' => [
                        'sizes' => ['S', 'M', 'L']
                    ]
                ]
            ],
            '456' => [
                'name' => 'Product B',
                'group' => '',
                'variants' => [
                    'red' => [
                        'sizes' => ['S', 'M', 'L']
                    ],
                    'blue' => [
                        'sizes' => ['S', 'M', 'L']
                    ]
                ]
            ],
            '900' => [
                'name' => 'Product C',
                'group' => '',
                'variants' => [
                    'off-white' => [
                        'sizes' => ['S', 'M']
                    ]
                ]
            ],
        ];

    }


}