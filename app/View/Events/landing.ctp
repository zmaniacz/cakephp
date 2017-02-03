<script type="text/javascript">
	$(document).ready(function() {
		$('.event_table').DataTable( {
			"deferRender" : true,
            "ordering" : false,
            "paging" : false,
            "pageLength" : 5,
            "searching" : false,
            "info" : false,
			"columns" : [
				{ "data" : "name" },
                { "data" : function ( row, type, val, meta ) {
						if (type === 'display') {
                            return row.center_short_name.toUpperCase();
						}
						
						return row.center_short_name;
					}
				},
                { "data" : function ( row, type, val, meta ) {
						if (type === 'display') {
							let date = new Date(row.last_gametime);
                            return date.toLocaleDateString();
						}
						
						return row.last_gametime;
					}
				}
			]
		});
    });
</script>
<div id="recent_social" class="panel panel-primary">
	<div class="panel-heading" id="recent_social_heading">
			Recent Socials
	</div>
    <table 
        class="event_table table table-striped table-bordered table-hover" 
        id="recent_social_table" 
        data-ajax="<?= html_entity_decode($this->Html->url(array('controller' => 'events', 'action' => 'eventList', 'social', '?' => array('limit' => 5), 'ext' => 'json'))); ?>"
    >
        <thead>
            <tr>
                <th>Event</th>
                <th>Center</th>
                <th>Last Played</th>
            </tr>
        </thead>
    </table>
</div>
<div id="recent_tournament" class="panel panel-primary">
	<div class="panel-heading" id="recent_tournament_heading">
			Recent Tournaments
	</div>
    <table 
        class="event_table table table-striped table-bordered table-hover" 
        id="recent_tournament_table" 
        data-ajax="<?= html_entity_decode($this->Html->url(array('controller' => 'events', 'action' => 'eventList', 'tournament', '?' => array('limit' => 5), 'ext' => 'json'))); ?>"
    >
        <thead>
            <tr>
                <th>Event</th>
                <th>Center</th>
                <th>Last Played</th>
            </tr>
        </thead>
    </table>
</div>
<div id="recent_league" class="panel panel-primary">
	<div class="panel-heading" id="recent_league_heading">
			Recent Leagues
	</div>
    <table 
        class="event_table table table-striped table-bordered table-hover" 
        id="recent_league_table" 
        data-ajax="<?= html_entity_decode($this->Html->url(array('controller' => 'events', 'action' => 'eventList', 'league', '?' => array('limit' => 5), 'ext' => 'json'))); ?>"
    >
        <thead>
            <tr>
                <th>Event</th>
                <th>Center</th>
                <th>Last Played</th>
            </tr>
        </thead>
    </table>
</div>