<?php

namespace ShoplicKr\Uploady\Validators;

use ShoplicKr\Uploady\File;

class FileType implements Validator
{
    private File $file;
    private array $mimeTypes;

    public function __construct(File $file, array $config = [])
    {
        $this->file = $file;

        $config = wp_parse_args(
            $config,
            [
                'mime_types' => null, // null: use default wp_get_mime_types()
            ],
        );

        $this->mimeTypes = $config['mime_types'];
    }

    /**
     * @throws ValidationError
     */
    public function validate(): void
    {
        [
            'ext'             => $ext,
            'type'            => $type,
            'proper_filename' => $properFileName,
        ] = wp_check_filetype_and_ext(
            file: $this->file->tmpName,
            filename: $this->file->name,
            mimes: $this->mimeTypes,
        );

        if (!$ext) {
            throw new ValidationError(
                "{$this->file->name} has an invalid file extension.",
                'fileType',
            );
        }

        if (!$type) {
            throw new ValidationError(
                "{$this->file->name} has an invalid file type.",
                'fileType',
            );
        }
    }
}