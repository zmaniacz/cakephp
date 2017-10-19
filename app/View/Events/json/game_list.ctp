<?php
	$data = array();
	foreach ($response['Game'] as $game) {
		$game['link'] = html_entity_decode($this->Html->url(array(
			'controller' => 'games',
			'action' => 'view',
			$game['id']
		)));
		$data[] = $game;
	}
	echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>