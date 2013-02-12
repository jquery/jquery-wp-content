<?php

class StripeForm {
	public static function init() {
		wp_enqueue_script( 'stripe', get_template_directory_uri() . '/js/stripe.js' );
		wp_localize_script( 'stripe', 'StripeForm', array(
			'url' => admin_url('admin-ajax.php'),
			'action' => 'stripe_charge',
			'nonce' => wp_create_nonce('stripe-nonce'),
			'key' => STRIPE_PUBLIC
		) );
	}

	public static function charge() {
		require_once( 'lib/Stripe.php' );
		Stripe::setApiKey( STRIPE_SECRET );

		header( 'Content-Type: application/json' );

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
		$customer = Stripe_Customer::create( array(
			'email' => $email,
			'card' => $token
		) );
		$charge = $customer->updateSubscription( array(
			'plan' => $_REQUEST['planId']
		) );

		// TODO: create user
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
