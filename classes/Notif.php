<?php

class Notif{
	private $pusher;

	private function __constructor($data){
		$this->pusher = new Pusher\Pusher(
			'6afb55a56f2b4a235c4b',
			'272e2a850479a8abd2aa',
			'606760',
			array(
				'cluster' => 'ap1',
				'useTLS' => true
			)
		);
		$this->pusher->trigger('notif', 'update', $data);
	}
}

?>