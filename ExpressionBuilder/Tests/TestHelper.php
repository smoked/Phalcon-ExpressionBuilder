<?php

$loader = new \Phalcon\Loader();

$loader->registerNamespaces(array(
    'Phalcon'               => __DIR__ . '/../../../Phalcon'
));

$loader->register();