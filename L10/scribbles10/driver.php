<?php

// import the required classes
require("Person.php");
require("Child.php");

// create three Person objects in memory.
// the new keyword returns an address to the object in memory
// assign each address to the variables $p1, $p2, $p3
$p1 = new Person();
$p2 = new Person();
$p3 = new Person();

// grab the value of age and send it to output.
// we have to use getAge() because $age is of private scope.
// this means an external call from this file directly to $age is not permitted.
// getAge() however is of public scope.
// that means this external call is permitted.
echo $p1->getAge();
//  echo $p1->age; // this won't run

// alter the value of age
$p1->setAge(30);

// check to see what the new value of age is
echo '<br>';
echo $p1->getAge();

// lets look at the ages of the other objects in memory
echo '<br>';
echo $p2->getAge();
echo '<br>';
echo $p3->getAge();

// lets look at their name
echo '<br>';echo '<br>';echo '<br>';
echo $p1->getName();
echo '<br>';
echo $p2->getName();
echo '<br>';
echo $p3->getName();
echo '<br>';

// create a new Child object and hold it's address in the $c1 variable
$c1 = new Child();

// getAge() works because it was inherited from the Perosn class
echo $c1->getAge();
// setToy() is defined in the Child class; alter the toy
$c1->setToy("Barbie");
echo $c1->getToy();


echo '<br>';
// get name is inherited from the Person class, which is why $c1 can use it
echo $c1->getName();

// doesn't work!
// $p1 is pointing to a Person object. And it does not have getToy() defined
//$p1->getToy();





