<?php
require_once __DIR__ . '/../vendor/symfony/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use \Symfony\Component\ClassLoader\UniversalClassLoader;

$classLoader = new UniversalClassLoader();
$classLoader->registerNamespace('Symfony', __DIR__ . DIRECTORY_SEPARATOR . '../vendor/symfony');
$classLoader->registerNamespace('Zend', __DIR__ . DIRECTORY_SEPARATOR . '../vendor/zend/library');
$classLoader->registerNamespace('Icone', __DIR__ . DIRECTORY_SEPARATOR . '..');
$classLoader->register();
?>