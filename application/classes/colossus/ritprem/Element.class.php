<?php

namespace colossus\ritprem;


class Element
{
	
	private $name;
	private $symbol;
	private $atomicWeight;
	private $atomicNumber;

	public function __construct()
	{

	}

	public function setFullName($name)
	{
		$this->name = $name;
	}
	
	public function setSymbol($symbol)
	{
		$this->symbol = $symbol;
	}
		
	public function setAtomicWeight($weight)
	{
		$this->atomicWeight = $weight;
	}
		
	public function setAtomicNumber($number)
	{
		$this->atomicNumber = $number;
	}
}

?>