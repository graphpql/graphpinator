<?php

declare(strict_types = 1);

include_once __DIR__ . '/../vendor/autoload.php';

$graphpinator = new \Graphpinator\Graphpinator(\Graphpinator\Tests\Spec\TestSchema::getSchema());
$request = new \Graphpinator\Request\JsonRequestFactory(\Infinityloop\Utils\Json::fromNative((object) [
    'query' => 'query queryName { fieldAbc { fieldXyz { name } } }',
]));

$timeStart = \microtime(true);

for ($i = 0; $i < 10_000; ++$i) {
    $graphpinator->run($request);
}

$timeFinish = \microtime(true);

echo $timeFinish - $timeStart;
