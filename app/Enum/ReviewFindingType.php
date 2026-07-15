<?php

namespace App\Enum;

enum ReviewFindingType: string
{
    case SECURITY = 'security';
    case BUG = 'bug';
    case PERFORMANCE = 'performance';
    case READABILITY = 'readability';
    case ARCHITECTURE = 'architecture';
    case CODE_SMELL = 'code_smell';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function options(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }
}
