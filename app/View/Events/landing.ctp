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
				{ "data" : function ( row, type, val, meta) {			
						if (type === 'display') {
							return '<a href="/events/view/'+row.id+'?gametype='+row.type+'&centerID='+row.center_id+'&eventID='+row.id+'" class="btn btn-info btn-block">'+row.name+'</a>';
						}
						return row.name;
					},
					"width" : "200px" 
				},
                { "data" : "center_name" },
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
	<div class="panel-heading clearfix" id="recent_social_heading">
        <h3 class="panel-title pull-left">Recent Socials</h3>
        <?=$this->Html->link('See All <span class="glyphicon glyphicon-forward"></span>', 
            array('controller' => 'Events', 
                'action' => 'index', 
                '?' => array(
                    'gametype' => 'social'
                )
            ),
            array(
                'escape' => false,
                'class' => 'btn btn-sm btn-info pull-right'
            )
        );?>
	</div>
    <table 
        class="event_table table table-striped table-bordered table-hover" 
        id="recent_social_table" 
        data-ajax="<?= html_entity_decode($this->Html->url(array('controller' => 'events', 'action' => 'eventList', 'social', '?' => array('limit' => 10), 'ext' => 'json'))); ?>"
    >
        <thead>
            <tr>
                <th class="col-xs-4">Event</th>
                <th class="col-xs-4">Center</th>
                <th class="col-xs-4">Last Played</th>
            </tr>
        </thead>
    </table>
</div>
<div id="recent_tournament" class="panel panel-primary">
	<div class="panel-heading clearfix" id="recent_social_heading">
        <h3 class="panel-title pull-left">Recent Tournaments</h3>
        <?=$this->Html->link('See All <span class="glyphicon glyphicon-forward"></span>', 
            array('controller' => 'Events', 
                'action' => 'index', 
                '?' => array(
                    'gametype' => 'tournament'
                )
            ),
            array(
                'escape' => false,
                'class' => 'btn btn-sm btn-info pull-right'
            )
        );?>
	</div>
    <table 
        class="event_table table table-striped table-bordered table-hover" 
        id="recent_tournament_table" 
        data-ajax="<?= html_entity_decode($this->Html->url(array('controller' => 'events', 'action' => 'eventList', 'tournament', '?' => array('limit' => 5), 'ext' => 'json'))); ?>"
    >
        <thead>
            <tr>
                <th class="col-xs-4">Event</th>
                <th class="col-xs-4">Center</th>
                <th class="col-xs-4">Last Played</th>
            </tr>
        </thead>
    </table>
</div>
<div id="recent_league" class="panel panel-primary">
	<div class="panel-heading clearfix" id="recent_league_heading">
        <h3 class="panel-title pull-left">Recent Leagues</h3>
        <?=$this->Html->link('See All <span class="glyphicon glyphicon-forward"></span>', 
            array('controller' => 'Events', 
                'action' => 'index', 
                '?' => array(
                    'gametype' => 'league'
                )
            ),
            array(
                'escape' => false,
                'class' => 'btn btn-sm btn-info pull-right'
            )
        );?>
	</div>
    <table 
        class="event_table table table-striped table-bordered table-hover" 
        id="recent_league_table" 
        data-ajax="<?= html_entity_decode($this->Html->url(array('controller' => 'events', 'action' => 'eventList', 'league', '?' => array('limit' => 5), 'ext' => 'json'))); ?>"
    >
        <thead>
            <tr>
                <th class="col-xs-4">Event</th>
                <th class="col-xs-4">Center</th>
                <th class="col-xs-4">Last Played</th>
            </tr>
        </thead>
    </table>
</div>