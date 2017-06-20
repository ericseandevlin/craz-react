<?php
/*
 * Plugin Name: Quick Empty Trash
 * Version: 1.0
 * Plugin URI: http://www.hughlashbrooke.com/
 * Description: Adds an 'empty trash' button to all post type tables - saves you from needing to load the trash list before being able to empty it.
 * Author: Hugh Lashbrooke
 * Author URI: http://www.hughlashbrooke.com/
 * Requires at least: 3.0
 * Tested up to: 4.0
 *
 * @package WordPress
 * @author Hugh Lashbrooke
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require_once( 'classes/class-quick-empty-trash.php' );

global $qet;
$qet = new Quick_Empty_Trash( __FILE__ );