<div class="jumbotron">
    <h1 class="display-5">WCT 2018</h3>
    <p class="lead">The 5th West Coast Tournament held January 16th - 18th, 2017 at Loveland LaserTag in Loveland, CO.</p>
    <p class="lead"><a class="btn btn-primary btn-lg" href="/leagues/standings?gametype=league&amp;leagueID=18&amp;centerID=10">Details <i class="fas fa-caret-right"></i></a></p>
</div>
<div class="row">
    <div class="col-sm">
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="socialDropDown" data-toggle="dropdown">
            Jump to social games
        </button>
        <div class="dropdown-menu">
            <h6 class="dropdown-header">Centers</h6>
            <?php
                $sorted_centers = $centers;
                asort($sorted_centers);
                foreach($sorted_centers as $key => $value) {
                    echo $this->Html->link($value, array(
                        'controller' => 'scorecards', 
                        'action' => 'nightly',
                        implode(",", $this->request->pass),
                        '?' => array(
                            'gametype' => $this->Session->read('state.gametype'),
                            'centerID' => $key,
                            'leagueID' => 0
                        )
                    ), array('class' => "dropdown-item"));
                }
            ?>
        </div>
    </div>
    </div>
    <div class="col-sm">
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="compDropDown" data-toggle="dropdown">
            Jump to competition
        </button>
        <div class="dropdown-menu">
            <h6 class="dropdown-header">Competitions</h6>
            <?php
                foreach($league_details as $league) {
                    echo $this->Html->link($league['League']['name'], array(
                        'controller' => 'leagues', 
                        'action' => 'standings',
                        implode(",", $this->request->pass),
                        '?' => array(
                            'gametype' => 'league',
                            'centerID' => $league['League']['center_id'],
                            'leagueID' => $league['League']['id']
                        )
                    ), array('class' => "dropdown-item"));
                }
            ?>
        </div>
    </div>
    </div>
</div>
<hr>
<table class="table table-striped table-sm table-bordered table-hover" id="events_list">
    <thead>
        <tr>
            <th>Center</th>
            <th class="text-right">Date</th>
            <th class="text-right">Games Played</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<script type="text/javascript">
    $(document).ready(function() {
        let params = new URLSearchParams();
        let events = <?= json_encode($events, JSON_NUMERIC_CHECK, JSON_FORCE_OBJECT); ?>;
        let table = $('#events_list tbody');

        events.forEach( function(item) {
            params.set('gametype', item.Game.type);
            params.set('centerID', item.Center.id);
            params.set('leagueID', (!item.Game.league_id) ? 0 : item.Game.league_id);

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