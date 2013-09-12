<?php 
foreach($players as $player) {
	echo "<h4>" . $this->Html->link($player['Player']['player_name'], array('controller' => 'Players', 'action' => 'view',$player['Player']['id'])) . "</h4>";
}
?>