<?php

namespace ShoplicKr\Uploady;

class File
{
    public int $error;
    public string $fullPath;
    public string $name;
    public int $size;
    public string $tmpName;
    public string $type;

    public function __construct(File|array $file = [])
    {
        if ($file instanceof File || is_object($file)) {
            $this->error    = $file->error ?? UPLOAD_ERR_NO_FILE;
            $this->fullPath = $file->fullPath ?? '';
            $this->name     = $file->name ?? '';
            $this->size     = $file->size ?? 0;
            $this->tmpName  = $file->tmpName ?? '';
            $this->type     = $file->type ?? '';
        } elseif (is_array($file)) {
            $this->error    = $file['error'] ?? UPLOAD_ERR_NO_FILE;
            $this->fullPath = $file['full_path'] ?? '';
            $this->name     = $file['name'] ?? '';
            $this->size     = $file['size'] ?? 0;
            $this->tmpName  = $file['tmp_name'] ?? '';
            $this->type     = $file['type'] ?? '';
        } else {
            $this->error    = UPLOAD_ERR_NO_FILE;
            $this->fullPath = '';
            $this->name     = '';
            $this->size     = 0;
            $this->tmpName  = '';
            $this->type     = '';
        }
    }
}
