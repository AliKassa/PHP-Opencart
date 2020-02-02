<?php

class ControllerExtensionPaymentAlikassa extends Controller {
	private $error = array();

	public function index() {
		$this->load->language( 'extension/payment/alikassa' );
		$this->document->setTitle( $this->language->get( 'heading_title' ) );
		$this->load->model( 'setting/setting' );

		if ( ( $this->request->server['REQUEST_METHOD'] == 'POST' ) && $this->validate() ) {
			$this->model_setting_setting->editSetting( 'payment_alikassa', $this->request->post );
			$this->session->data['success'] = $this->language->get( 'text_success' );
			$this->response->redirect( $this->url->link( 'marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true ) );
		}

		$data['heading_title'] = $this->language->get( 'heading_title' );

		$data['text_all_zones'] = $this->language->get( 'text_all_zones' );
		$data['text_payment']   = $this->language->get( 'text_payment' );
		$data['text_success']   = $this->language->get( 'text_success' );
		$data['text_edit']      = $this->language->get( 'text_edit' );
		$data['text_pay']       = $this->language->get( 'text_pay' );
		$data['text_enabled']   = $this->language->get( 'text_enabled' );
		$data['text_disabled']  = $this->language->get( 'text_disabled' );

		$data['entry_merchant']      = $this->language->get( 'entry_merchant' );
		$data['entry_security']      = $this->language->get( 'entry_security' );
		$data['entry_security_type'] = $this->language->get( 'entry_security_type' );
		$data['entry_security_sign'] = $this->language->get( 'entry_security_sign' );
		$data['entry_order_wait']    = $this->language->get( 'entry_order_wait' );
		$data['entry_order_success'] = $this->language->get( 'entry_order_success' );
		$data['entry_order_fail']    = $this->language->get( 'entry_order_fail' );
		$data['entry_geo_zone']      = $this->language->get( 'entry_geo_zone' );
		$data['entry_status']        = $this->language->get( 'entry_status' );
		$data['entry_sort_order']    = $this->language->get( 'entry_sort_order' );
		$data['entry_log']           = $this->language->get( 'entry_log' );
		$data['entry_admin_email']   = $this->language->get( 'entry_admin_email' );
		$data['entry_status_url']    = $this->language->get( 'entry_status_url' );
		$data['entry_success_url']   = $this->language->get( 'entry_success_url' );
		$data['entry_fail_url']      = $this->language->get( 'entry_fail_url' );

		$data['error_url']        = $this->language->get( 'error_url' );
		$data['error_permission'] = $this->language->get( 'error_permission' );
		$data['error_merchant']   = $this->language->get( 'error_merchant' );
		$data['error_security']   = $this->language->get( 'error_security' );

		$data['help_url']         = $this->language->get( 'help_url' );
		$data['help_merchant']    = $this->language->get( 'help_merchant' );
		$data['help_security']    = $this->language->get( 'help_security' );
		$data['help_log']         = $this->language->get( 'help_log' );
		$data['help_admin_email'] = $this->language->get( 'help_admin_email' );

		$data['button_save']   = $this->language->get( 'button_save' );
		$data['button_cancel'] = $this->language->get( 'button_cancel' );

		if ( isset( $this->error['warning'] ) ) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if ( isset( $this->error['url'] ) ) {
			$data['error_url'] = $this->error['url'];
		} else {
			$data['error_url'] = '';
		}

		if ( isset( $this->error['merchant'] ) ) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}

		if ( isset( $this->error['security'] ) ) {
			$data['error_security'] = $this->error['security'];
		} else {
			$data['error_security'] = '';
		}

		if ( isset( $this->error['type'] ) ) {
			$data['error_type'] = $this->error['type'];
		} else {
			$data['error_type'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get( 'text_home' ),
			'href' => $this->url->link( 'common/dashboard', 'user_token=' . $this->session->data['user_token'], true )
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get( 'text_payment' ),
			'href' => $this->url->link( 'marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true )
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get( 'heading_title' ),
			'href' => $this->url->link( 'extension/payment/alikassa', 'user_token=' . $this->session->data['user_token'], true )
		);

		$data['action'] = $this->url->link( 'extension/payment/alikassa', 'user_token=' . $this->session->data['user_token'], true );

		$data['cancel'] = $this->url->link( 'marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true );

		if ( isset( $this->request->post['payment_alikassa_merchant'] ) ) {
			$data['payment_alikassa_merchant'] = $this->request->post['payment_alikassa_merchant'];
		} else {
			$data['payment_alikassa_merchant'] = $this->config->get( 'payment_alikassa_merchant' );
		}

		if ( isset( $this->request->post['payment_alikassa_security'] ) ) {
			$data['payment_alikassa_security'] = $this->request->post['payment_alikassa_security'];
		} else {
			$data['payment_alikassa_security'] = $this->config->get( 'payment_alikassa_security' );
		}

		if ( isset( $this->request->post['payment_alikassa_security_sign'] ) ) {
			$data['payment_alikassa_security_sign'] = $this->request->post['payment_alikassa_security_sign'];
		} else {
			if ( ! $this->config->get( 'payment_alikassa_security_sign' ) ) {
				$data['payment_alikassa_security_sign'] = 0;
			} else {
				$data['payment_alikassa_security_sign'] = $this->config->get( 'payment_alikassa_security_sign' );
			}
		}

		if ( isset( $this->request->post['payment_alikassa_security_type'] ) ) {
			$data['payment_alikassa_security_type'] = $this->request->post['payment_alikassa_security_type'];
		} else {
			if ( ! $this->config->get( 'payment_alikassa_security_type' ) ) {
				$data['payment_alikassa_security_type'] = 'md5';
			} else {
				$data['payment_alikassa_security_type'] = $this->config->get( 'payment_alikassa_security_type' );
			}
		}

		if ( isset( $this->request->post['payment_alikassa_variants'] ) ) {
			$data['payment_alikassa_variants'] = $this->request->post['payment_alikassa_variants'];
		} else {
			$data['payment_alikassa_variants'] = $this->config->get( 'payment_alikassa_variants' );
		}

		if ( isset( $this->request->post['payment_alikassa_order_wait_id'] ) ) {
			$data['payment_alikassa_order_wait_id'] = $this->request->post['payment_alikassa_order_wait_id'];
		} else {
			if ( ! $this->config->get( 'payment_alikassa_order_wait_id' ) ) {
				$data['payment_alikassa_order_wait_id'] = 1;
			} else {
				$data['payment_alikassa_order_wait_id'] = $this->config->get( 'payment_alikassa_order_wait_id' );
			}
		}
		if ( isset( $this->request->post['payment_alikassa_status'] ) ) {
			$data['payment_alikassa_status'] = $this->request->post['payment_alikassa_status'];
		} else {
			if ( ! $this->config->get( 'payment_alikassa_status' ) ) {
				$data['payment_alikassa_status'] = 0;
			} else {
				$data['payment_alikassa_status'] = $this->config->get( 'payment_alikassa_status' );
			}
		}
		if ( isset( $this->request->post['payment_alikassa_order_success_id'] ) ) {
			$data['payment_alikassa_order_success_id'] = $this->request->post['payment_alikassa_order_success_id'];
		} else {
			if ( ! $this->config->get( 'payment_alikassa_order_success_id' ) ) {
				$data['payment_alikassa_order_success_id'] = 5;
			} else {
				$data['payment_alikassa_order_success_id'] = $this->config->get( 'payment_alikassa_order_success_id' );
			}
		}

		if ( isset( $this->request->post['payment_alikassa_order_fail_id'] ) ) {
			$data['payment_alikassa_order_fail_id'] = $this->request->post['payment_alikassa_order_fail_id'];
		} else {
			if ( ! $this->config->get( 'payment_alikassa_order_fail_id' ) ) {
				$data['payment_alikassa_order_fail_id'] = 10;
			} else {
				$data['payment_alikassa_order_fail_id'] = $this->config->get( 'payment_alikassa_order_fail_id' );
			}
		}

		$this->load->model( 'localisation/order_status' );

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if ( isset( $this->request->post['payment_alikassa_geo_zone_id'] ) ) {
			$data['payment_alikassa_geo_zone_id'] = $this->request->post['payment_alikassa_geo_zone_id'];
		} else {
			$data['payment_alikassa_geo_zone_id'] = $this->config->get( 'payment_alikassa_geo_zone_id' );
		}

		$this->load->model( 'localisation/geo_zone' );

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if ( isset( $this->request->post['payment_alikassa_sort_order'] ) ) {
			$data['payment_alikassa_sort_order'] = $this->request->post['payment_alikassa_sort_order'];
		} else {
			$data['payment_alikassa_sort_order'] = $this->config->get( 'payment_alikassa_sort_order' );
		}

		if ( isset( $this->request->post['payment_alikassa_log_value'] ) ) {
			$data['payment_alikassa_log_value'] = $this->request->post['payment_alikassa_log_value'];
		} else {
			$data['payment_alikassa_log_value'] = $this->config->get( 'payment_alikassa_log_value' );
		}

		if ( isset( $this->request->post['payment_alikassa_admin_email'] ) ) {
			$data['payment_alikassa_admin_email'] = $this->request->post['payment_alikassa_admin_email'];
		} else {
			$data['payment_alikassa_admin_email'] = $this->config->get( 'payment_alikassa_admin_email' );
		}

		$data['siteData']   = false;
		$data['paysystems'] = json_decode( file_get_contents( 'https://api.alikassa.com/v1/paysystem' ), true );
		if ( $this->config->get( 'payment_alikassa_merchant' ) && $this->config->get( 'payment_alikassa_security' ) ) {
			$merchant = $this->config->get( 'payment_alikassa_merchant' );
			$security = $this->config->get( 'payment_alikassa_security' );

			$auth     = base64_encode( $merchant . ':' . $this->getSign( array(), $security, $this->config->get( 'payment_alikassa_security_type' ) ) );
			$siteData = @file_get_contents( 'https://api.alikassa.com/v1/site', false, stream_context_create( [
				"http" => [
					'method' => 'POST',
					"header" => "Authorization: Basic $auth",
				]
			] ) );
			if ( ! empty( $siteData ) ) {
				$data['siteData'] = json_decode( $siteData, true );
			}
		}


		$data['header']      = $this->load->controller( 'common/header' );
		$data['column_left'] = $this->load->controller( 'common/column_left' );
		$data['footer']      = $this->load->controller( 'common/footer' );

		$this->response->setOutput( $this->load->view( 'extension/payment/alikassa', $data ) );
	}

	private function getSign( $params, $secret, $type ) {
		ksort( $params, SORT_STRING );
		$params[]   = $secret;
		$signString = implode( ':', $params );

		return base64_encode( hash( ( $type == 'sha256' ? 'sha256' : 'md5' ), $signString, true ) );
	}

	protected function validate() {

		if ( ! $this->user->hasPermission( 'modify', 'extension/payment/alikassa' ) ) {
			$this->error['warning'] = $this->language->get( 'error_permission' );
		}

		if ( ! $this->request->post['payment_alikassa_merchant'] ) {
			$this->error['merchant'] = $this->language->get( 'error_merchant' );
		}

		if ( ! $this->request->post['payment_alikassa_security'] ) {
			$this->error['security'] = $this->language->get( 'error_security' );
		}

		return ! $this->error;
	}
}