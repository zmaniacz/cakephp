<?php

App::uses('ConnectionManager', 'Model');

class MigrateShell extends AppShell {
    public $uses = array('Team','Game','Scorecard');

    public function main() {
        $this->out('choose a step');
    }

    public function step_1() {
        $db = ConnectionManager::getDataSource('default');

        //Rename teams table and foreign keys
        $db->rawQuery("ALTER TABLE `lfstats`.`teams` RENAME TO  `lfstats`.`league_teams`");
        $db->rawQuery("ALTER TABLE `lfstats`.`league_teams` DROP FOREIGN KEY `fk_teams_leagues_league_id`");
        $db->rawQuery("ALTER TABLE `lfstats`.`league_teams` 
                        ADD CONSTRAINT `fk_league_teams_leagues_league_id`
                            FOREIGN KEY (`league_id`)
                            REFERENCES `lfstats`.`leagues` (`id`)");
        
        //drop player_teams table -- unused
        $db->rawQuery("DROP TABLE `lfstats`.`players_teams`;");

        //create the new teams table
        $db->rawQuery("CREATE TABLE `teams` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                        `raw_score` int(11) NOT NULL DEFAULT '0',
                        `bonus_score` int(11) NOT NULL DEFAULT '0',
                        `penalty_score` int(11) NOT NULL DEFAULT '0',
                        `eliminated` tinyint(1) NOT NULL DEFAULT '0',
                        `eliminated_opponent` tinyint(1) NOT NULL DEFAULT '0',
                        `game_id` int(11) NOT NULL,
                        `league_team_id` int(11) DEFAULT NULL,
                        `created` datetime NULL DEFAULT NULL,
                        `updated` datetime NULL DEFAULT NULL,
                        PRIMARY KEY (`id`),
                        KEY `game_id_idx` (`game_id`),
                        KEY `league_team_id_idx` (`league_team_id`), 
                        CONSTRAINT `fk_teams_games_game_id` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`),
                        CONSTRAINT `fk_teams_league_teams_league_team_id` FOREIGN KEY (`league_team_id`) REFERENCES `league_teams` (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

        //create events table
        $db->rawQuery("CREATE TABLE `events` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                        `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'social',
                        `created` datetime NULL DEFAULT NULL,
                        `updated` datetime NULL DEFAULT NULL,
                        PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
        
        //add fk from games to events
        //create event linkages
    }

    public function step_2() {
        $db = ConnectionManager::getDataSource('default');

        //create teams records based on existing game records
        //select all the games with their red team data
        $games = $this->Game->find('all');
        foreach($games as $game) {
            $teams = array(
                array(
                    'color' => 'red',
                    'raw_score' => $game['Game']['red_score'],
                    'eliminated' => $game['Game']['red_eliminated'],
                    'eliminated_opponent' => $game['Game']['green_eliminated'],
                    'game_id' => $game['Game']['id'],
                    'league_team_id' => $game['Game']['red_team_id']
                ),
                array(
                    'color' => 'green',
                    'raw_score' => $game['Game']['green_score'],
                    'eliminated' => $game['Game']['green_eliminated'],
                    'eliminated_opponent' => $game['Game']['red_eliminated'],
                    'game_id' => $game['Game']['id'],
                    'league_team_id' => $game['Game']['green_team_id']
                )
            );

            $this->Team->saveMany($teams);
            $this->Team->clear();
        }

        //add team_id fk to scorecards
        $db->rawQuery("ALTER TABLE `lfstats`.`scorecards` 
                        ADD COLUMN `team_id` INT(11) NULL DEFAULT NULL AFTER `league_id`");
        $db->rawQuery("ALTER TABLE `lfstats`.`scorecards` 
                        ADD INDEX `team_id_fk_idx` (`team_id`)");
        $db->rawQuery("ALTER TABLE `lfstats`.`scorecards` 
                        ADD CONSTRAINT `fk_scorecards_teams_team_id`
                            FOREIGN KEY (`team_id`)
                            REFERENCES `lfstats`.`teams` (`id`)");
    }

    public function step_3() {
        //link scorecards to teams instead of games
        $scorecards = $this->Scorecard->find('all', array('fields' => array('id', 'team', 'game_id', 'team_id')));
        $games = $this->Game->find('all', array(
            'fields' => array('Game.id'),
			'contain' => array(
                'Team' =>array(
                    'id',
                    'color'
                )
            )
        ));

        $results = array();
        array_map(function($n) use (&$results) {
            $results[$n['Game']['id']] = array(
                $n['Team'][0]['color'] => $n['Team'][0]['id'],
                $n['Team'][1]['color'] => $n['Team'][1]['id']
            );
            return null;
        }, $games);

        foreach($scorecards as $scorecard) {
            $scorecard['Scorecard']['team_id'] = $results[$scorecard['Scorecard']['game_id']][$scorecard['Scorecard']['team']];
            $this->Scorecard->save($scorecard);
            $this->Scorecard->clear();
        }
    }

    public function step_4() {
        //remove redundant game data - kill columns
        $db = ConnectionManager::getDataSource('default');

        $db->rawQuery("ALTER TABLE `lfstats`.`games` 
                        DROP FOREIGN KEY `fk_games_centers_center_id`,
                        DROP FOREIGN KEY `fk_games_teams_red_team_id`,
                        DROP FOREIGN KEY `fk_games_teams_green_team_id`");

        $db->rawQuery("ALTER TABLE `lfstats`.`games` 
                        DROP COLUMN `green_eliminated`,
                        DROP COLUMN `red_eliminated`,
                        DROP COLUMN `green_adj`,
                        DROP COLUMN `red_adj`,
                        DROP COLUMN `green_score`,
                        DROP COLUMN `red_score`,
                        DROP COLUMN `green_team_name`,
                        DROP COLUMN `red_team_name`,
                        DROP COLUMN `red_team_id`,
                        DROP COLUMN `green_team_id`,
                        CHANGE COLUMN `game_name` `game_name` VARCHAR(100) CHARACTER SET 'utf8' NULL DEFAULT NULL ,
                        CHANGE COLUMN `game_description` `game_description` TEXT CHARACTER SET 'utf8' NULL DEFAULT NULL ,
                        CHANGE COLUMN `center_id` `center_id` INT(11) NOT NULL ,
                        DROP INDEX `red_team_id` ,
                        DROP INDEX `green_team_id`");

        $db->rawQuery("ALTER TABLE `lfstats`.`games` 
                        ADD CONSTRAINT `fk_games_centers_center_id`
                        FOREIGN KEY (`center_id`)
                        REFERENCES `lfstats`.`centers` (`id`)
                        ON DELETE NO ACTION
                        ON UPDATE NO ACTION");
    }

    public function step_5() {
        //recalc all game winners
        $games = $this->Game->find('all', array(
            'fields' => array('id')
        ));

        foreach($games as $game) {
            $this->Game->updateGameWinner($game['Game']['id']);
        }
    }

    public function step_6() {
        //create and link events
    }
}