<?php
	
	function sms($var=''){
		$finalArr = array();
		foreach(debug_backtrace() as $data)
		{}
		echo "<pre style='background-color:orange; font-size:20px; font-family:verdana;'>";
		print_r($data['args'][0]);
		echo "</pre></p>";
	}
?>