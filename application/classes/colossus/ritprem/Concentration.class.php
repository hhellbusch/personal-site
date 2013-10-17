<?php

namespace colossus\riteprem;

class Concentration
{
	private $name;
	private $amount;

	public function __construct($name, $amount)
	{
		$this->name = $name;
		$this->amount = $amount;
	}

	public function getName()
	{
		return $name;
	}

	public function getAmount()
	{
		return $amount;
	}

	public function add($amount)
	{
		$this->amount += $amount;
	}

	public function subtract($amount)
	{
		$this->amount -= $amount;
	}
}

?>
