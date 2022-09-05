<?php

/**
 * Plugin Name: Data Tables Generator by Supsystic
 * Plugin URI: http://supsystic.com
 * Description: Create and manage beautiful data tables with custom design. No HTML knowledge is required
 * Version: 1.10.20
 * Author: supsystic.com
 * Author URI: http://supsystic.com
 * Text Domain: supsystic_tables
 * Domain Path: /app/langs
 */

include dirname(__FILE__) . '/app/SupsysticTables.php';

if (!defined('SUPSYSTIC_STB_DEBUG')) {
	define('SUPSYSTIC_STB_DEBUG', false);
}
if (!defined('SUPSYSTIC_TABLES_SHORTCODE_NAME')) {
    define('SUPSYSTIC_TABLES_SHORTCODE_NAME', 'supsystic-tables');
}
if (!defined('SUPSYSTIC_TABLES_PART_SHORTCODE_NAME')) {
	define('SUPSYSTIC_TABLES_PART_SHORTCODE_NAME', SUPSYSTIC_TABLES_SHORTCODE_NAME.'-part');
}
if (!defined('SUPSYSTIC_TABLES_CELL_SHORTCODE_NAME')) {
	define('SUPSYSTIC_TABLES_CELL_SHORTCODE_NAME', SUPSYSTIC_TABLES_SHORTCODE_NAME.'-cell-full');
}
if (!defined('SUPSYSTIC_TABLES_VALUE_SHORTCODE_NAME')) {
	define('SUPSYSTIC_TABLES_VALUE_SHORTCODE_NAME', SUPSYSTIC_TABLES_SHORTCODE_NAME.'-cell');
}
if (!defined('DTGS_PLUGIN_URL')) {
	define('DTGS_PLUGIN_URL', plugin_dir_url( __FILE__ ));
}
if (!defined('DTGS_PLUGIN_ADMIN_URL')) {
	define('DTGS_PLUGIN_ADMIN_URL', admin_url());
}

$supsysticTables = new SupsysticTables();
$supsysticTables->run();

$supsysticTables->activate(__FILE__);
$supsysticTables->deactivate(__FILE__);

if (!function_exists('supsystic_tables_get')) {
    function supsystic_tables_get($id)
    {
        return do_shortcode(sprintf('[%s id="%d"]', SUPSYSTIC_TABLES_SHORTCODE_NAME, (int)$id));
    }
}
