<?php
/***Formatting helper functions***/

/*
In this file:
namify(), format_date(), moments(), clean_input(), save_as_CSV(), trimpty(), not_mempty()
*/

function namify($str)
//e.g. turning h JuLiAn_sLomAN to H. Julian Sloman
{
	$str = str_replace('_', ' ', $str);
	$str = ucwords(strtolower($str));
	if (strlen($str) === 1) {
		return $str . ".";
	}
	//handling multiple name entries
	if(strpos($str, ' ')){
		$arr = explode(' ', $str);
		foreach ($arr as $key => $value) {
			if (strlen($value) === 1) {
				$arr[$key] .= '.';
			}
		}
		$str = implode(' ', $arr);
	}
	return $str;
}

function format_date($seconds)
//e.g. turning 1481808630 to Thursday December the 15th, 2016
{
	if (is_numeric($seconds) ) {
		return date('l F \t\h\e dS, Y', $seconds);
	} else {
		return "unknown date";
	}
}


function moments($seconds)
//more intuitive date
//in JS switch seem less effective, not sure if that's true server-side, too? http://stackoverflow.com/questions/6665997/switch-statement-for-greater-than-less-than
{
	$seconds = time() - $seconds;
	if($seconds==="unknown"){
		return "no idea when";
	}
	$values = array('hour' => (60 * 60), 'day' => (60 * 60 * 24), 'month' => (60 * 60 * 24 * 30) );
	if ($seconds < $values['hour']) {
		return "just now";
	}
	if ($seconds < $values['day']) {
		return "today";
	}
	if ($seconds < $values['month']) {
		return "within the month";
	}
	if ($seconds >= $values['month']) {
		return "a while ago";
	}
}

function clean_input($data)
//inspired by https://www.w3schools.com/php/showphp.asp?filename=demo_form_validation_escapechar
{
	$data = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $data)));//removes unnecessary whitespace
	$data = stripslashes($data); //avoids escaping the intended context
	$data = htmlspecialchars($data); //also to help vs injection
	return $data;
}

function save_as_CSV($what = NULL, $where = NULL)
{
	if (!isset($where)) {
		$where = 'posts.txt';
	}
	if (!isset($what)){
		$what = $_POST;
	}
	$file = fopen($where, 'a');
	fputcsv($file, $what);
	fclose($file);
}

function trimpty($arg) //trimmed + empty
{
	return (empty(trim($arg)));
}

//multivariable-empty-checker inspired from http://stackoverflow.com/a/7798842/2848941
function not_mempty()  //multiple + trimmed + empty
{
    foreach(func_get_args() as $arg){
		if(!trimpty($arg)){
			continue;
		} else {
			return false;
		}
		return true;
	}
}

function formatBytes($bytes, $precision = 2)
//taken from http://php.net/manual/de/function.filesize.php
{
	$units = array('B', 'KB', 'MB', 'GB', 'TB');

	$bytes = max($bytes, 0);
	$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
	$pow = min($pow, count($units) - 1);
	$bytes /= pow(1024, $pow);

	return round($bytes, $precision) . ' ' . $units[$pow];
}
