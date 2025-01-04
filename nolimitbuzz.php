<?php

/**
 * Plugin Name: Nolimitbuzz Core
 * Plugin URI:  https://nolimitbuzz.com
 * Author:      Nolimitbuzz
 * Author URI:  https://nolimitbuzz.com
 * Description: This plugin extends the functionality of the Nolimitbuzz theme.
 * Version:     0.1.0
 * License:     GPL-2.0+
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: nolimitbuzz
 */

//check for security
if (!defined('ABSPATH')) {
    exit;
}

//define constants
define('NO_LIMIT_BUZZ_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('NO_LIMIT_BUZZ_PLUGIN_URL', plugin_dir_url(__FILE__));
//version
define('NO_LIMIT_BUZZ_PLUGIN_VERSION', time());

//load the plugin
require_once NO_LIMIT_BUZZ_PLUGIN_DIR . 'includes/nolimitbuzz.class.php';

//initialize the plugin
Nolimitbuzz\Includes\Nolimitbuzz::get_instance();
