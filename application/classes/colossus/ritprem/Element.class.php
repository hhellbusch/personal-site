<?php

namespace colossus\ritprem;


class Element
{
	
	private $name;
	private $symbol;
	private $atomicWeight;
	private $atomicNumber;
	private $diffusionCoef;
	private $activationEnergy;

	public function __construct()
	{

	}

	public function equal(Element $other)
	{
		return ($other->getFullName() == $this->getFullName());
	}

	public function getFullName()
	{
		return $this->name;
	}

	public function getSymbol()
	{
		return $this->symbol;
	}

	public function getDiffusionCoef()
	{
		return $this->diffusionCoef;
	}

	public function getActivationEnergy()
	{
		return $this->activationEnergy;
	}

	/**
	 * [getDiffusivity description]
	 * @param  [type] $temperature IN KELVIN
	 * @return [type]              [description]
	 */
	public function getDiffusivity($temperature)
	{
		return $this->diffusionCoef * 
			exp(-1 * $this->activationEnergy / (BOLTZMANN * $temperature));
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

	public function setDiffusionCoef($diffusionCoef)
	{
		$this->diffusionCoef = $diffusionCoef;
	}

	public function setActivationEnergy($activationEnergy)
	{
		$this->activationEnergy = $activationEnergy;
	}
}

?>