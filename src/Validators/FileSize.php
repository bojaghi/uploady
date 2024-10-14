<?php

namespace ShoplicKr\Uploady\Validators;

use ShoplicKr\Uploady\File;

class FileSize implements Validator
{
    private int $exactSize;

    private int $maxSize;

    private int $minSize;

    public function __construct(array $args = [])
    {
        $args = wp_parse_arge(
            $args,
            [
                'exact' => '',
                'max'   => '',
                'min'   => '',
            ],
        );

        $this->exactSize = self::parseSize($args['exact']);
        $this->maxSize   = self::parseSize($args['max']);
        $this->minSize   = self::parseSize($args['min']);
    }

    public function validate(File $item)
    {
        if ($this->exactSize > 0 && $this->exactSize !== $item->size) {
            // throw.
        }

        if ($this->minSize > 0 && $this->minSize > $item->size) {
            // throw.
        }

        if ($this->maxSize > 0 && $this->maxSize < $this->size) {
            // throw.
        }
    }

    private static function parseSize(string $size): int
    {
        $output = -1;

        if (preg_match('/^([0-9]*\.?[0-9]+)([KMGT]?)$/i', $size, $matches)) {
            $amount = (float)$matches[1];
            $unit   = strtoupper($matches[2]);
            $output = (int)match ($unit) {
                'K' => $amount * KB_IN_BYTES,
                'M' => $amount * MB_IN_BYTES,
                'G' => $amount * GB_IN_BYTES,
                'T' => $amount * TB_IN_BYTES,
                default => $amount,
            };
        }

        return $output ?: -1;
    }
}

