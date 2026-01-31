<?php


namespace Omarsaiouf\Integrations\Enums;
enum MappingMode: string
{
    case LIST = 'list';
    case SINGLE = 'single';


    public static function getAllValue(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(?string $lang = null): string
    {
        $key = "mapping.mode.{$this->value}";
        $label = __($key, [], $lang);

        if ($label === $key) {
            $label = __($key, [], 'en');
        }

        return $label === $key ? $this->value : $label;
    }
}