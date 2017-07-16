<?php

// define a function that takes 2 parameters
function printTwoParams($param1, $param2)
{
	// return the concatenated version back to the caller
	return $param1 . "<br/>" .$param2;
}

// call the function we defined
// assign the returned value to a variable
$string = printTwoParams("hello", "world");

// mutate the value to all uppercase,
// then send to output
echo strtoupper($string);


// set the timezone to reference from vancouver 
date_default_timezone_set("America/Vancouver");

// return timestamp for the current instance in time
$now 		= time();	

// return timestamp for a point in time, in the past
$past 		= mktime(0, 0, 0, 8, 20, 1986);

// return a timestamp for five days into the future
$future 	= time() + 60 * 60 * 24 * 5;

// print out the formatted string
$thedate = date("l F d, Y", $future);
echo $thedate;

$number = 25;

// this evalutes to true because of weak typing 
if($number == "25")
{
	echo $number;
}

// this evalutes to fales because === needs to match both values and data types
if($number === "25")
{
	echo $number;
}


