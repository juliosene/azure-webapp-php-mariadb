<?php
$azuretest = include 'master.php';
print_r($azuretest);

$azuretest['objectcache.memcached.servers'] = array("'127.0.0.1:' . getenv('MEMCACHESHIM_PORT')");
$azuretest['dbcache.memcached.servers'] = array("'127.0.0.1:' . getenv('MEMCACHESHIM_PORT')");
$azuretest['pgcache.memcached.servers'] = array("'127.0.0.1:' . getenv('MEMCACHESHIM_PORT')");
$azuretest['minify.memcached.servers'] = array("'127.0.0.1:' . getenv('MEMCACHESHIM_PORT')");
$azuretest['fragmentcache.memcached.servers'] = array("'127.0.0.1:' . getenv('MEMCACHESHIM_PORT')");

$newfile = fopen("master.php", "w+");

fwrite($newfile,"<?php\n\nretun ");
fwrite($newfile,var_export($azuretest, TRUE));
fwrite($newfile,"\n?>");
fclose($newfile);
//print_r ($azuretest['objectcache.memcached.servers']);
//echo ("<br/><br/>");
//var_dump ($azureMencached);
?>
