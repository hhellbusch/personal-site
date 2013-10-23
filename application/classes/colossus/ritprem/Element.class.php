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
	private $dopantType;

	public function __construct()
	{

	}

	public function equal(Element $other)
	{
		return ($other->getFullName() == $this->getFullName());
	}

	public function setDopantType($dopantType)
	{
		$this->dopantType = $dopantType;
	}

	public function getDopantType()
	{
		return $this->dopantType;
	}

	public function isDonor()
	{
		return $this->getDopantType() == 'n';
	}

	public function isAcceptor()
	{
		return $this->getDopantType() == 'p';
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

	//implant stuff
	public function getProjectedRange($energy)
	{
		$func = $this->calcProjectedRange;
		return $func($energy);
	}

	public function getStraggle($energy)
	{
		$func = $this->calcStraggle;
		return $func($energy);
	}

	public function setStraggleFunc($func)
	{
		$this->calcStraggle = $func;
	}
	public function setProjectedRangeFunc($func)
	{
		$this->calcProjectedRange = $func;
	}

}

?>