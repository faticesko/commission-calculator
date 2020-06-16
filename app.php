<?php
require('constants.php');

require_once './vendor/autoload.php';

$commission = new \Commissions\Commissions();


echo $commission->calculateCommissions($argv[1]);