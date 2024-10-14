<?php

namespace ShoplicKr\Uploady;

use ShoplicKr\Uploady\Validators\NumFiles;
use ShoplicKr\Uploady\Validators\Validator;
use WP_Error;

class Uploady
{
    public static function validate(
        string     $varName,
        array      $config = [],
        array|null $file = null,
    ): true|WP_Error
    {
        if (is_null($file)) {
            $file = $_FILES[$varName] ?? [];
        }

        [$items, $isMultiple] = self::_filesToItems($file);

        $error  = new WP_Error();
        $config = wp_parse_args($config, self::getDefaultConfig());

        foreach ($config as $criteria => $args) {
            foreach ($items as $idx => $item) {
                $validator = self::getValidator($criteria, $args, $item, $idx, $items);
                if ($validator && !$validator->validate($item)) {
                    $error->add($vadiator->getErrorCode(), $validator->getErrorMessage());
                }
            }
        }

        return $error->has_errors() ? $error : true;
    }

    private static function getValidator(
        string $criteria,
        mixed  $args,
        bool   $isMultiple,
        int    $idx,
    ): Validator|null
    {
    }

    private static function _filesToItems(array $file): array
    {
        $items   = [];
        $isArray = is_array($file['name']);

        if ($isArray) {
            $count = count($file['name']);

            for ($i = 0; $i < $count; $i++) {
                $item = new File();

                // error
                if (isset($file['error'][$i])) {
                    $item->error = $file['error'][$i];
                }

                // full_path
                if (isset($file['full_path'][$i])) {
                    $item->fullPath = $file['full_path'][$i];
                }

                // name
                if (isset($file['name'][$i])) {
                    $item->name = $file['name'][$i];
                }

                // size
                if (isset($file['size'][$i])) {
                    $item->size = $file['size'][$i];
                }

                // tmp_name
                if (isset($file['tmp_name'][$i])) {
                    $item->tmpName = $file['tmp_name'][$i];
                }

                // type
                if (isset($file['type'][$i])) {
                    $item->type = $file['type'][$i];
                }

                $items[] = $item;
            }
        } else {
            $items = [new File($file)];
        }

        return [$items, $isArray];
    }

    public static function getDefaultConfig(): array
    {
        return [
            'allowMultiple' => false,
            'numFiles'      => null,
            'fileSize'      => null,
            'fileTypes'     => null,
        ];
    }
}
