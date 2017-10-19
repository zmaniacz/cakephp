<?php
	foreach ($data as &$scorecard) {
		$scorecard['Game']['link'] = html_entity_decode($this->Html->url(array(
			'controller' => 'games',
			'action' => 'view',
			$scorecard['Game']['id']
		)));
		$scorecard['Player']['link'] = html_entity_decode($this->Html->url(array(
			'controller' => 'players',
			'action' => 'view',
			$scorecard['Player']['id']
		)));
	}
	echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>