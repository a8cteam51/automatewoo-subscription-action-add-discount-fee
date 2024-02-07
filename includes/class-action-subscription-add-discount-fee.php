<?php
namespace to51\AW_Action;

use AutomateWoo\Action;
use AutomateWoo\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Action_Subscription_Add_Discount_Fee extends Action {

	public $required_data_items = array( 'subscription' );

	protected function load_admin_details() {
		$this->title       = __( 'Add Discount or Fee', 'automatewoo' );
		$this->group       = __( 'Subscription', 'automatewoo' );
		$this->description = __( 'Adds a discount or a fee to a subscription. Note: due to the nature of how actions are scheduled, this won\'t work to provide discounts at initial checkout. For that, please use a coupon or other solution.', 'automatewoo' );
	}

	public function load_fields() {
		$this->add_field( $this->get_name_field() );
		$this->add_field( $this->get_discount_fee_options() );
		$this->add_field( $this->get_amount_field() );
	}

	protected function get_name_field() {
		$name = new Fields\Text();
		$name->set_name( 'name' );
		$name->set_title( __( 'Name', 'automatewoo' ) );
		$name->set_required();
		return $name;
	}

	protected function get_discount_fee_options() {
		$discount_fee = new Fields\Select();
		$discount_fee->set_name( 'discount_or_fee' );
		$discount_fee->set_title( __( 'Discount or Fee', 'automatewoo' ) );
		$discount_fee->set_required();
		$discount_fee->set_options(
			array(
				'fee'      => __( 'Fee', 'automatewoo' ),
				'discount' => __( 'Discount', 'automatewoo' ),
			)
		);
		return $discount_fee;
	}

	protected function get_amount_field() {
		$amount = new Fields\Price();
		$amount->set_name( 'amount' );
		$amount->set_title( __( 'Amount', 'automatewoo' ) );
		$amount->set_placeholder( __( 'E.g. 10.00', 'automatewoo' ) );
		$amount->set_required();
		$amount->set_variable_validation();
		return $amount;
	}

	public function run() {
		$subscription = $this->workflow->data_layer()->get_subscription();

		if ( ! $subscription ) {
			return;
		}

		$discount_or_fee = $this->get_option( 'discount_or_fee' );
		$amount          = $this->get_option( 'amount' );
		$name            = $this->get_option( 'name' );

		// Convert amount to negative if it's a discount
		$total_amount = $discount_or_fee === 'discount' ? -$amount : $amount;

		$item_fee = new \WC_Order_Item_Fee();
		$item_fee->set_name( $name );
		$item_fee->set_amount( $amount );
		$item_fee->set_tax_class( '' );
		$item_fee->set_tax_status( 'none' );
		$item_fee->set_total( $total_amount );

		$subscription->add_item( $item_fee );
		$subscription->calculate_totals();
		$subscription->save();

		$this->add_subscription_note( $subscription, $discount_or_fee, $total_amount );
	}

	protected function add_subscription_note( $subscription, $discount_or_fee, $total_amount ) {
		// translators: "discount" or "fee", and dollar amount
		$note = sprintf(
			__( 'AutomateWoo - Added a %1$s of %2$s.', 'automatewoo' ),
			$discount_or_fee,
			wc_price( $total_amount )
		);
		$subscription->add_order_note( $note );
	}
}
