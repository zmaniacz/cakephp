<script>
var checkStatus = function() {
	$.getJSON("<?php echo $this->Html->url(array('action' => 'checkPid', $pid, 'ext' => 'json')); ?>", function(data) {
		if (data.alive) {
			setTimeout(checkStatus, 1000);
		} else {
			$("#status").html("<h3>Complete!</h3><p>Click <?php echo addslashes($this->Html->link('Here', array('controller' => 'uploads', 'action' => 'parseCSV', 'league_id' => $this->request->named['league_id'], 'center_id' => $this->request->named['center_id']))); ?> to import scorecards.</p>");
		}
	});
};

checkStatus();
</script>
<div id="status"><img src="http://lfstats.redial.net/img/lfstats_loading.gif" /></div>