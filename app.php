<?php
require('constants.php');
require('src/Commissions.php');


$commission = new Commissions();


echo $commission->calculateCommissions($argv[1]);
