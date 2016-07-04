<?php
$azuretest = include 'master.php';
// echo("<pre>");
// print_r($azuretest);
// echo("</pre>");

if($azuretest['dbcache.engine']=='memcached')
    $azuretest['dbcache.memcached.servers'] = array("%IP_MEMCACHESHIM_PORT%");
if ($azuretest['pgcache.memcached.servers'])
    $azuretest['pgcache.memcached.servers'] = array("%IP_MEMCACHESHIM_PORT%");
if ($azuretest['minify.memcached.servers'])
    $azuretest['minify.memcached.servers'] = array("%IP_MEMCACHESHIM_PORT%");
if($azuretest['fragmentcache.memcached.servers'])
    $azuretest['fragmentcache.memcached.servers'] = array("%IP_MEMCACHESHIM_PORT%");
if ($azuretest['objectcache.memcached.servers'])
    $azuretest['objectcache.memcached.servers'] = array("%IP_MEMCACHESHIM_PORT%");


$azuretest = var_export($azuretest, TRUE);
$azuretest = str_replace("'%IP_MEMCACHESHIM_PORT%'", "'127.0.0.1:' . getenv(\"MEMCACHESHIM_PORT\")", $azuretest);

$newfile = fopen("master.php", "w+");

fwrite($newfile,"<?php\n\nreturn ");
fwrite($newfile,$azuretest);

fwrite($newfile,';');
fclose($newfile);
?>
