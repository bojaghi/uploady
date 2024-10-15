<?php

namespace ShoplicKr\Uploady\Validators;

use ShoplicKr\Uploady\File;
use stdClass;

class ImageSize implements Validator
{
    private File $file;

    private stdClass $exactSize;

    private stdClass $minSize;

    private stdClass $maxSize;

    public function __construct(File $file, array $config = [])
    {
        $config = wp_parse_args(
            $config,
            [
                'exact' => [
                    'width'  => -1,
                    'height' => -1,
                ],
                'min'   => [
                    'width'  => -1,
                    'height' => -1,
                ],
                'max'   => [
                    'width'  => -1,
                    'height' => -1,
                ]
            ],
        );

        $this->file      = $file;
        $this->exactSize = self::sanitizeResolution($config['exact']);
        $this->minSize   = self::sanitizeResolution($config['min']);
        $this->maxSize   = self::sanitizeResolution($config['max']);
    }

    /**
     * @throws ValidationError
     */
    public function validate(): void
    {
        $size = wp_getimagesize($this->file->tmpName);
        if (empty($size)) {
            // Skip if it is not image file.
            return;
        }

        [$width, $height] = $size;

        if (
            self::available($this->exactSize) &&
            (
                ($this->exactSize->width > 0 && $this->exactSize->width !== $width) ||
                ($this->exactSize->height > 0 && $this->exactSize->height !== $height)
            )
        ) {
            throw new ValidationError(
                sprintf(
                    'Image size should be exact %dx%d, but %dx%d is given',
                    $this->exactSize->width,
                    $this->exactSize->height,
                    $width,
                    $height,
                ),
            );
        }

        if (
            self::available($this->minSize) &&
            (
                ($this->minSize->width > 0 && $this->minSize->width > $width) ||
                ($this->minSize->height > 0 && $this->minSize->height > $height)
            )
        ) {
            throw new ValidationError(
                sprintf(
                    'Image size should be smaller than %dx%d, but %dx%d is given',
                    $this->minSize->width,
                    $this->minSize->height,
                    $width,
                    $height,
                ),
            );
        }

        if (self::available($this->maxSize) &&
            (
                ($this->maxSize->width > 0 && $this->maxSize->width < $width) ||
                ($this->maxSize->height > 0 && $this->maxSize->height < $height)
            )
        ) {
            throw new ValidationError(
                sprintf(
                    'Image size should be larger than %dx%d, but %dx%d is given',
                    $this->maxSize->width,
                    $this->maxSize->height,
                    $width,
                    $height,
                ),
            );
        }
    }

    private static function sanitizeResolution(mixed $input): \stdClass
    {
        if (is_string($input) && preg_match('/^(\d+)+[x*,_\-\s]+(\d+)$/', $input, $matches)) {
            return (object)[
                'width'  => (int)$matches[1],
                'height' => (int)$matches[2],
            ];
        }

        if (is_array($input)) {
            if (wp_is_numeric_array($input)) {
                return (object)[
                    'width'  => (int)$input[0],
                    'height' => (int)$input[1],
                ];
            } else {
                return (object)[
                    'width'  => (int)$input['width'] ?? -1,
                    'height' => (int)$input['height'] ?? -1,
                ];
            }
        }

        return (object)[
            'width'  => -1,
            'height' => -1,
        ];
    }

    private static function available(stdClass $size): bool
    {
        return $size->width > 0 && $size->height > 0;
    }
}
