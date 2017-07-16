<?php
class Custodian extends Employee
{
    // private scope or instance variables
	private $certification_level = "Default";

	public function __construct($firstname=NULL, $lastname=NULL, $employee_number=NULL) {
		parent::__construct ($firstname, $lastname, $employee_number);
	}

    public function getCertificationLevel() {
        return $this->certification_level;
    }

    public function setCertificationLevel($value) {
        $this->certification_level = $value;
        //return $this; //future prepping for chaining
    }

}
