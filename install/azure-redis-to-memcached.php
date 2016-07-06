<?php
/*
   Plugin Name: Azure Redis to Memcached
   Plugin URI: http://wordpress.org/extend/plugins/azure-redis-to-memcached/
   Version: 0.1
   Author: Julio Franca
   Description: Enable a web app in Azure App Service to connect to Redis Cache via the Memcache protocol. The instructions to create a Redis to Memcached connector are <a TARGET="_blank" href="https://azure.microsoft.com/en-us/documentation/articles/web-sites-connect-to-redis-using-memcache-protocol/">HERE</a>. This plugin requires W3 Total Cache.
   Text Domain: azure-redis-to-memcached
   License: GPLv3
  */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!W3TC) die('W3 Total Cache isnt installed');

/* define the w3tc_saving_options callback */

function action_azure_redis_to_memcached ($new_config) {
    // print_r($new_config);
    $saved_config = include W3TC_CONFIG_DIR .'/master.php';
    if($saved_config['dbcache.engine']=='memcached')
        $saved_config['dbcache.memcached.servers'] = array("%IP_MEMCACHESHIM_PORT%");
    if ($saved_config['pgcache.engine']=='memcached')
        $saved_config['pgcache.memcached.servers'] = array("%IP_MEMCACHESHIM_PORT%");
    if ($saved_config['minify.engine']=='memcached')
        $saved_config['minify.memcached.servers'] = array("%IP_MEMCACHESHIM_PORT%");
    if($saved_config['fragmentcache.engine']=='memcached')
        $saved_config['fragmentcache.memcached.servers'] = array("%IP_MEMCACHESHIM_PORT%");
    if ($saved_config['objectcache.engine']=='memcached')
        $saved_config['objectcache.memcached.servers'] = array("%IP_MEMCACHESHIM_PORT%");
    $saved_config = var_export($saved_config, TRUE);
    $saved_config = str_replace("'%IP_MEMCACHESHIM_PORT%'", "'127.0.0.1:' . getenv(\"MEMCACHESHIM_PORT\")", $saved_config);
    $newfile = fopen(W3TC_CONFIG_DIR ."/master.php", "w+");
    fwrite($newfile,"<?php\n\nreturn ");
    fwrite($newfile,$saved_config);
    fwrite($newfile,';');
    fclose($newfile);
}
// add the action 
add_action( 'w3tc_saved_options', 'azure_redis_to_memcached', 10, 3 ); 
//add_action( 'w3tc_after_save_options', 'azure_redis_to_memcached', 10, 3 );
