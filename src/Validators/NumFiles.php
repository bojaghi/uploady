<?php

namespace ShoplicKr\Uploady\Validators;

class NumFiles implements Validator
{
    private array $items;

    private string $varName;

    private int $exactNum;

    private int $minNum;

    private int $maxNum;

    public function __construct(array $items, array $config, string $varName)
    {
        $this->items   = $items;
        $this->varName = $varName;

        $config = wp_parse_args(
            $config,
            [
                'exact' => -1,
                'max'   => -1,
                'min'   => -1,
            ],
        );

        $this->exactNum = (int)$config['exact'];
        $this->maxNum   = (int)$config['max'];
        $this->minNum   = (int)$config['min'];
    }

    public function validate(): void
    {
        if ($this->exactNum > 0 && $this->exactNum !== count($this->items)) {
            // Error.
        }

        if ($this->minNum > 0 && $this->minNum > count($this->items)) {
            // Error.
        }

        if ($this->maxNum > 0 && $this->maxNum < count($this->items)) {
            // Error.
        }
    }
}
