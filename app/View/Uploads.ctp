<script>
var checkStatus = function() {
	$.getJSON("<?php echo $this->Html->url(array('action' => 'checkPid', $pid, 'ext' => 'json')); ?>", function(data) {
		if (data.alive) {
			setTimeout(checkStatus, 1000);
		} else {
			$("#status").html("<h3>Complete!</h3><p>Click <?php echo addslashes($this->Html->link('Here', array('controller' => 'scorecards', 'action' => 'parseCSV'))); ?> to import scorecards.</p>");
		}
	});
};

checkStatus();
</script>
<div id="status"><img src="http://lfstats.com/img/lfstats_loading.gif" /></div>