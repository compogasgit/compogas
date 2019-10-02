<?php

// Lightning wrapper for LIO CRON Job when only PHP Cron Jobs are allowed

set_time_limit(15*60);

$path = dirname(__FILE__);

$out = '';
exec($path."/lio ".str_replace("catalog/controller/extension/lightning/", "", $path), $out);
foreach($out as $l) echo "$l<br/>";