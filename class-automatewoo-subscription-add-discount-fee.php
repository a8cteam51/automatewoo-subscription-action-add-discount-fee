<?php
namespace to51\AW_Action;

class AutomateWoo_Subscription_Add_Discount_Fee {
	public static function init() {
		add_filter( 'automatewoo/actions', array( __CLASS__, 'register_action' ) );
	}

	public static function register_action( $actions ) {
		require_once __DIR__ . '/includes/class-action-subscription-add-discount-fee.php';

		$actions['to51_subscription_add_discount_fee'] = Action_Subscription_Add_Discount_Fee::class;
		return $actions;
	}
}

AutomateWoo_Subscription_Add_Discount_Fee::init();
