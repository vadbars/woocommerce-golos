<?php
/**
 * WC_Steem_Order_Handler
 *
 * @package WooCommerce Steem
 * @category Class Handler
 * @author ReCrypto
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class WC_Steem_Order_Handler {

	public static function init() {
		$instance = __CLASS__;

		add_action('wc_order_steem_status', array($instance, 'default_order_steem_status'));

		add_action('woocommerce_view_order', array($instance, 'payment_details'), 5);
		add_action('woocommerce_thankyou', array($instance, 'payment_details'), 5);
	}

	public static function default_order_steem_status($status) {
		return $status ? $status : 'pending';
	}

	public static function payment_details($order_id) {
		$order = wc_get_order($order_id);

		if ($order->payment_method != 'wc_steem') 
			return; ?>
		<section class="woocommerce-steem-order-payment-details">
		<h3>* Для завершения заказа перейдите в <a href="https://steemit.com/@ВАШ_АККАУНТ/transfers" title="Доступен по адресу https://steemit.com/@ВАШ_АККАУНТ/transfers" target=_blank><b>Кошелек на Steemit.com</b></a> и переведите пользователю <strong>chainstore</strong> токены на сумму <strong><?php echo wc_order_get_steem_amount($order_id); ?> <?php echo wc_order_get_steem_amount_currency($order_id); ?></strong>.</h3>
		<h3>Не забудьте указать в поле Memo (Примечание) значение <strong><?php echo wc_order_get_steem_memo($order_id); ?></strong></h3>
		<h3>* Либо воспользуйтесь <a href="https://v2.steemconnect.com/sign/transfer?to=chainstore&amount=<?php echo wc_order_get_steem_amount($order_id); ?>%20<?php echo wc_order_get_steem_amount_currency($order_id); ?>&memo=<?php echo wc_order_get_steem_memo($order_id); ?> target=_blank"><b>прямой ссылкой</b></a> (авторизация через Steemconnect).</h3>
			
		<h3>После оплаты вам на почту поступит письмо с информацией о дальнейших действиях.</h3>
		<p>&nbsp;</p>
			<h2 class="woocommerce-steem-order-payment-details__title"><?php _e( 'Golos Payment details', 'wc-steem' ); ?></h2>

			<table class="woocommerce-table woocommerce-table--steem-order-payment-details shop_table steem_order_payment_details">
				<tbody>
					<tr>
						<th><?php _e('Payee', 'wc-steem'); ?></th>
						<td><?php echo wc_order_get_steem_payee($order_id); ?></td>
					</tr>
					<tr>
						<th><?php _e('Memo', 'wc-steem'); ?></th>
						<td><?php echo wc_order_get_steem_memo($order_id); ?></td>
					</tr>
					<tr>
						<th><?php _e('Amount', 'wc-steem'); ?></th>
						<td><?php echo wc_order_get_steem_amount($order_id); ?></td>
					</tr>
					<tr>
						<th><?php _e('Currency', 'wc-steem'); ?></th>
						<td><?php echo wc_order_get_steem_amount_currency($order_id); ?></td>
					</tr>
					<tr>
						<th><?php _e('Status', 'wc-steem'); ?></th>
						<td><?php echo wc_order_get_steem_status($order_id); ?></td>
					</tr>
				</tbody>
			</table>

						<!-- p>Please don't forge to include the <strong>"MEMO"</strong> when making a transfer for this transaction to Steem.</p -->
			<p>&nbsp;</p>
			<quote><h3>Для завершения заказа перейдите, пожалуйста, на страницу <a href="https://golos.io/@ВАШ_АККАУНТ/transfers" title="Доступна по адресу https://golos.io/@ВАШ_АККАУНТ/transfers" target=_blank>Кошелек</a> на Golos.io и переведите пользователю <strong>chainstore</strong> указанную выше сумму в токенах GOLOS или GBG.</h3>
			<h4>Не забудьте указать значение <strong>Memo</strong> (указано выше) в соответствующем поле перевода!</h4>
			<h3>После оплаты вам на email поступит сообщение о дальнейших действиях.</h3>
			</quote>
			<p>&nbsp;</p>

			<?php do_action( 'wc_steem_order_payment_details_after_table', $order ); ?>

		</section>

		<?php if ($transfer = get_post_meta($order->id, '_wc_steem_transaction_transfer', true)) : ?>
		<section class="woocommerce-steem-order-transaction-details">

			<h2 class="woocommerce-steem-order-transaction-details__title"><?php _e( 'Golos Transfer details', 'wc-steem' ); ?></h2>

			<table class="woocommerce-table woocommerce-table--steem-order-transaction-details shop_table steem_order_payment_details">
				<tbody>
					<tr>
						<th><?php _e('Golos Transaction ID', 'wc-steem'); ?></th>
						<td><?php echo $transfer['tx_id']; ?></td>
					</tr>
					<tr>
						<th><?php _e('Golos Transfer ID', 'wc-steem'); ?></th>
						<td><?php echo $transfer['ID']; ?></td>
					</tr>
					<tr>
						<th><?php _e('Payor', 'wc-steem'); ?></th>
						<td><?php echo $transfer['from']; ?></td>
					</tr>
					<tr>
						<th><?php _e('Paid on', 'wc-steem'); ?></th>
						<td><?php printf('%s on %s', date('F j, Y', $transfer['timestamp']), date('g:i A', $transfer['timestamp'])); ?></td>
					</tr>
				</tbody>
			</table>

			<?php do_action( 'wc_steem_order_transaction_details_after_table', $order ); ?>

		</section>
		<?php endif; ?>

		<?php
	}
}

WC_Steem_Order_Handler::init();
