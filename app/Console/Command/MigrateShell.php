<?php

App::uses('ConnectionManager', 'Model');

class MigrateShell extends AppShell {
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
                        `winner` tinyint(1) NOT NULL DEFAULT '0',
                        `eliminated` tinyint(1) NOT NULL DEFAULT '0',
                        `eliminated_opponent` tinyint(1) NOT NULL DEFAULT '0',
                        `game_id` int(11) NOT NULL,
                        `league_team_id' int(11) DEFAULT NULL,
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
        
        
        
        //create teams records based on existing game records

        //select all the games with their red team data
        //insert into teams with the game_id as red data
        //dont use adj scores...add elim bonuses as appropriate
        //have to go back after scorecards are tied out and re-apply penalties
        //repeat for green

        //link scorecards to teams instead of games

        //remove redundant game data - kill columns

        //add fk from games to events
        //create event linkages
    }
}