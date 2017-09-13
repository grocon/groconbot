<?php


class Exchange {
	protected $markets;
	protected $updatedmarkets;
	protected $tLastUpdate = 0;

	function __construct() {
		$this->updateMarketList();
		$this->tLastUpdate = 0;
	}

	protected function updateMarketList() {
		if((time() - $this->tLastUpdate) > 10) {
			$this->markets = $this->updatedmarkets;
			$this->updatedmarkets = $this->_updateMarketList();
		}
	}

	protected function _updateMarketList() {}

	public function getNewMarkets() {
		$this->updateMarketList();
		echo count($this->markets)." dans markets et ".count($this->updatedmarkets)." dans updatedmarkets...\n";
		$newmarkets = array_diff_key($this->updatedmarkets, $this->markets);
		return $newmarkets;
	}

	public function getDeletedMarkets() {
		$this->updateMarketList();
		$newmarkets = array_diff_key($this->markets, $this->updatedmarkets);
		return $newmarkets;
	}

	public function getName() {
		return 'implement getName() method';
	}
}






