<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

use Infinityloop\Utils\Json;

final class UploadModuleTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                '{ "0": ["variables.var1"] }',
                Json::fromNative((object) [
                    'query' => 'query queryName($var1: Upload) { fieldUpload(file: $var1) { fileName fileContent } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldUpload' => ['fileName' => 'a.txt', 'fileContent' => 'test file'],
                    ],
                ]),
            ],
            [
                '{ "0": ["variables.var1.0", "variables.var1.1"] }',
                Json::fromNative((object) [
                    'query' => 'query queryName($var1: [Upload]) { fieldMultiUpload(files: $var1) { fileName fileContent } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldMultiUpload' => [
                            ['fileName' => 'a.txt', 'fileContent' => 'test file'],
                            ['fileName' => 'a.txt', 'fileContent' => 'test file'],
                        ],
                    ],
                ]),
            ],
            [
                '{ "0": ["variables.var1.file"] }',
                Json::fromNative((object) [
                    'query' => 'query queryName($var1: UploadInput! = {}) { 
                        fieldInputUpload(fileInput: $var1) { fileName fileContent } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldInputUpload' => ['fileName' => 'a.txt', 'fileContent' => 'test file'],
                    ],
                ]),
            ],
            [
                '{ "0": ["variables.var1.files.0", "variables.var1.files.1"] }',
                Json::fromNative((object) [
                    'query' => 'query queryName($var1: UploadInput! = {}) { 
                        fieldInputMultiUpload(fileInput: $var1) { fileName fileContent } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldInputMultiUpload' => [
                            ['fileName' => 'a.txt', 'fileContent' => 'test file'],
                            ['fileName' => 'a.txt', 'fileContent' => 'test file'],
                        ],
                    ],
                ]),
            ],
            [
                '{ "0": ["variables.var1.0.file"] }',
                Json::fromNative((object) [
                    'query' => 'query queryName($var1: [UploadInput!]! = [{}]) { 
                        fieldMultiInputUpload(fileInputs: $var1) { fileName fileContent } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldMultiInputUpload' => [
                            ['fileName' => 'a.txt', 'fileContent' => 'test file'],
                        ],
                    ],
                ]),
            ],
            [
                '{ "0": ["variables.var1.0.files.0", "variables.var1.0.files.1"] }',
                Json::fromNative((object) [
                    'query' => 'query queryName($var1: [UploadInput!]! = [{}]) { 
                        fieldMultiInputMultiUpload(fileInputs: $var1) { fileName fileContent } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldMultiInputMultiUpload' => [
                            ['fileName' => 'a.txt', 'fileContent' => 'test file'],
                            ['fileName' => 'a.txt', 'fileContent' => 'test file'],
                        ],
                    ],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param string $map
     * @param Json $request
     * @param Json $expected
     */
    public function testSimple(string $map, Json $request, Json $expected) : void
    {
        $stream = $this->createStub(\Psr\Http\Message\StreamInterface::class);
        $stream->method('getContents')->willReturn('test file');
        $file = $this->createStub(\Psr\Http\Message\UploadedFileInterface::class);
        $file->method('getClientFilename')->willReturn('a.txt');
        $file->method('getStream')->willReturn($stream);
        $fileProvider = $this->createStub(\Graphpinator\Module\Upload\FileProvider::class);
        $fileProvider->method('getMap')->willReturn(Json\MapJson::fromString($map));
        $fileProvider->method('getFile')->willReturn($file);
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema(), false, new \Graphpinator\Module\ModuleSet([
            new \Graphpinator\Module\Upload\UploadModule($fileProvider),
        ]));
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                '{ "0": ["queryName.fileUpload.file"] }',
                Json::fromNative((object) [
                    'query' => 'query queryName($var1: Upload) { fieldUpload(file: $var1) { fileName } }',
                ]),
                \Graphpinator\Exception\Upload\OnlyVariablesSupported::class,
            ],
            [
                '{ "0": ["variables.var1.invalid"] }',
                Json::fromNative((object) [
                    'query' => 'query queryName($var1: Upload) { fieldUpload(file: $var1) { fileName } }',
                ]),
                \Graphpinator\Exception\Upload\InvalidMap::class,
            ],
            [
                '{ "0": ["variables.var1.invalid", "variables.var1.1"] }',
                Json::fromNative((object) [
                    'query' => 'query queryName($var1: [Upload]) { fieldMultiUpload(files: $var1) { fileName } }',
                ]),
                \Graphpinator\Exception\Upload\InvalidMap::class,
            ],
            [
                '{ "0": ["variables.var1.0"] }',
                Json::fromNative((object) [
                    'query' => 'query queryName($var1: UploadInput! = {}) { fieldInputUpload(fileInput: $var1) { fileName } }',
                ]),
                \Graphpinator\Exception\Upload\InvalidMap::class,
            ],
            [
                '{ "0": ["variables.var1"] }',
                Json::fromNative((object) [
                    'query' => 'query queryName($var1: UploadInput) { fieldInputUpload(fileInput: $var1) { fileName } }',
                ]),
                \Graphpinator\Exception\Upload\InvalidMap::class,
            ],
            [
                '{ "0": ["variables.var1"] }',
                Json::fromNative((object) [
                    'query' => 'query queryName($var1: Int) { fieldInputUpload(fileInput: $var1) { fileName } }',
                ]),
                \Graphpinator\Exception\Normalizer\VariableTypeMismatch::class,
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param string $map
     * @param Json $request
     * @param string $exception
     */
    public function testInvalid(string $map, Json $request, string $exception) : void
    {
        $this->expectException($exception);
        $this->expectExceptionMessage(\constant($exception . '::MESSAGE'));

        $stream = $this->createStub(\Psr\Http\Message\StreamInterface::class);
        $stream->method('getContents')->willReturn('test file');
        $file = $this->createStub(\Psr\Http\Message\UploadedFileInterface::class);
        $file->method('getClientFilename')->willReturn('a.txt');
        $file->method('getStream')->willReturn($stream);
        $fileProvider = $this->createStub(\Graphpinator\Module\Upload\FileProvider::class);
        $fileProvider->method('getMap')->willReturn(Json\MapJson::fromString($map));
        $fileProvider->method('getFile')->willReturn($file);
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema(), false, new \Graphpinator\Module\ModuleSet([
            new \Graphpinator\Module\Upload\UploadModule($fileProvider),
        ]));
        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));
    }
}
