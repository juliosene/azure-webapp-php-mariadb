<?php
/*
   Plugin Name: Azure Redis
   Plugin URI: http://wordpress.org/extend/plugins/azure-redis/
   Version: 0.1
   Author: Julio Franca
   Description: Enable a web app in Azure App Service to connect to Redis Cache via the Memcache protocol
   Text Domain: azure-redis-to-memcached
   License: GPLv3
  */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/* define the w3tc_saving_options callback */

function action_azure_redis($new_config, $new_config_admin) {
   // print_r($new_config);
    $azuretest = include W3TC_CONFIG_DIR .'/master.php';
    if($azuretest['dbcache.engine']=='memcached')
        $azuretest['dbcache.memcached.servers'] = array("%IP_MEMCACHESHIM_PORT%");
    if ($azuretest['pgcache.engine']=='memcached')
        $azuretest['pgcache.memcached.servers'] = array("%IP_MEMCACHESHIM_PORT%");
    if ($azuretest['minify.engine']=='memcached')
        $azuretest['minify.memcached.servers'] = array("%IP_MEMCACHESHIM_PORT%");
    if($azuretest['fragmentcache.engine']=='memcached')
        $azuretest['fragmentcache.memcached.servers'] = array("%IP_MEMCACHESHIM_PORT%");
    if ($azuretest['objectcache.engine']=='memcached')
        $azuretest['objectcache.memcached.servers'] = array("%IP_MEMCACHESHIM_PORT%");
    $azuretest = var_export($azuretest, TRUE);
    $azuretest = str_replace("'%IP_MEMCACHESHIM_PORT%'", "'127.0.0.1:' . getenv(\"MEMCACHESHIM_PORT\")", $azuretest);
    $newfile = fopen(W3TC_CONFIG_DIR ."/master.php", "w+");
    fwrite($newfile,"<?php\n\nreturn ");
    fwrite($newfile,$azuretest);
    fwrite($newfile,';');
    fclose($newfile);
}
// add the action 
add_action( 'w3tc_saved_options', 'action_azure_redis', 10, 3 ); 
