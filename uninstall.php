<?php 

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

global $wpdb,$table_prefix;
$c_nochex_table = $table_prefix.'custom_nochex';

$qry = "DROP TABLE $c_nochex_table";
$wpdb->query($qry);

 ?>