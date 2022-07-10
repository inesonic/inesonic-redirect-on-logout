<?php
/**
 * Plugin Name: Inesonic Redirect On Logout
 * Plugin URI: http://www.inesonic.com
 * Description: A small proprietary plug-in that handles automatic redirection on logout.
 * Version: 1.0.0
 * Author: Inesonic, LLC
 * Author URI: http://www.inesonic.com
 */

/***********************************************************************************************************************
 * Copyright 2022, Inesonic, LLC.
 *
 * GNU Public License, Version 3:
 *   This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 *   License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any
 *   later version.
 *
 *   This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 *   warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 *   details.
 *
 *   You should have received a copy of the GNU General Public License along with this program.  If not, see
 *   <https://www.gnu.org/licenses/>.
 ***********************************************************************************************************************
 */

/* Inesonic WordPress redirect on logout plug-in. */
class InesonicRedirectOnLogout {
    const VERSION = '1.0.0';
    const SLUG    = 'inesonic-redirect-on-logout';
    const NAME    = 'Inesonic Redirect On Logout';
    const AUTHOR  = 'Inesonic, LLC';
    const PREFIX  = 'InesonicRedirectOnLogout';

    private static $instance;  /* Plug-in instance */
    public static  $dir = '';  /* Plug-in directory */
    public static  $url = '';  /* Plug-in URL */

    /* Method that is called to initialize a single instance of the plug-in */
    public static function instance() {
        if (!isset(self::$instance)                                &&
            !(self::$instance instanceof InesonicRedirectOnLogout)    ) {
            self::$instance = new InesonicRedirectOnLogout();
            self::$dir      = plugin_dir_path(__FILE__);
            self::$url      = plugin_dir_url(__FILE__);

            spl_autoload_register(array(self::$instance, 'autoloader'));
        }
    }


    /* This method ties the plug-in into the rest of the WordPress framework by adding hooks where needed. */
    public function __construct() {
        add_action('wp_logout', array($this, 'auto_redirect_after_logout'));
    }


    /* Optional methods for convenience. */
    public function autoloader($class_name) {
        if (!class_exists($class_name) and (FALSE !== strpos($class_name, self::PREFIX))) {
            $class_name = str_replace(self::PREFIX, '', $class_name);
            $classes_dir = realpath(plugin_dir_path(__FILE__)) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
            $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';

            if (file_exists($classes_dir . $class_file)) {
                require_once $classes_dir . $class_file;
            }
        }
    }


    /* Function that forces logout redirection to home page */
    function auto_redirect_after_logout() {
        wp_redirect(home_url());
        exit();
    }
}

/* Function that returns the main plug-in instance. */
function inesonic_redirect_on_logout() {
    return InesonicRedirectOnLogout::instance();
}

inesonic_redirect_on_logout();
