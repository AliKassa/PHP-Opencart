<?php

class ModelExtensionPaymentAlikassa extends Model {
	public function getMethod( $address, $total ) {
		$this->load->language( 'extension/payment/alikassa' );

		$query = $this->db->query( "SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int) $this->config->get( 'payment_alikassa_geo_zone_id' ) . "' AND country_id = '" . (int) $address['country_id'] . "' AND (zone_id = '" . (int) $address['zone_id'] . "' OR zone_id = '0')" );

		if ( ! $this->config->get( 'payment_alikassa_geo_zone_id' ) ) {
			$status = true;
		} elseif ( $query->num_rows ) {
			$status = true;
		} else {
			$status = false;
		}

		$currencies = array(
			'RUB',
			'RUR',
			'USD',
			'EUR'
		);

		if ( ! in_array( strtoupper( $this->session->data['currency'] ), $currencies ) ) {
			$status = false;
		}

		$method_data = array();

		if ( $status ) {
			if ( $this->config->get( 'payment_alikassa_variants' ) ) {
				list( $code, $title ) = explode( ':', $this->config->get( 'payment_alikassa_variants' ) );
			} else {
				$title = $this->language->get( 'text_title' );
			}
			$method_data = array(
				'code'       => 'alikassa',
				'title'      => $title,
				'terms'      => '',
				'sort_order' => $this->config->get( 'payment_alikassa_sort_order' )
			);
		}

		return $method_data;
	}

	public function getSign( $params, $secret, $type ) {
		if ( isset( $params['sign'] ) ) {
			unset( $params['sign'] );
		}
		ksort( $params, SORT_STRING );
		$params[]   = $secret;
		$signString = implode( ':', $params );

		return base64_encode( hash( ( $type == 'sha256' ? 'sha256' : 'md5' ), $signString, true ) );
	}
}

?>