<div class="row">
    <div class="jumbotron">
        <h3>WCT 2018</h3>
        <p>The 5th West Coast Tournament held January 16th - 18th, 2017 at Loveland LaserTag in Loveland, CO.</p>
        <p><a class="btn btn-primary btn-lg" href="/leagues/standings?gametype=league&amp;leagueID=18&amp;centerID=10">Details <i class="fas fa-caret-right"></i></a></p>
    </div>
</div>
<div class="row">
    <div class="col-xs-8 col-xs-offset-2 col-sm-3 col-sm-offset-3">
        <div class="dropdown">
            <button class="btn btn-default btn-block dropdown-toggle" type="button" id="socialDropDown" data-toggle="dropdown">
                Jump to social games <i class="fas fa-caret-down"></i>
            </button>
            <ul class="dropdown-menu">
                <li class="dropdown-header">Centers</li>
                <?php
                    $sorted_centers = $centers;
                    asort($sorted_centers);
                    foreach($sorted_centers as $key => $value) {
                        echo "<li>".$this->Html->link($value, array(
                            'controller' => 'scorecards', 
                            'action' => 'nightly',
                            implode(",", $this->request->pass),
                            '?' => array(
                                'gametype' => $this->Session->read('state.gametype'),
                                'centerID' => $key,
                                'leagueID' => 0
                            )
                        ))."</li>";
                    }
                ?>
            </ul>
        </div>
    </div>
    <div class="col-xs-8 col-xs-offset-2 col-sm-3 col-sm-offset-0">
        <div class="dropdown">
            <button class="btn btn-default btn-block dropdown-toggle" type="button" id="compDropDown" data-toggle="dropdown">
                Jump to competition <i class="fas fa-caret-down"></i>
            </button>
            <ul class="dropdown-menu">
                <li class="dropdown-header">Competitions</li>
                <?php
                    foreach($league_details as $league) {
                        echo "<li>".$this->Html->link($league['League']['name'], array(
                            'controller' => 'leagues', 
                            'action' => 'standings',
                            implode(",", $this->request->pass),
                            '?' => array(
                                'gametype' => 'league',
                                'centerID' => $league['League']['center_id'],
                                'leagueID' => $league['League']['id']
                            )
                        ))."</li>";
                    }
                ?>
            </ul>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
        <table class="table table-striped table-condensed table-bordered table-hover" id="events_list">
            <thead>
                <tr>
                    <th class="col-xs-4">Center</th>
                    <th class="col-xs-4 text-right">Date</th>
                    <th class="col-xs-4 text-right">Games Played</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        let params = new URLSearchParams();
        let events = <?= json_encode($events, JSON_NUMERIC_CHECK, JSON_FORCE_OBJECT); ?>;
        let table = $('#events_list tbody');

        events.forEach( function(item) {
            params.set('gametype', item.Game.type);
            params.set('centerID', item.Center.id);
            params.set('leagueID', (item.Game.type === 'league') ? item.Game.league_id : 0);

            let eventLink = `<a href="/scorecards/nightly/${item[0].games_date}?${params.toString()}">
                                ${item.Center.name} - <span class="text-capitalize">${item.Game.type}</span></a>`;
            
            let row = `<tr>
                        <td>${eventLink}</td>
                        <td class="text-right">${item[0].games_date}</td>
                        <td class="text-right">${item[0].games_played}</td>
                    </tr>`;
            table.append(row);
        });
    });
</script>