<?php

return new \Atoolo\Resource\Resource(
    '/primaryParentWithoutUrl.php',
    'primaryParentWithoutUrl',
    'primaryParentWithoutUrl',
    '',
    [
        'base' => [
            'trees' => [
                'category' => [
                    'children' => [
                        'a' => 'invalid'
                    ]
                ]
            ]
        ]
    ]
);
