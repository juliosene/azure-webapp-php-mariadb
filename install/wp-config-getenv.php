<?php
$conn_str = getenv("MYSQLCONNSTR_mariadb");

$database = preg_replace("/^.*Database=(.+?);.*$/", "\\1", $conn_str);

$host = preg_replace("/^.*Server=(.+?);.*$/", "\\1", $conn_str);
$username = preg_replace("/^.*Uid=(.+?);.*$/", "\\1", $conn_str);
$password = preg_replace("/^.*Pwd=(.+?)$/", "\\1", $conn_str); 

/** O nome do banco de dados do WordPress */
define('DB_NAME', $database);

/** Usuário do banco de dados MySQL */
define('DB_USER', $username);

/** Senha do banco de dados MySQL */
define('DB_PASSWORD', $password);

/** nome do host do MySQL */
define('DB_HOST', $host);
?>
