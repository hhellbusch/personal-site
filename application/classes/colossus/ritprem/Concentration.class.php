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
		return $this->element;
	}

	public function getElementName()
	{
		return $this->element->getFullName();
	}

	public function isDonor()
	{
		return $this->element->isDonor();
	}

	public function isAcceptor()
	{
		return $this->element->isAcceptor();
	}

	public function getAmount()
	{
		return $this->amount;
	}

	public function getConcentration()
	{
		return $this->getAmount();
	}

	public function add($amount)
	{
		$this->amount += $amount;
	}

	public function setConcentration($amount)
	{
		$this->amount = $amount;
	}

	public function subtract($amount)
	{
		$this->amount -= $amount;
	}
}

?>
