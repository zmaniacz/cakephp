<?php
	$data = array();
	
	foreach ($response as $key => $value) {
		$data[] = array(
			'name' => $this->Html->link($value['player_name'], array('controller' => 'Players', 'action' => 'view', $key), array('class' => 'btn btn-block btn-info')),
			'avg_avg_mvp' => round($value['avg_avg_mvp'],2),
			'avg_avg_acc' => round($value['avg_avg_acc']*100,2),
			'total_games' => round($value['total_games'],2),
			'commander_avg_mvp' => round($value['Commander']['avg_mvp'],2),
			'commander_avg_acc' => round($value['Commander']['avg_acc']*100,2),
			'commander_games_played' => round($value['Commander']['games_played'],2),
			'heavy_avg_mvp' => round($value['Heavy Weapons']['avg_mvp'],2),
			'heavy_avg_acc'	=> round($value['Heavy Weapons']['avg_acc']*100,2),
			'heavy_games_played' => round($value['Heavy Weapons']['games_played'],2),
			'scout_avg_mvp' => round($value['Scout']['avg_mvp'],2),
			'scout_avg_acc' => round($value['Scout']['avg_acc']*100,2),
			'scout_games_played' => round($value['Scout']['games_played'],2),
			'ammo_avg_mvp' => round($value['Ammo Carrier']['avg_mvp'],2),
			'ammo_avg_acc' => round($value['Ammo Carrier']['avg_acc']*100,2),
			'ammo_games_played' => round($value['Ammo Carrier']['games_played'],2),
			'medic_avg_mvp' => round($value['Medic']['avg_mvp'],2),
			'medic_avg_acc' => round($value['Medic']['avg_acc']*100,2),
			'medic_games_played' => round($value['Medic']['games_played'],2)
		);
	}
	
	echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>