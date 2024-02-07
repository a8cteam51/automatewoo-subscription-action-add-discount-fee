<?php
/*
 * Plugin Name: AutomateWoo Subscription Action - Add Discount or Fee
 * Plugin URI:  https://github.com/a8cteam51/automatewoo-subscription-action-add-discount-fee
 * Description: Extends the functionality of AutomateWoo with a custom action which allows you to add a discount or fee line item
 * Version:     1.0.0
 * Author:      WP Special Projects
 * Author URI:  https://wpspecialprojects.wordpress.com/
 * License:     GPL v2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once dirname( __FILE__ ) . '/class-automatewoo-subscription-add-discount-fee.php';
