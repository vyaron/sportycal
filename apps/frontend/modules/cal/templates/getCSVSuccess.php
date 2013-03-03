<?php
header('Content-Type: text/plain');
header('Content-Disposition: attachment; filename="sportYcal_'.substr(str_replace(' ', '_', $cal->getName()),0,20).'.csv"');
echo $cal->getAsCSV();

?>
