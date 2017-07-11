<?php
class Tweet {
	public $moma;

	public function __get($property)
	{
	echo "hi from get<br>";
	  if (property_exists($this, $property)) {
	    return $this->$property;
	  }
	}
	public function __set($property, $value)
	{
		echo "hi from set<br>";
	  if (property_exists($this, $property)) {
	    $this->$property = $value;
	  }
	}
	public function  __isset($property)
	{
	  return isset($this->$property);
	}

}
$tweet = new Tweet();
$twit = $tweet;
$twit->moma = 'Lovesya';
$tweet->moma= 'doesn\'t';

var_dump($tweet->moma);
var_dump($twit->moma);
var_dump(isset($twit->moma));
