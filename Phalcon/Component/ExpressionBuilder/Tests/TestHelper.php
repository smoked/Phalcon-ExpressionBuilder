<?php

$loader = new \Phalcon\Loader();

$loader->registerNamespaces(array(
    'Phalcon'               => realpath(__DIR__ . '/../../../../Phalcon/')
));

$loader->register();