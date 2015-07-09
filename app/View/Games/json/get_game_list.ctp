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
		
		$red_score = $game['Game']['red_score']+$game['Game']['red_adj'];
		$green_score = $game['Game']['green_score']+$game['Game']['green_adj'];
		
		$red_team = ($game['Game']['red_team_id'] == null) ? "Red Team : $red_score" : $this->Html->link("{$game['Red_Team']['name']} : $red_score", array('controller' => 'teams', 'action' => 'view', $game['Game']['red_team_id']), array('class' => 'btn btn-block btn-danger'));
		$green_team = ($game['Game']['green_team_id'] == null) ? "Green Team : $green_score" : $this->Html->link("{$game['Green_Team']['name']} : $green_score", array('controller' => 'teams', 'action' => 'view', $game['Game']['green_team_id']), array('class' => 'btn btn-block btn-success'));
		
		if($game['Game']['winner'] == 'Red') {
			$winner =  $red_team;
			$loser = $green_team;
			$options = array('class' => 'btn btn-danger btn-block');
		} else {
			$winner = $green_team;
			$loser = $red_team;
			$options = array('class' => 'btn btn-success btn-block');
		}
		
		if($game['Game']['pdf_id'] == null) {
			$pdf = "";
		} else {
			$pdf = "<a href=\"/pdf/".$game['Game']['pdf_id'].".pdf\" class=\"btn btn-info btn-block\" target=\"_blank\">PDF</a>";
		}
		
		$data[] = array(
			'id' => $game['Game']['id'],
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