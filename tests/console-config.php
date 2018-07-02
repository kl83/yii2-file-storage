<?php

$config = require 'config.php';
unset($config['components']['user']);
unset($config['components']['request']);
unset($config['modules']);

return $config;
