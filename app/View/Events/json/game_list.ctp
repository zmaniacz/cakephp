<?php
	$data = $response['Game'];
	echo json_encode(compact('data'), JSON_NUMERIC_CHECK);
?>