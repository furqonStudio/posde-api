<?php

namespace App\Services;

use App\Enums\BusinessType;

class EnumService
{
    public static function getBusinessTypes(): array
    {
        return array_map(function ($type) {
            return [
                'name' => $type->name, // Nama konstanta enum (contoh: RETAIL, FNB, dll.)
                'value' => $type->value, // Nilai enum (contoh: Retail, F&B, dll.)
            ];
        }, BusinessType::cases());
    }
}
