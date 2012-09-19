<?php

require '_SplClassLoader.php';

$loader = new SplClassLoader('There4', realpath(__DIR__ . '/../src'));
$loader->register();

/* End of file _autoloader.php */