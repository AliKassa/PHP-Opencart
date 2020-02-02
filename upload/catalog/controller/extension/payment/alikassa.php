<?php

class ControllerExtensionPaymentAlikassa extends Controller {
	public function index() {
		$data['button_confirm'] = $this->language->get( 'button_confirm' );
		$this->load->model( 'checkout/order' );
		$this->load->model( 'extension/payment/alikassa' );
		$this->load->language( 'extension/payment/alikassa' );

		$order_info = $this->model_checkout_order->getOrder( $this->session->data['order_id'] );

		$data['action'] = 'https://sci.alikassa.com/payment';
		$m_curr         = strtoupper( $order_info['currency_code'] );
		$orderId        = $this->session->data['order_id'];

		if ( $orderId < 10 ) {
			$orderIdPs = '00' . $orderId;
		} elseif ( $orderId < 100 ) {
			$orderIdPs = '0' . $orderId;
		} else {
			$orderIdPs = $orderId;
		}

		$data['params'] = array(
			'merchantUuid' => $this->config->get( 'payment_alikassa_merchant' ),
			'orderId'      => $orderIdPs,
			'amount'       => $this->currency->format( $order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false ),
			'currency'     => ( $m_curr == 'RUR' ) ? 'RUB' : $m_curr,
			'desc'         => str_replace( '#ORDER_ID#', $orderId, $this->language->get( 'text_description' ) ),
		);
		if ( $this->config->get( 'payment_alikassa_variants' ) ) {
			list( $code, $title ) = explode( ':', $this->config->get( 'payment_alikassa_variants' ) );
			$data['params']['payWayVia'] = $code;
		}


		if ( $this->config->get( 'payment_alikassa_security_sign' ) ) {
			$data['params']['sign'] = $this->model_extension_payment_alikassa->getSign( $data['params'], $this->config->get( 'payment_alikassa_security' ), $this->config->get( 'payment_alikassa_security_type' ) );
		}

		$this->model_checkout_order->addOrderHistory( $orderId, $this->config->get( 'payment_alikassa_order_wait_id' ) );

		return $this->load->view( 'extension/payment/alikassa', $data );
	}

	public function status( $withResponse = true ) {
		$request = $this->request->post;
		$status  = false;

		if ( isset( $request["action"] ) && $request["action"] == 'deposit' && isset( $request["id"] ) ) {
			$this->load->model( 'extension/payment/alikassa' );
			$this->load->model( 'checkout/order' );

			if ( $request['sign'] != $this->model_extension_payment_alikassa->getSign( $request, $this->config->get( 'payment_alikassa_security' ), $this->config->get( 'payment_alikassa_security_type' ) )
			     || ! ( $order = $this->model_checkout_order->getOrder( $request['orderId'] ) ) ) {
				$this->log->write( 'Alikassa(' . $request['orderId'] . '): no valid sign(' . $request['sign'] . ') or not found order' );

				$status = false;
			} else {
				if ( $request['payStatus'] == 'success' ) {
					if ( $order['order_status_id'] !== $this->config->get( 'payment_alikassa_order_success_id' ) ) {
						$this->model_checkout_order->addOrderHistory( $request['orderId'], $this->config->get( 'payment_alikassa_order_success_id' ) );
					}
					$status = true;
				} else {
					if ( $order['order_status_id'] !== $this->config->get( 'payment_alikassa_order_fail_id' ) ) {
						$this->model_checkout_order->addOrderHistory( $request['orderId'], $this->config->get( 'payment_alikassa_order_fail_id' ) );
					}
					$this->log->write( 'Alikassa(' . $request['orderId'] . '): payment fail' );
				}
			}
		}

		if ( $withResponse ) {
			$this->response->setOutput( 'OK' );
		}

		return $status;
	}

	public function fail() {
		$this->status();
		$this->response->redirect( $this->url->link( 'checkout/checkout' ) );

		return true;
	}

	public function success() {
		$this->load->model( 'extension/payment/alikassa' );
		$request = $this->request->post;

		if ( isset( $request['status'] ) && $request['status'] == 'success' && $request['sign'] == $this->model_extension_payment_alikassa->getSign( $request, $this->config->get( 'payment_alikassa_security' ), $this->config->get( 'payment_alikassa_security_type' ) ) ) {
			$this->response->redirect( $this->url->link( 'checkout/success' ) );
		} else {
			$this->response->redirect( $this->url->link( 'checkout/checkout' ) );
		}

		return true;
	}
}