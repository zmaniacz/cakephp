<?php

App::uses('ConnectionManager', 'Model');

class MigrateShell extends AppShell {
    public $uses = array('Team','Game','Scorecard','Event');

    public function main() {
        $this->out('choose a step');
    }

    public function step_1() {
        $db = ConnectionManager::getDataSource('default');

        //Rename teams table and foreign keys
        $db->rawQuery("ALTER TABLE `lfstats`.`teams` RENAME TO  `lfstats`.`event_teams`");
        $db->rawQuery("ALTER TABLE `lfstats`.`event_teams` DROP FOREIGN KEY `fk_teams_leagues_league_id`");
        $db->rawQuery("ALTER TABLE `lfstats`.`event_teams` DROP INDEX `league_id`");
        $db->rawQuery("ALTER TABLE `lfstats`.`event_teams` CHANGE COLUMN `league_id` `event_id` INT(11) NOT NULL");

        //rename leagues table to events
        $db->rawQuery("ALTER TABLE `lfstats`.`leagues` DROP FOREIGN KEY `fk_leagues_centers_center_id`");
        $db->rawQuery("ALTER TABLE `lfstats`.`leagues` 
                        ADD COLUMN `is_comp` TINYINT(1) NOT NULL DEFAULT 0 AFTER `type`, 
                        RENAME TO  `lfstats`.`events`");
        $db->rawQuery("ALTER TABLE `lfstats`.`events` 
                        ADD CONSTRAINT `fk_events_centers_center_id`
                            FOREIGN KEY (`center_id`)
                            REFERENCES `lfstats`.`centers` (`id`)");
        
        //fix event_teams foreign keys
        $db->rawQuery("ALTER TABLE `lfstats`.`event_teams` 
                        ADD CONSTRAINT `fk_event_teams_events_event_id`
                            FOREIGN KEY (`event_id`)
                            REFERENCES `lfstats`.`events` (`id`)");
        
        //drop player_teams table -- unused
        $db->rawQuery("DROP TABLE `lfstats`.`players_teams`;");

        //gotta fix the Rounds table too
        $db->rawQuery("ALTER TABLE `lfstats`.`rounds` DROP FOREIGN KEY `fk_rounds_leagues_league_id`");
        $db->rawQuery("ALTER TABLE `lfstats`.`rounds` 
                        CHANGE COLUMN `league_id` `event_id` INT(11) NULL DEFAULT NULL ,
                        ADD INDEX `fk_rounds_events_event_id_idx` (`event_id` ASC),
                        DROP INDEX `league_id`");
        $db->rawQuery("ALTER TABLE `lfstats`.`rounds` 
                        ADD CONSTRAINT `fk_rounds_events_event_id`
                            FOREIGN KEY (`event_id`)
                            REFERENCES `lfstats`.`events` (`id`)
                            ON DELETE NO ACTION
                            ON UPDATE NO ACTION");

        ///////REGEN THE VIEW
        $db->rawQuery("CREATE OR REPLACE
                        ALGORITHM = UNDEFINED 
                        DEFINER = `dbo_redial`@`%` 
                        SQL SECURITY DEFINER
                    VIEW `league_games` AS
                        SELECT 
                            `Event`.`id` AS `event_id`,
                            `Round`.`id` AS `round_id`,
                            `Round`.`round` AS `round_number`,
                            `Round`.`is_finals` AS `is_finals`,
                            `Match`.`id` AS `match_id`,
                            `Match`.`match` AS `match_number`,
                            `Game`.`id` AS `game_id`,
                            `Game`.`league_game` AS `game_number`
                        FROM
                            (((`events` `Event`
                            JOIN `rounds` `Round` ON ((`Round`.`event_id` = `Event`.`id`)))
                            JOIN `matches` `Match` ON ((`Match`.`round_id` = `Round`.`id`)))
                            JOIN `games` `Game` ON ((`Game`.`match_id` = `Match`.`id`)))
                        ORDER BY `Event`.`id` , `Round`.`round` , `Match`.`match` , `Game`.`league_game`");

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
                        `event_team_id` int(11) DEFAULT NULL,
                        `created` datetime NULL DEFAULT NULL,
                        `updated` datetime NULL DEFAULT NULL,
                        PRIMARY KEY (`id`),
                        KEY `game_id_idx` (`game_id`),
                        KEY `event_team_id_idx` (`event_team_id`), 
                        CONSTRAINT `fk_teams_games_game_id` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`),
                        CONSTRAINT `fk_teams_event_teams_event_team_id` FOREIGN KEY (`event_team_id`) REFERENCES `event_teams` (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
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
                    'event_team_id' => $game['Game']['red_team_id']
                ),
                array(
                    'color' => 'green',
                    'raw_score' => $game['Game']['green_score'],
                    'eliminated' => $game['Game']['green_eliminated'],
                    'eliminated_opponent' => $game['Game']['red_eliminated'],
                    'game_id' => $game['Game']['id'],
                    'event_team_id' => $game['Game']['green_team_id']
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
                        ADD COLUMN `event_id` INT(11) NULL DEFAULT NULL AFTER `match_id`,
                        ADD INDEX `fk_games_events_event_id_idx` (`event_id` ASC),
                        DROP INDEX `red_team_id` ,
                        DROP INDEX `green_team_id`");
       
        $db->rawQuery("ALTER TABLE `lfstats`.`games` 
                        ADD CONSTRAINT `fk_games_centers_center_id`
                            FOREIGN KEY (`center_id`)
                            REFERENCES `lfstats`.`centers` (`id`)
                            ON DELETE NO ACTION
                            ON UPDATE NO ACTION,
                        ADD CONSTRAINT `fk_games_events_event_id`
                            FOREIGN KEY (`event_id`)
                            REFERENCES `lfstats`.`events` (`id`)
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
        //create and populate events
        //rip and repalce leageus table with events first

    }
}