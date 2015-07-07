<ul>
	<?php
		$mvp = 0;
		switch($score['Scorecard']['position']) {
			case "Ammo Carrier":
				$mvp += max(ceil(($score['Scorecard']['score']-3999)/1000),0);
				break;
			case "Commander":
				$mvp += max(ceil(($score['Scorecard']['score']-10999)/1000),0);
				break;
			case "Heavy Weapons":
				$mvp += max(ceil(($score['Scorecard']['score']-7999)/1000),0);
				break;
			case "Medic":
				$mvp += max(ceil(($score['Scorecard']['score']-2999)/1000),0);
				break;
			case "Scout":
				$mvp += max(ceil(($score['Scorecard']['score']-6999)/1000),0);
				break;
		}
		echo "<li>Position Score Bonus: $mvp</li>";
		
		if($score['Scorecard']['position'] == 'Medic' && $score['Scorecard']['score'] >= 3000) {
			echo "<li>Medic Score Bonus: 1</li>";
		}

		echo "<li>Accuracy: ".round($score['Scorecard']['accuracy'] * 10,1)."</li>";
		
		echo "<li>Times Missiled: ".($score['Scorecard']['times_missiled']*-1)."</li>";
		
		$mvp = 0;
		$lvp = 0;
		switch($score['Scorecard']['position']) {
			case "Commander":
				$mvp = $score['Scorecard']['missiled_opponent'];
				$lvp = $score['Scorecard']['missiled_team'] * -3;
				break;
			case "Heavy Weapons":
				$mvp = $score['Scorecard']['missiled_opponent'] * 2;
				$lvp = $score['Scorecard']['missiled_team'] * -3;
				break;
		}
		echo "<li>Missiled Opponent: $mvp</li>";
		echo "<li>Missiled Team: $lvp</li>";
		
		if($score['Scorecard']['position'] == 'Commander') {
			echo "<li>Nukes Detonated: {$score['Scorecard']['nukes_detonated']}</li>";
		}
		
		if($score['Scorecard']['position'] == 'Commander' && $score['Scorecard']['nukes_activated'] > $score['Scorecard']['nukes_detonated']) {
			echo "<li>Your Nukes Canceled: ".(($score['Scorecard']['nukes_activated'] - $score['Scorecard']['nukes_detonated']) * -3)."</li>";
		}
		
		echo "<li>Nukes Canceled: ".($score['Scorecard']['nukes_canceled']*3)."</li>";
		
		echo "<li>Own Nukes Canceled: ".($score['Scorecard']['own_nuke_cancels'] * -3)."</li>";
		
		echo "<li>Medic Hits: {$score['Scorecard']['medic_hits']}</li>";
		
		echo "<li>Own Medic Hits: ".($score['Scorecard']['own_medic_hits']*-1)."</li>";
		
		if($score['Scorecard']['position'] == 'Scout') {
			echo "<li>Rapid Fire Activated: ".($score['Scorecard']['scout_rapid']*.5)."</li>";
			echo "<li>Shot 3-Hits: ".(floor(($score['Scorecard']['shot_3hit']/6)*100) / 100)."</li>";
		}
		
		if($score['Scorecard']['position'] == 'Ammo Carrier') {
			echo "<li>Ammo Boosts: ".($score['Scorecard']['ammo_boost'] * 3)."</li>";
		}
		
		if($score['Scorecard']['position'] == 'Medic') {
			echo "<li>Medic Boosts: ".($score['Scorecard']['life_boost'] * 2)."</li>";
		}
		
		//survival bonuses/penalties
		if($score['Scorecard']['lives_left'] > 0 && $score['Scorecard']['position'] == "Medic") {
			echo "<li>Medic Survival Bonus: 2</li>";
		} elseif($score['Scorecard']['lives_left'] <= 0 && $score['Scorecard']['position'] != "Medic") {
			echo "<li>Eliminated: -1</li>";
		}
		
		//lose 5 points for every penalty in competitive games only
		if($score['Scorecard']['type'] == 'league' || $score['Scorecard']['type'] == 'tournament')
			echo "<li>Penalties: ".($score['Scorecard']['penalties'] * -5)."</li>";
		
		if($score['Scorecard']['elim_other_team'])
			echo "<li>Elimination Bonus: ".($score['Scorecard']['elim_other_team'] * 2)."</li>";
	?>
</ul>
			