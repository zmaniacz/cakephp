<script type="text/javascript">
	$(document).ready(function() {
		$('#recent_social_table').DataTable( {
			"deferRender" : true,
            "ordering" : false,
            "paging" : false,
            "pageLength" : 5,
            "searching" : false,
            "info" : false,
			"ajax" : {
				"url" : "<?= html_entity_decode($this->Html->url(array('controller' => 'events', 'action' => 'eventList', 'social', '?' => array('limit' => 5), 'ext' => 'json'))); ?>"
			},
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
<div id="recent_social" class="panel panel-info col-xs-6">
	<div class="panel-heading" id="recent_social_heading">
		<h4 class="panel-title">
			Recent Socials
		</h4>
	</div>
            <table class="table table-striped table-bordered table-hover" id="recent_social_table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Center</th>
                        <th>Last Played</th>
                    </tr>
                </thead>
            </table>
</div>
<div id="recent_social" class="panel panel-info col-xs-6">
	<div class="panel-heading" id="recent_social_heading">
		<h4 class="panel-title">
			Recent Socials
		</h4>
	</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="recent_social_table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Center</th>
                        <th>Last Played</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<div id="recent_social" class="panel panel-info col-xs-6">
	<div class="panel-heading" id="recent_social_heading">
		<h4 class="panel-title">
			Recent Socials
		</h4>
	</div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="recent_social_table">
                <thead>
                    <tr>
                        <th>Event</th>
                        <th>Center</th>
                        <th>Last Played</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>