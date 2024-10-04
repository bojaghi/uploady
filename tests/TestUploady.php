<?php

namespace ShoplicKr\Uploady\Tests;

use ShoplicKr\Uploady\Uploady;
use ShoplicKr\Uploady\File;

use WP_UnitTestCase;

class TestUploady extends WP_UnitTestCase
{
    public function test_uploady(): void
    {
//        $errors = Uploady::validate(
//            'test_me',
//            [
//                'allowMultiple' => false,
//                'fileSize'      => [
//                    'min' => null,
//                    'max' => 300,
//                ],
//                'mimeTypes'     => [
//                    'txt|bak|log' => 'text/plain',
//                ],
//                'numFiles'      => [
//                    'min' => null,
//                    'max' => 3,
//                ],
//                'required'      => false,
//            ],
//            // $_FILES alternatives.
//            [
//                'test_me' => [
//                    'name'     => 'test.me',
//                    'type'     => 'text/plain',
//                    'size'     => 182,
//                    'tmp_name' => '/tmp/tempname',
//                    'error'    => 0,
//                ],
//            ],
//        );
//
//        if ($errors->has_errors()) {
//            // Do something with errors.
//            return;
//        }

        // Do something when the file is clean.
        $this->assertTrue(true);
    }

    /**
     * @dataProvider fileToTiemsProvider
     *
     * @return void
     */
    public function testFilesToItems($expected, $actual)
    {
        $reflection = new \ReflectionClass(Uploady::class);
        $method     = $reflection->getMethod('_filesToItems');
        $method->setAccessible(true);

        $actual = $method->invoke(null, $actual);
        $this->assertEquals($expected, $actual);
    }

    public function fileToTiemsProvider(): array
    {
        return [
            'correct multiple items' => [
                // Expected
                [
                    [
                        new File(
                            [
                                'name'      => 'foo.jpg',
                                'full_path' => 'foo.jpg',
                                'type'      => 'image/jpeg',
                                'tmp_name'  => '/tmp/php5pleZk',
                                'error'     => 0,
                                'size'      => 182873
                            ],
                        ),
                        new File(
                            [
                                'name'      => 'bar.png',
                                'full_path' => 'bar.png',
                                'type'      => 'image/png',
                                'tmp_name'  => '/tmp/php2bwzme',
                                'error'     => 0,
                                'size'      => 95866
                            ],
                        ),
                        new File(
                            [
                                'name'      => 'baz.zip',
                                'full_path' => 'baz.zip',
                                'type'      => 'application/zip',
                                'tmp_name'  => '/tmp/phpFOqINz',
                                'error'     => 0,
                                'size'      => 3289,
                            ],
                        ),
                    ],
                    true,
                ],
                // Actual
                [
                    'name'      => ['foo.jpg', 'bar.png', 'baz.zip'],
                    'full_path' => ['foo.jpg', 'bar.png', 'baz.zip'],
                    'type'      => ['image/jpeg', 'image/png', 'application/zip'],
                    'tmp_name'  => ['/tmp/php5pleZk', '/tmp/php2bwzme', '/tmp/phpFOqINz'],
                    'error'     => [0, 0, 0],
                    'size'      => [182873, 95866, 3289],
                ],
            ],
            'correct single item'    => [
                // Expected
                [
                    [
                        new File(
                            [
                                'name'      => 'foo.jpg',
                                'full_path' => 'foo.jpg',
                                'type'      => 'image/jpeg',
                                'tmp_name'  => '/tmp/php5pleZk',
                                'error'     => 0,
                                'size'      => 182873,
                            ],
                        ),
                    ],
                    false
                ],
                // Actual
                [
                    'name'      => 'foo.jpg',
                    'full_path' => 'foo.jpg',
                    'type'      => 'image/jpeg',
                    'tmp_name'  => '/tmp/php5pleZk',
                    'error'     => 0,
                    'size'      => 182873,
                ]
            ]
        ];
    }
}
