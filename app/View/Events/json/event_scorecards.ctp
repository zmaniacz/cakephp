<?php
	$data = array();
    foreach($response['Game'] as $game) {
        foreach($game['Red_Team']['Scorecard'] as $scorecard) {
            $scorecard['game_name'] = $game['game_name'];
            $scorecard['game_id'] = $game['id'];
            $scorecard['winner'] = $game['winner'];
            $data[] = $scorecard;
        }
        foreach($game['Green_Team']['Scorecard'] as $scorecard) {
            $scorecard['game_name'] = $game['game_name'];
            $scorecard['game_id'] = $game['id'];
            $scorecard['winner'] = $game['winner'];
            $data[] = $scorecard;
        }
    }
	echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>