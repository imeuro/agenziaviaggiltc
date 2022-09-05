<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
/**
 * Class WC_Vivawallet_DirectPay
 *
 * @extends WC_Vivawallet_Apm
 *
 * @class   WC_Vivawallet_DirectPay
 * @package VivaWalletForWooCommerce
 */
class WC_Vivawallet_DirectPay extends  WC_Vivawallet_Apm {

	/**
	 * Payment method id
	 *
	 * @var string
	 */
	public $id;

	/**
	 * Method Title
	 *
	 * @var string
	 */
	public $method_title;

	/**
	 * Viva wallet id
	 *
	 * @var int
	 */
	public $vivawallet_id;

	/**
	 * Icon
	 *
	 * @var int
	 */
	public $icon;

	/**
	 * Constructor.
	 */
	public function __construct() {

		$this->id           = 'vivawallet-directpay';
		$this->method_title = __( 'Viva Wallet Standard Checkout - DirectPay Payment Gateway', 'viva-wallet-for-woocommerce' );

		$this->vivawallet_id = 16;

		$this->icon = apply_filters( 'woocommerce_vivawallet_directpay_icon', WC_Vivawallet_Helper::VW_CHECKOUT_DIRECTPAY_LOGO_URL );

		parent::__construct();

	}

}

