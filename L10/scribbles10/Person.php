<?php
class Person
{
    // private scope means these properties can only be accessed internally
    // in some texts, these properties are known as instance variables
	private $age;
	private $name;

	// the constructor is called after the new keyword
    // this is used to create a starting defaultstate for the object in memory
	public function __construct()
	{
	    // using the $this keyword, we are addressing the current object; this object
        // the -> operator allows access to anything within scope
        // age and name are within the private scope because we are internal to the class
		$this->age = 100;           // the starting state for age is 1
		$this->name = "John Doe";   // the starting state for name is John Doe
	}

	// a setter method aka mutator method
    // this takes in a parameter to mutate the current value of name
    // because methods are of public scope, they can be called external to this class
	public function setName($name)
	{
	    // using the = operator, assign the parameter from the right hand side
        // to the instance variable on the left hand side
		$this->name = $name;
	}
	
	public function setAge($age)
	{
		$this->age = $age;
	}

	// a getter method aka accessor method
    // this returns the current value of of age
	public function getAge()
	{
		return $this->age;
	}

	public function getName()
	{
		return $this->name;
	}	
	
}

