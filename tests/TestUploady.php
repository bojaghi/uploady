<?php

namespace ShoplicKr\Uploady\Tests;

use WP_UnitTestCase;

class TestUploady extends WP_UnitTestCase
{
    public function test_uploady(): void
    {
        $errors = Uploady::validate(
            $_FILES['test_me'],
            [
                ''
            ]
        );

        if ($errors->has_errors()) {
            // Do something with errors.
            return;
        }

        // Do something when the file is clean.
    }
}
