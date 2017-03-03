<?php
	$data = array();
	foreach ($response as $event) {
		$data[] = array(
            'id' => $event['Event']['id'],
			'name' => $event['Event']['name'],
			'description' => $event['Event']['description'],
            'is_comp' => $event['Event']['is_comp'],
			'type' => $event['Event']['type'],
			'last_gametime' => $event['Event']['last_gametime'],
			'center_id' => $event['Center']['id'],
            'center_short_name' => $event['Center']['short_name'],
			'center_name' => $event['Center']['name'],
			'center' => $event['Center']['name']
		);
	}
	echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>