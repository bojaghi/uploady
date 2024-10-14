<?php

namespace ShoplicKr\Uploady\Validators;

use ShoplicKr\Uploady\File;

interface Validator
{
    public function validate(File $item);
}
