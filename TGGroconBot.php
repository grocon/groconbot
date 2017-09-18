<?php
class TGGroconBot {
	private $curl;
	private $tgUrl;
	private $db;
	private $chats = array();
	private $lastupdateid = 0;

	function __construct($token) {
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($this->curl, CURLOPT_TIMEOUT, 5);
// 		curl_setopt($this->curl, CURLOPT_VERBOSE, true);

		$this->tgUrl = 'https://api.telegram.org/bot'.urlencode($token).'/';

		$this->db = new PDO('sqlite:groconbot.sqlite');
		$this->db->exec("CREATE TABLE if not exists chats (id INTEGER PRIMARY KEY, username varchar(200))");
	}

	private function postData($url, $data, $contenttype) {
		curl_setopt($this->curl, CURLOPT_URL, $url);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-type: '.$contenttype));

		return curl_exec($this->curl);
	}

	public function sendTextMessage($recipient, $text) {
// 		echo "\n======== sendTextMessage\n";
		$endpointUrl = $this->tgUrl.'sendMessage';
		$data = new stdClass;
		$data->chat_id = $recipient;
		$data->text = $text;

		$result = $this->postData($endpointUrl, json_encode($data), 'application/json');
		$result = json_decode($result);

		return $result;
	}

	private function getUpdates() {
// 		echo "\n======== getUpdates\n";
		$endpointUrl = $this->tgUrl.'getUpdates';
		$data = new stdClass;
		$data->offset = $this->lastupdateid + 1;

		$result = $this->postData($endpointUrl, json_encode($data), 'application/json');
		$result = json_decode($result);

		return $result;
	}

	public function update() {
// 		echo "\n======== update\n";
		$updates = $this->getUpdates();
		foreach($updates->result as $x) {
			$this->lastupdateid = $x->update_id;
			if($x->channel_post) $event = $x->channel_post;
			elseif($x->message) $event = $x->message;
			else continue;

			$this->chats[$event->chat->id] = $event->chat->first_name;
			$st = $this->db->prepare('insert into chats(id, username) values(?, ?)');
			$st->execute(array($event->chat->id, $event->chat->first_name));

			// /prout command
			if(strpos($event->text, '/prout') === 0) {
				$this->sendTextMessage($event->chat->id, 'prout...');
			}
		}
	}

	public function broadcast($text) {
		$this->update();
		foreach($this->chats as $chatid=>$user) {
			$this->sendTextMessage($chatid, $text);
			usleep(33000);
		}
	}
}


// $bot = new TGGroconBot(BOT_TGTOKEN);
// $bot->update();



