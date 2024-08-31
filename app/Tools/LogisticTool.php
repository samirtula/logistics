<?php

declare(strict_types=1);

namespace App\Tools;

class LogisticTool
{
    public static function getArrayTypeMap(): array
    {
        return [
            'typeMap' => [
                'root' => 'array',
                'document' => 'array',
                'array' => 'array',
            ]
        ];
    }
}
