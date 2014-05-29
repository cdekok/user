#!/usr/bin/env php
<?php
chdir(dirname(__DIR__.'/../../'));
require 'vendor/autoload.php';

$application = new \Symfony\Component\Console\Application();
$application->add(new Cept\User\Cli\UserAdd);
$application->run();