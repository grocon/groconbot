#!/usr/bin/php
<?php
require_once 'TGGroconBot.php';
require_once 'BittrexExchange.php';
require_once 'PoloniexExchange.php';
require_once 'LiquiExchange.php';
require_once 'config.php';

$bot = new TGGroconBot(BOT_TGTOKEN);
$btex = new BittrexExchange;

$exchanges = array(new BittrexExchange, new PoloniexExchange, new LiquiExchange);

while(true) {
	foreach($exchanges as $ex) {
		$newmarkets = $ex->getNewMarkets();
		$deletedmarkets = $ex->getDeletedMarkets();

		if(count($newmarkets) || count($deletedmarkets)) {
			echo $ex->getName().' : '.count($newmarkets)." marchés ajoutés et ".count($deletedmarkets)." supprimés...\n";
			$message = $ex->getName();
			if(count($newmarkets)) {
				$message .= "\nmarchés ajoutés : \n";
				foreach($newmarkets as $k=>$v) {
					$message.=$k."\n";
				}
			}
			if(count($deletedmarkets)) {
				$message .= "\nmarchés supprimés : \n";
				foreach($deletedmarkets as $k=>$v) {
					$message.=$k."\n";
				}
			}
			$bot->broadcast($message);
		}
	}
	$bot->update();
	sleep(30);
}


