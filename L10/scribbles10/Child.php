<?php

// the child class inherits public methods from the Person class
class Child extends Person
{
	private $toy;
	
	public function getToy()
	{
		return $this->toy;
	}
	
	public function setToy($toy)
	{
		$this->toy = $toy;
	}
}
