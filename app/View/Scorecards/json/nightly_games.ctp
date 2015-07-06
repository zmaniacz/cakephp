<?php
	$data = array();
	
	foreach($games as $game) {
		if(!empty($game['Match']['id'])) {
			$game_name = 'R'.$game['Match']['Round']['round'].' M'.$game['Match']['match'].' G'.$game['Game']['league_game'];
			if($game['Match']['Round']['is_finals'])
				$game_name .= '(Finals)';
		} else {
			$game_name = $game['Game']['game_name'];
		}
		
		$red_team = ($game['Game']['red_team_id'] == null) ? 'Red Team' : $game['Red_Team']['name'];
		$green_team = ($game['Game']['green_team_id'] == null) ? 'Green Team' : $game['Green_Team']['name'];
		
		if($game['Game']['winner'] == 'Red') {
			$winner =  $red_team.": ".($game['Game']['red_score']+$game['Game']['red_adj']);
			$loser = $green_team.": ".($game['Game']['green_score']+$game['Game']['green_adj']);
			$options = array('class' => 'btn btn-danger btn-block');
		} else {
			$winner = $green_team.": ".($game['Game']['green_score']+$game['Game']['green_adj']);
			$loser = $red_team.": ".($game['Game']['red_score']+$game['Game']['red_adj']);
			$options = array('class' => 'btn btn-success btn-block');
		}
		
		if($game['Game']['pdf_id'] == null) {
			$pdf = "";
		} else {
			$pdf = "<a href=\"/pdf/".$game['Game']['pdf_id'].".pdf\" class=\"btn btn-info btn-block\" target=\"_blank\">PDF</a>";
		}
		
		$data[] = array(
			'game_name' => $this->Html->link($game_name, array('controller' => 'games', 'action' => 'view', $game['Game']['id']), $options),
			'game_datetime' => $game['Game']['game_datetime'],
			'winner' => $winner,
			'loser' => $loser,
			'winner_color' => $game['Game']['winner'],
			'pdf' => $pdf
		);
	}
	echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>