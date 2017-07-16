<?php

// declare an array with 3 elements

$countries = array("Canada", "USA", "Brazil");
// $countries = []; // another way to declare arrays, in newer versions of PHP

// arrays are 0 indexed, which means 0 will be referring to Canada
echo $countries[0];
echo '<br/>';

// here we can push on another String to the array
// this will get a default index provided by PHP
$countries[] = "China";

// here we explicitly set the index to 14
// and we also mixed in the integer 500 into the array
$countries[14] = 500;

// we can also use strings as indexes
// here foo is the index
$countries['foo'] = 'bar';

// set a few more elements into the array
$countries[5] = "France";
$countries[] = "Germany";

// using a for-each loop, we print out every key-value pair
// in other words, we print out the index used to address each value
foreach($countries as $key => $country)
{
    echo $key . " ";
    echo $country;
    echo '<br/>';
}

echo '<br/>';
echo '<br/>';
echo '<br/>';

// there are various sort functions to sort an array
// this sorts according to the values, in alphabetical order
sort($countries);

// notice this changed the indexes as well
// some sort functions don't change the indexes and maintain the original, check the PHP documentation
foreach($countries as $key => $country)
{
    echo $key . " ";
    echo $country;
    echo '<br/>';
}
echo '<br/>';

// we can print out the total size of the array
echo count($countries);
echo '<br/>';
echo '<br/>';
echo '<br/>';

// var_dump() can help you debug an array
// the <pre> tag from html can help you format the output a bit to make it more readable.
echo "<pre>";
var_dump($countries);
