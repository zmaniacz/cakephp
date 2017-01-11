<?php 
class AppSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $centers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'short_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $game_results = array(
		'game_datetime' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'player_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'scorecard_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'game_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'center_id' => array('type' => 'integer', 'null' => true, 'default' => '1', 'unsigned' => false),
		'league_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'result' => array('type' => 'string', 'null' => false, 'length' => 1, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'won' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'unsigned' => false),
		'indexes' => array(
			
		),
		'tableParameters' => array('comment' => 'VIEW')
	);

	public $games = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'game_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'game_description' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'game_datetime' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'green_team_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'red_team_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'red_team_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'green_team_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'red_score' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'green_score' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'red_adj' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'green_adj' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'winner' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 5, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'red_eliminated' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'green_eliminated' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'league_round' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4, 'unsigned' => false),
		'league_match' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4, 'unsigned' => false),
		'league_game' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4, 'unsigned' => false),
		'pdf_id' => array('type' => 'biginteger', 'null' => true, 'default' => null, 'unsigned' => false),
		'center_id' => array('type' => 'integer', 'null' => true, 'default' => '1', 'unsigned' => false, 'key' => 'index'),
		'league_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'match_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'center_id' => array('column' => 'center_id', 'unique' => 0),
			'league_id' => array('column' => 'league_id', 'unique' => 0),
			'green_team_id' => array('column' => 'green_team_id', 'unique' => 0),
			'red_team_id' => array('column' => 'red_team_id', 'unique' => 0),
			'match_id' => array('column' => 'match_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $hits = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'hits' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'missiles' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'player_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'target_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'scorecard_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'player_id' => array('column' => 'player_id', 'unique' => 0),
			'target_id' => array('column' => 'target_id', 'unique' => 0),
			'game_id' => array('column' => 'scorecard_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $league_games = array(
		'league_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'round_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'league_round' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'is_finals' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4, 'unsigned' => false),
		'match_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'league_match' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'game_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'league_game' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 4, 'unsigned' => false),
		'indexes' => array(
			
		),
		'tableParameters' => array('comment' => 'VIEW')
	);

	public $leagues = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 20, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'center_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'center_id' => array('column' => 'center_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $matches = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'match' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'team_1_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'team_2_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'team_1_points' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'team_2_points' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'round_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'team_1_id' => array('column' => 'team_1_id', 'unique' => 0),
			'team_2_id' => array('column' => 'team_2_id', 'unique' => 0),
			'round_id' => array('column' => 'round_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $mvp_values = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'factor' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'value' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $penalties = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'type' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'value' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'scorecard_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'scorecard_id' => array('column' => 'scorecard_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $players = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'player_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 100, 'key' => 'unique', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'member_number' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'player_name' => array('column' => 'player_name', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $players_names = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'player_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'player_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'player_id' => array('column' => 'player_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $players_teams = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'player_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'team_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'player_id' => array('column' => 'player_id', 'unique' => 0),
			'team_id' => array('column' => 'team_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $rounds = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'round' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'is_finals' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4, 'unsigned' => false),
		'league_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'league_id' => array('column' => 'league_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $scorecards = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'player_name' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'game_datetime' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'game_endtime' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'team' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'position' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'shots_hit' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'shots_fired' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'times_zapped' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'times_missiled' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'missile_hits' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'nukes_activated' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'nukes_detonated' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'nukes_canceled' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'medic_hits' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'own_medic_hits' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'medic_nukes' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'scout_rapid' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'life_boost' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'ammo_boost' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'lives_left' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'score' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'shots_left' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'penalties' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'shot_3hit' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'elim_other_team' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'team_elim' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'own_nuke_cancels' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'shot_opponent' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'shot_team' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'missiled_opponent' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'missiled_team' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'resupplies' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'rank' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'bases_destroyed' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'accuracy' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'mvp_points' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'sp_earned' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'sp_spent' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'game_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'type' => array('type' => 'string', 'null' => false, 'default' => 'social', 'length' => 20, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'is_sub' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'player_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'center_id' => array('type' => 'integer', 'null' => true, 'default' => '1', 'unsigned' => false, 'key' => 'index'),
		'pdf_id' => array('type' => 'biginteger', 'null' => true, 'default' => null, 'unsigned' => false),
		'league_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'game_id_fk_idx' => array('column' => 'game_id', 'unique' => 0),
			'player_id_fk_idx' => array('column' => 'player_id', 'unique' => 0),
			'center_id_fk_idx' => array('column' => 'center_id', 'unique' => 0),
			'league_id' => array('column' => 'league_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $teams = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'points' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 9, 'unsigned' => false),
		'league_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'captain_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'league_id' => array('column' => 'league_id', 'unique' => 0),
			'captain_id' => array('column' => 'captain_id', 'unique' => 0)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

	public $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 10, 'unsigned' => true, 'key' => 'primary'),
		'username' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'password' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'role' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
		'center' => array('type' => 'integer', 'null' => true, 'default' => null, 'length' => 10, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB')
	);

}
