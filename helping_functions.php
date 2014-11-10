<?php 
	function SaveDb($array,$file=null) {
		if (empty($file)) return false;
		$jsonkeys = json_encode($array);
		$dump = file_put_contents($file,$jsonkeys);
	}
	function LoadDb($file=null) {
		if (empty($file)) return false;
		if (!is_file($file)) file_put_contents($file, '');
		$jsonkeys = file_get_contents($file);
		$array = json_decode($jsonkeys,true);
		return $array;
	}
	function ctype_alnumi($alnum=0,$ignore=null) {
		if (!empty($ignore)) $alnum = str_replace($ignore,'',$alnum);
		if (ctype_alnum($alnum)) return true;
		else return false;
	}
	function debug($string,$error=0) {
		if ($error = 0) $class = "";
		if ($error = 1) $class = "alert-info";
		if ($error = 2) $class = "alert-error";
		$html = "<div class='alert alert-$class'><button type='button' class='close' data-dismiss='alert'>&times;</button>$string</div>";
		return $html;
	}	
?>