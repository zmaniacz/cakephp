<div>
	<p>
		Choose a competition:
	</p>
	<h2>Tournaments</h2>
	<ul>
		<?php
			foreach($leagues as $league) {
				if($league['League']['type'] == 'tournament') {
					echo "<li>".$this->Form->postLink($league['League']['name'], array('controller' => 'scorecards', 'action' => 'pickLeague'), array('data' => array('league_id' => $league['League']['id'])))."</li>";
				}
			}
		?>
	</ul>
	<h2>Leagues</h2>
	<ul>
	<?php
		foreach($leagues as $league) {
			if($league['League']['type'] == 'league') {
				echo "<li>".$this->Form->postLink($league['Center']['name']." - ".$league['League']['name'], array('controller' => 'scorecards', 'action' => 'pickLeague'), array('data' => array('league_id' => $league['League']['id'])))."</li>";
			}
		}
	?>
	</ul>
</div>