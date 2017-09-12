<?php
require_once('Exchange.php');

class BittrexExchange extends Exchange {
	protected function _updateMarketList() {
		$result = array();
		$markets = json_decode(file_get_contents('https://bittrex.com/api/v1.1/public/getmarkets'));
		foreach($markets->result as $market) {
			$result[$market->MarketName] = 1;
		}
		$this->tLastUpdate = time();
		return $result;
	}
}

