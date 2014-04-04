<?php

// The Stripe keys must be defined in wp-config.php
if ( !defined('STRIPE_PUBLIC') ) {
	define( 'STRIPE_PUBLIC', 'invalid-key' );
	define( 'STRIPE_SECRET', '' );
}

class StripeForm {
	public static function init() {
		wp_enqueue_script( 'stripe', "https://checkout.stripe.com/checkout.js" );
		wp_localize_script( 'stripe', 'StripeForm', array(
			'url' => admin_url('admin-ajax.php'),
			'action' => 'stripe_charge',
			'nonce' => wp_create_nonce('stripe-nonce'),
			'key' => STRIPE_PUBLIC
		) );
	}

	public static function coupon() {
		require_once( 'lib/Stripe.php' );
		Stripe::setApiKey( STRIPE_SECRET );

		// Verify required data
		if ( empty( $_REQUEST[ 'coupon' ] ) ) {
			self::fail( 'Please enter a coupon code.');
		}

		try {
			$coupon = Stripe_Coupon::retrieve( $_REQUEST[ 'coupon' ] );
		} catch( Exception $e ) {
			self::fail( 'Invalid coupon id' );
		}

		echo json_encode(array(
			'percent_off' => $coupon->percent_off,
			'amount_off' => $coupon->amount_off
		));
		exit();
	}

	public static function charge() {
		require_once( 'lib/Stripe.php' );
		Stripe::setApiKey( STRIPE_SECRET );

		// Verify required data
		if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( $_REQUEST['nonce'], 'stripe-nonce' ) ) {
			self::fail( 'Invalid Nonce' );
		}
		if ( empty( $_REQUEST['email'] ) ) {
			self::fail( 'Looks like you forgot to provide us with your email, ' .
				'please go back and make sure you provide it.' );
		}
		if ( empty( $_REQUEST['token'] ) ) {
			self::fail( 'It seems we didn\'t get your payment information, ' .
				'please go back and try again.' );
		}

		$token = $_REQUEST['token'];
		$email = $_REQUEST['email'];
		$name = $_REQUEST['firstName'] . ' ' . $_REQUEST['lastName'];
		$plan = $_REQUEST['planId'];
		$address = $_REQUEST['address'];
		$coupon = empty( $_REQUEST['coupon'] ) ? null : $_REQUEST['coupon'];

		// Create Stripe subscription
		$customer = Stripe_Customer::create( array(
			'email' => $email,
			'card' => $token,
			'description' => stripcslashes($name)
		) );
		$subscription = array(
			'plan' => $plan
		);
		if ( !empty( $coupon ) ) {
			$subscription['coupon'] = $coupon;
		}
		$charge = $customer->updateSubscription( $subscription );
		$stripeId = $customer->id;

		// Create or update WordPress user
		$user = get_user_by( 'email', $email );
		if ( $user ) {
			$user_id = $user->ID;
		} else {
			$user_id = wp_insert_user(array(
				'user_pass' => wp_generate_password(),
				'user_login' => $email,
				'user_email' => $email,
				'first_name' => $_REQUEST['firstName'],
				'last_name' => $_REQUEST['lastName'],
				'display_name' => $name
			) );
		}

		// Store Stripe ID and gift choices on WordPress user
		$customerInfo = array(
			'stripe_id' => $stripeId,
			'address' => $address
		);
		if ( !empty( $_REQUEST[ 'tshirt' ] ) ) {
			$customerInfo[ 'tshirt' ] = $_REQUEST[ 'tshirt' ];
		}
		if ( !empty( $_REQUEST[ 'hoodie' ] ) ) {
			$customerInfo[ 'hoodie' ] = $_REQUEST[ 'hoodie' ];
		}
		if ( !empty( $_REQUEST[ 'bag' ] ) ) {
			$customerInfo[ 'bag' ] = $_REQUEST[ 'bag' ];
		}
		foreach( $customerInfo as $key => $value ) {
			add_user_meta( $user_id, $key, $value );
		}

		$mailBody = "$name ($email) has signed up for $plan.\n";
		$mailBody .= "WordPress user id: $user_id\n";
		if ( !empty( $coupon ) ) {
			$mailBody .=  "coupon: $coupon\n";
		}
		foreach( $customerInfo as $key => $value ) {
			$mailBody .= "$key: $value\n";
		}

		mail( 'membership@jquery.org', "New Foundation Member ($name)", $mailBody );

		echo $user_id;
		exit();
	}

	public static function fail( $message ) {
		header( 'HTTP/1.0 400 Bad Request' );
		echo $message;
		exit();
	}
}

add_action( 'init', array( 'StripeForm', 'init' ) );
add_action( 'wp_ajax_nopriv_stripe_charge', array( 'StripeForm', 'charge' ) );
add_action( 'wp_ajax_stripe_charge', array( 'StripeForm', 'charge' ) );
add_action( 'wp_ajax_nopriv_stripe_coupon', array( 'StripeForm', 'coupon' ) );
add_action( 'wp_ajax_stripe_coupon', array( 'StripeForm', 'coupon' ) );
