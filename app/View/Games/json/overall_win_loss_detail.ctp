<?php
	$green_wins_nonelim = 0;
	$green_wins_elim = 0;
	$red_wins_nonelim = 0;
	$red_wins_elim = 0;

	foreach($overall as $line) {
		if($line['Game']['winner'] == 'Green') {
			if($line['Game']['red_eliminated'] == 1) {
				$green_wins_elim += $line[0]['Total'];
			} else {
				$green_wins_nonelim += $line[0]['Total'];
			}
		} else {
			if($line['Game']['green_eliminated'] == 1) {
				$red_wins_elim += $line[0]['Total'];
			} else {
				$red_wins_nonelim += $line[0]['Total'];
			}
		}
	}
	
	$winloss = array('red_wins' => ($red_wins_nonelim + $red_wins_elim), 'green_wins' => ($green_wins_elim + $green_wins_nonelim));
	$winlossdetail = array(
		'elim_wins_from_red' => $red_wins_elim,
		'non_elim_wins_from_red' => $red_wins_nonelim,
		'elim_wins_from_green' => $green_wins_elim,
		'non_elim_wins_from_green' => $green_wins_nonelim
	);
	
	echo json_encode(compact('winloss','winlossdetail'));
?>