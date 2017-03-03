<?php
    foreach($response as &$player) {
        $player['non_resup_total_medic_hits'] = 0;
        $player['non_resup_total_games_played'] = 0;
        
        if(isset($player['Commander'])) {
            $player['non_resup_total_medic_hits'] += $player['Commander']['medic_hits'];
            $player['non_resup_total_games_played'] += $player['Commander']['games_played'];
        }

        if(isset($player['Heavy Weapons'])) {
            $player['non_resup_total_medic_hits'] += $player['Heavy Weapons']['medic_hits'];
            $player['non_resup_total_games_played'] += $player['Heavy Weapons']['games_played'];
        }

        if(isset($player['Scout'])) {
            $player['non_resup_total_medic_hits'] += $player['Scout']['medic_hits'];
            $player['non_resup_total_games_played'] += $player['Scout']['games_played'];
        }
    }
    
    $data = array_values($response);
    echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>