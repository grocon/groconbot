<?php
require_once('Exchange.php');

class BittrexExchange extends Exchange {
	protected function _updateMarketList() {
		$result = array();
		$markets = json_decode(file_get_contents('https://bittrex.com/api/v1.1/public/getmarkets'));
		foreach($markets->result as $market) {
			if($market->IsActive) {
// 				if(rand(0, 10) != 1)
				$result[$market->MarketName] = 1;
			}
		}
		$this->tLastUpdate = time();
		return $result;
	}

	public function getName() {
		return 'BITTREX';
	}
}

