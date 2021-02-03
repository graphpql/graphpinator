<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Infinityloop\Utils\Json;

final class UploadConstraintTest extends \PHPUnit\Framework\TestCase
{
    public static function getUploadType() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'UploadType';

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'fileContent',
                        \Graphpinator\Container\Container::String(),
                        static function (\Psr\Http\Message\UploadedFileInterface $file) : string {
                            return $file->getStream()->getContents();
                        },
                    ),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public function simpleUploadDataProvider() : array
    {
        return [
            [
                new \Graphpinator\Constraint\UploadConstraint(10000),
            ],
            [
                new \Graphpinator\Constraint\UploadConstraint(5000),
            ],
            [
                new \Graphpinator\Constraint\UploadConstraint(null, ['text/plain']),
            ],
            [
                new \Graphpinator\Constraint\UploadConstraint(null, ['application/x-httpd-php', 'text/html', 'text/plain', 'application/pdf']),
            ],
        ];
    }

    public function invalidUploadDataProvider() : array
    {
        return [
            [
                new \Graphpinator\Constraint\UploadConstraint(4999),
                \Graphpinator\Exception\Constraint\MaxSizeConstraintNotSatisfied::class,
            ],
            [
                new \Graphpinator\Constraint\UploadConstraint(null, ['application/x-httpd-php']),
                \Graphpinator\Exception\Constraint\MimeTypeConstraintNotSatisfied::class,
            ],
        ];
    }

    /**
     * @dataProvider simpleUploadDataProvider
     * @param \Graphpinator\Constraint\UploadConstraint $constraint
     */
    public function testUploadSimple(\Graphpinator\Constraint\UploadConstraint $constraint) : void
    {
        $request = Json::fromNative((object) [
            'query' => 'query queryName($var1: Upload) { fieldUpload(file: $var1) { fileContent } }',
            'variables' => (object) ['var1' => null],
        ]);

        $stream = $this->createStub(\Psr\Http\Message\StreamInterface::class);
        $stream->method('getContents')->willReturn('test file');
        $stream->method('getMetaData')->willReturn(__DIR__ . '/textFile.txt');
        $file = $this->createStub(\Psr\Http\Message\UploadedFileInterface::class);
        $file->method('getStream')->willReturn($stream);
        $file->method('getSize')->willReturn(5000);
        $fileProvider = $this->createStub(\Graphpinator\Module\Upload\FileProvider::class);
        $fileProvider->method('getMap')->willReturn(Json\MapJson::fromString('{ "0": ["variables.var1"] }'));
        $fileProvider->method('getFile')->willReturn($file);
        self::getGraphpinator($fileProvider, $constraint)
            ->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertTrue(true);
    }

    /**
     * @dataProvider invalidUploadDataProvider
     * @param string $exception
     * @param \Graphpinator\Constraint\UploadConstraint $constraint
     */
    public function testUploadInvalid(\Graphpinator\Constraint\UploadConstraint $constraint, string $exception) : void
    {
        $request = Json::fromNative((object) [
            'query' => 'query queryName($var1: Upload) { fieldUpload(file: $var1) { fileContent } }',
            'variables' => (object) ['var1' => null],
        ]);

        $stream = $this->createStub(\Psr\Http\Message\StreamInterface::class);
        $stream->method('getContents')->willReturn('test file');
        $stream->method('getMetaData')->willReturn(__DIR__ . '/textFile.txt');
        $file = $this->createStub(\Psr\Http\Message\UploadedFileInterface::class);
        $file->method('getStream')->willReturn($stream);
        $file->method('getSize')->willReturn(5000);
        $fileProvider = $this->createStub(\Graphpinator\Module\Upload\FileProvider::class);
        $fileProvider->method('getMap')->willReturn(Json\MapJson::fromString('{ "0": ["variables.var1"] }'));
        $fileProvider->method('getFile')->willReturn($file);

        self::expectException($exception);
        self::expectExceptionMessage(\constant($exception . '::MESSAGE'));

        self::getGraphpinator($fileProvider, $constraint)
            ->run(new \Graphpinator\Request\JsonRequestFactory($request));
    }

    protected static function getGraphpinator(
        \Graphpinator\Module\Upload\FileProvider $fileProvider,
        \Graphpinator\Constraint\UploadConstraint $constraint,
    ) : \Graphpinator\Graphpinator
    {
        $query = new class ($constraint) extends \Graphpinator\Type\Type
        {
            protected const NAME = 'Query';

            public function __construct(protected \Graphpinator\Constraint\UploadConstraint $constraint)
            {
                parent::__construct();
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldUpload',
                        \Graphpinator\Tests\Feature\UploadConstraintTest::getUploadType()->notNull(),
                        static function ($parent, ?\Psr\Http\Message\UploadedFileInterface $file) : \Psr\Http\Message\UploadedFileInterface {
                            return $file;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        \Graphpinator\Argument\Argument::create(
                            'file',
                            new \Graphpinator\Module\Upload\UploadType(),
                        )->addConstraint($this->constraint),
                    ])),
                ]);
            }
        };

        return new \Graphpinator\Graphpinator(
            new \Graphpinator\Type\Schema(
                new \Graphpinator\Container\SimpleContainer([
                    'Query' => $query,
                    'UploadType' => \Graphpinator\Tests\Feature\UploadConstraintTest::getUploadType(),
                    'Upload' => new \Graphpinator\Module\Upload\UploadType(),
                ], []),
                $query,
            ),
            false,
            new \Graphpinator\Module\ModuleSet([
                new \Graphpinator\Module\Upload\UploadModule($fileProvider),
            ]),
        );
    }
}
