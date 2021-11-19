<?php

use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Vitagon\SerializerBug\dto\UpdateRequest;

require 'vendor/autoload.php';

$data = <<<EOF
{"users": {"1": {"id":"452"}, "2": {"id":"2123"}}}
EOF;

$extractor = new PropertyInfoExtractor([], [new PhpDocExtractor(), new ReflectionExtractor()]);

$classMetadataFactory = new ClassMetadataFactory(new YamlFileLoader(__DIR__ . "/src/mappings.yaml"));
$objectNormalizer = new ObjectNormalizer($classMetadataFactory, null, null, null);
$serializer = new Serializer([
    new CustomNormalizer(),
    $objectNormalizer,
], [
    new JsonEncoder()
]);

$requestDto = $serializer->deserialize($data, UpdateRequest::class, 'json', [
    AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => true,
]);

var_dump($requestDto);
