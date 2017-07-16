<?php
class Employee
{
    // private scope or instance variables
	private $firstname;
	private $lastname;
    private $employee_number;

	public function getFirstName() {
	    return $this->firstname;
	}

	public function getLastName() {
	    return $this->lastname;
	}

	public function getEmployeeNumber() {
		return $this->employee_number;
	}

	public function setFirstName($value) {
	    $this->firstname = $value;
	    return $this; //for chaining
	}
	public function setLastName($value) {
		$this->lastname = $value;
		return $this;
	}
	public function setEmployeeNumber($value) {
		$this->employee_number = $value;
		return $this;
	}

	function __construct($firstname=NULL, $lastname=NULL, $employee_number=NULL) {
		//defaults
		$this->firstname = $firstname ?: 'John'; //note "expr1 ?: expr3 returns expr1 if expr1 evaluates to TRUE, and expr3 otherwise."
		$this->lastname = $lastname ?: 'Doe';
		$this->employee_number = empty($employee_number) ? '001' : $employee_number;
	} //feedback from stackoverflow about using firstname lastname in the class design http://www.kalzumeus.com/2010/06/17/falsehoods-programmers-believe-about-names/
}


// below only commentary to future me about a failed attempt to make something work more automagically - the way I imagined it could.


	/*
		//overwrite with stuff from the new constructor if provided
		foreach ($members as $name =>value) {
	      $this->$name = $value;
	    }
	}

	//__get() is utilized for reading data from inaccessible properties, which all private ones should be from outside this scope...
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }



    //skipped constructor as no defaults were required by the instructions

    //BEWARE! MAGIC BELOW
	//$employee->firstname = 'x'; echo $employee->firstname;
	//these should both work with the functions below.

    //__get() is utilized for reading data from inaccessible properties
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    //__set() is run when writing data to inaccessible properties.
    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;//for chaining
    }

	//And one more thing section on https://www.phpied.com/javascript-style-object-literals-in-php/

	function __construct($members = array()) {
		//defaults
		$this->firstname = 'John';
		$this->lastname = "Doe";
		$this->employee_number = 001;
		//overwrite with stuff from the new constructor if provided
		foreach ($members as $name =>value) {
			$this->$name = $value;
		}
	}
	function __call($name, $args) {
		if (is_callable($this->$name)) {
			array_unshift($args, $this);
			return call_user_func_array($this->$name, $args);
		}
	}

    //I'm aware that it is 5x slower (which is still very fast), prevents autocompletion/type handling (or else requires additional handling and maintaining of @property phpdoc annotation), treats all getting and setting the same (which works here, but also could be handled http://www.beaconfire-red.com/epic-stuff/better-getters-and-setters-php ) and in a team everyone would have to understand the magic (here you're experienced and I'm alone). So, for the sake of this particular setting I think it's fine. - To show that I understand the normal way, I did that in the Custodian child class.

	//Ironically I couldn't find a good way to chain these. Say for example $employee->firstname = 'Dave'; - then what? A whole new row for the next set? Ha!

	//Tried finding a better solution and after chatting a bunch on stack overflow I found out that PHP people really aren't too lazy to repeat themselves, or too offended by visually repetitive code like I finally ended up writing above... So even though this is probably my only chance to abuse dynamic property magic methods to handle a few static properties  I gave it up for the sake of sanity. :D

	/*

	//Nonmagic improv - a real method
	public function set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
        return $this;//for chaining
    }

	//And sure, why not a constructor.
	public function __construct()
	{
		$this->firstname = 'John';
		$this->lastname = "Doe";
		$this->employee_number = 000;
	}
}

/*
class JSObject {
  function __construct($members = array()) {
    foreach ($members as $name =>value) {
      $this->$name = $value;
    }
  }
  function __call($name, $args) {
    if (is_callable($this->$name)) {
      array_unshift($args, $this);
      return call_user_func_array($this->$name, $args);
    }
  }
}
*/
