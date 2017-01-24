<?php

App::uses('ConnectionManager', 'Model');

class DBMigrateShell extends AppShell {
    public function main() {
        $db = ConnectionManager::getDataSource('default');

        //Rename teams table and foreign keys
        $db->rawQuery("ALTER TABLE `lfstats`.`teams` RENAME TO  `lfstats`.`league_teams`");
        $db->rawQuery("ALTER TABLE `lfstats`.`league_teams` DROP FOREIGN KEY `fk_teams_leagues_league_id`");
        $db->rawQuery("ALTER TABLE `lfstats`.`league_teams` 
                        ADD CONSTRAINT `fk_league_teams_leagues_league_id`
                            FOREIGN KEY (`league_id`)
                            REFERENCES `lfstats`.`leagues` (`id`)");
        
        //rename player_teams table
        $db->rawQuery("ALTER TABLE `lfstats`.`players_teams` RENAME TO  `lfstats`.`players_league_teams`");
        //change column name and rename foreign keys
        $db->rawQuery("ALTER TABLE `lfstats`.`players_league_teams` DROP FOREIGN KEY `fk_players_teams_teams_team_id`");
        $db->rawQuery("ALTER TABLE `lfstats`.`players_league_teams` CHANGE COLUMN `team_id` `league_team_id` INT(11) NOT NULL");
        $db->rawQuery("ALTER TABLE `lfstats`.`players_league_teams` 
                        ADD CONSTRAINT `fk_players_teams_teams_team_id`
                            FOREIGN KEY (`league_team_id`)
                            REFERENCES `lfstats`.`league_teams` (`id`)");
        //rename remaining FKs
        $db->rawQuery("ALTER TABLE `lfstats`.`players_league_teams` 
                        DROP FOREIGN KEY `fk_players_teams_players_player_id`,
                        DROP FOREIGN KEY `fk_players_teams_teams_team_id`");
        $db->rawQuery("ALTER TABLE `lfstats`.`players_league_teams` 
                        ADD CONSTRAINT `fk_players_league_teams_players_player_id`
                            FOREIGN KEY (`player_id`)
                            REFERENCES `lfstats`.`players` (`id`),
                        ADD CONSTRAINT `fk_players_league_teams_teams_team_id`
                            FOREIGN KEY (`league_team_id`)
                            REFERENCES `lfstats`.`league_teams` (`id`)");

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
                        `created` datetime NULL DEFAULT NULL,
                        `updated` datetime NULL DEFAULT NULL,
                        PRIMARY KEY (`id`),
                        KEY `game_id_idx` (`game_id`),
                        CONSTRAINT `fk_teams_games_game_id` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");

        
        //create teams records based on existing game recorsds

        //link scorecards to teams instead of games

        //remove redundant game data - kill columns

        //create events table

        //create event linkages
    }
}