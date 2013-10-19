<?php

namespace colossus\ritprem;

use colossus\ritprem\Element;

class Concentration
{
	private $element;
	private $amount;

	public function __construct(Element $element, $amount)
	{
		$this->element = $element;
		$this->amount = $amount;
	}

	public function getElement()
	{
		return $element;
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
