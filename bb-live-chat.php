<?php

/*
Plugin Name:       ByteBunch Live Chat
Plugin URI:          https://bytebunch.com/plugin
Description:        This is wordpress plugin which is created for the wordpress site to live chat with admin and other staff members.
Version:               1.0.0
Requires at least: 5.2
Requires PHP:      8.1.2
Author:               ByteBunch
Author URI:        https://bytebunch.com
License:               GPL v2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:       bb_live_chat
Domain Path:       /languages
*/

// If anyone can access this file directory show error.
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

// Define CONSTANT for plugin directory path.
if ( ! defined( 'PLUGIN_DIR_PATH' ) ) {
    define( 'PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
}

// Define CONSTANT for plugin directory url.
if ( ! defined( 'PLUGIN_DIR_URL' ) ) {
    define( 'PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
}

// Define CONSTANT for plugin directory.
if ( ! defined( 'PLUGIN' ) ) {
    define( 'PLUGIN', plugin_basename( __FILE__ ) );
}

class BBLiveChat {

    // function for register all actions and filters.
    function register() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

        add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );

        add_filter( "plugin_action_links_" . PLUGIN, array( $this, 'settings_link' ) );

        add_filter( "the_content", array( $this, 'bb_live_chat_html' ) );

        add_action( "admin_init", array( $this, 'bb_register_settings' ) );
    }

    // enqueue styles and scripts
    function enqueue() {
        wp_enqueue_style( 'bb-style', PLUGIN_DIR_URL . 'assets/css/style.css' );
        wp_enqueue_script( 'bb-script', PLUGIN_DIR_URL . 'assets/js/script.js' );
    }

    // function for adding settings link in plugin.
    function settings_link( $links ) {
        $settings_link = '<a href="admin.php?page=bb_live_chat">Settings</a>';
        array_push( $links, $settings_link );
        return $links;
    }

    // function for adding menu and sub menu pages
    function add_admin_pages() {
        add_menu_page( 'ByteBunch Live Chat','BB Live Chat', 'manage_options', 'bb_live_chat', array( $this, 'admin_index' ), 'dashicons-store', 110  );
        add_submenu_page( 'bb_live_chat', 'Live Chat','Live Chat', 'manage_options', 'live_chat', array( $this, 'live_admin_index' ), 110  );
        add_submenu_page( 'bb_live_chat', 'Email Log','Email Log', 'manage_options', 'email_log', array( $this, 'email_admin_index' ), 110  );
    }

    // callback function for menu page.
    function admin_index() {
        require_once PLUGIN_DIR_PATH . 'templates/admin.php';
    }

    // callback function for sub menu page 1.
    function live_admin_index() {
       echo "<h1>Live Chat</h1>";
    }

    // callback function for sub menu page 2.
    function email_admin_index() {
       echo "<h1>Email Log</h1>";
    }

    // function for writting plugin html
    function bb_live_chat_html( $content ) {
        $chat_button_number = get_option( 'bb_chat_label' );
        $bb_live_chat_div = '<div class="bb_live_chat_div">';
        $bb_live_chat_link = '<a href="https://web.whatsapp.com/send?phone='.$chat_button_number.'" class="bb_live_chat_link"></a>';
        $bb_live_chat_div_end = '</div>';

        $content .= $bb_live_chat_div;
        $content .= $bb_live_chat_link;
        $content .= $bb_live_chat_div_end;

        return $content;
    }

    // Register settings, sections & fields.
    function bb_register_settings() {

        register_setting( 'bb_register_setting', 'bb_chat_label' );

        add_settings_section( 
            'bb_register_setting_section', 
            'Settings Manager', 
            function () {
                echo '<p>Enter the phone number in the field.</p>';
            }, 
            'bb_register_setting' 
        );

        add_settings_field( 
            'bb_register_setting_field', 
            'Enter Phone Number', 
            function () {
                $setting = get_option('bb_chat_label');
                echo '<input type="text" name="bb_chat_label" value="'.$setting.'">';
            }, 
            'bb_register_setting', 
            'bb_register_setting_section' 
        );

    }

}
$bbLiveChat = new BBLiveChat();
$bbLiveChat->register();