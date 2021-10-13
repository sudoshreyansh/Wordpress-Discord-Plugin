<?php

/* 
Plugin Name: Live Members Counter For Discord
Description: Plugin to include discord servers' live member count
Author: Shreyansh Jain
Version: 1.0.1
Requires at least: 5.2
License: GPLv2 or later
*/

if ( !defined('ABSPATH') ) {
    die(0);
}

require_once(plugin_dir_path(__FILE__) . 'class.dlmc-server-counter.php');
require_once(plugin_dir_path(__FILE__) . 'class.dlmc-counter.php');
require_once(plugin_dir_path(__FILE__) . 'class.dlmc-cron.php');
require_once(plugin_dir_path(__FILE__) . 'options-dlmc.php');

function dlmc_init() {
    add_shortcode('dlmc_counter', 'dlmc_counter_shortcode');
}

function dlmc_admin_enqueue() {
    wp_enqueue_style('dlmc_admin_style', plugin_dir_url(__FILE__).'admin-style.css');
    wp_enqueue_script('dlmc_admin_script', plugin_dir_url(__FILE__).'admin-script.js', array(), false, true);
}

function dlmc_settings_menu_init() {
    add_submenu_page(
        'options-general.php',
        'Discord Live Member Count Settings',
        'Members Counter',
        'manage_options',
        'dlmc',
        'dlmc_settings_content'
    );
}

function dlmc_settings_init () {
    register_setting('dlmc', 'dlmc_bot_token');
    register_setting('dlmc', 'dlmc_members_count');
    add_settings_section('default', '', null, 'dlmc');
    add_settings_field('dlmc_members_count', 'Current Members Count', 'dlmc_members_count_content', 'dlmc', 'default', array(
        'label_for' => 'dlmc_members_count',
    ));
    add_settings_field('dlmc_bot_token', 'Discord Bot Token', 'dlmc_bot_token_content', 'dlmc', 'default', array(
        'label_for' => 'dlmc_bot_token',
    ));
}

function dlmc_update_cron() {
    if ( isset($_POST['dlmc_activate_cron']) ) {
        if ( !get_option('dlmc_bot_token') ) {
            add_settings_error('dlmc_cron_messages', 'dlmc_cron_messages', 'Please enter a valid Discord Bot Token', 'error');
            return;
        }

        if ( $_POST['dlmc_activate_cron'] == 'start' ) {
            if ( DLMC_Cron::start_cron() ) {
                add_settings_error('dlmc_cron_messages', 'dlmc_cron_messages', 'Live Member Counter is running successfully', 'success');
            } else {
                add_settings_error('dlmc_cron_messages', 'dlmc_cron_messages', 'Live Member Counter could not be started', 'error');
            }
        } else if ( $_POST['dlmc_activate_cron'] == 'stop' ) {
            if ( DLMC_Cron::stop_cron() ) {
                add_settings_error('dlmc_cron_messages', 'dlmc_cron_messages', 'Live Member Counter has been stopped successfully', 'success');
            } else {
                add_settings_error('dlmc_cron_messages', 'dlmc_cron_messages', 'Live Member Counter could not be stopped', 'error');
            }
        }
    }
}

function dlmc_deactivate_plugin () {
    DLMC_Cron::stop_cron();
    delete_option('dlmc_bot_token');
    delete_option('dlmc_members_count');
}

function dlmc_check_errors() {
    $errors = get_option('dlmc_error_logs');
    if ( $errors ) {
        $errors = json_decode($errors);
        add_settings_error('dlmc_cron_messages', 'dlmc_cron_messages', 'Looks like there was an issue in updating the members count<br>Error code: '.$errors->code.', error message: '.$errors->message,  'error');
    }
}

function dlmc_counter_shortcode_defaults() {
    return array(
        'color' => '#000000',
        'weight' => 'bold',
        'size' => '16px'
    );
}

function dlmc_counter_shortcode($attributes = array(), $content = null, $tag = '') {
    $count = DLMC_Counter::get_saved_members_count();
    $attributes = shortcode_atts(dlmc_counter_shortcode_defaults(), $attributes, $tag);
    $content = "<span style=\"color:{$attributes['color']}; font-weight:{$attributes['weight']}; font-size:{$attributes['size']}\">$count</span>";
    echo $content;
}

DLMC_Counter::init();
DLMC_Cron::init();

register_deactivation_hook(__FILE__, 'dlmc_deactivate_plugin');

add_action('init', 'dlmc_init');
add_action('admin_enqueue_scripts', 'dlmc_admin_enqueue');
add_action('admin_menu', 'dlmc_settings_menu_init');
add_action('admin_init', 'dlmc_settings_init');

add_action('wp_ajax_dlm_counter', array('DLMC_Counter', 'ajax_get_saved_members_count'));
add_action('wp_ajax_nopriv_dlm_counter', array('DLMC_Counter', 'ajax_get_saved_members_count'));

add_action('wp_ajax_dlm_counter_update', array('DLMC_Counter', 'refresh_members_count'));
add_action('wp_ajax_nopriv_dlm_counter_update', array('DLMC_Counter', 'refresh_members_count'));

add_filter('cron_schedules', array('DLMC_Cron', 'cron_interval'));
add_action(DLMC_Cron::CRON_HOOK, array('DLMC_Counter', 'refresh_members_count'));

?>