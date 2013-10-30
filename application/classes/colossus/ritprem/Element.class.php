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
	private $mobilityParams;
	private $segregationCoef;
	private $segregation_e;
	private $transport_e;
	private $transportCoef;
	private $fermiDiffusionParams;

	public function __construct()
	{

	}

	public function setFermiDiffusivityParams($params)
	{
		$this->fermiDiffusionParams = $params;

	}

	public function getFermiDiffusivityParams()
	{
		return $this->fermiDiffusionParams;
	}

	public function setSegregationCoef($segregationCoef)
	{
		$this->segregationCoef = $segregationCoef;
	}

	public function setSegregationExponent($segregation_e)
	{
		$this->segregation_e = $segregation_e;
	}

	public function setTransportCoef($transportCoef)
	{
		$this->transportCoef = $transportCoef;
	}

	public function setTransportExponent($transport_e)
	{
		$this->transport_e = $transport_e;
	}

	public function getTransportCoef()
	{
		return $this->transportCoef;
	}

	public function getSegregationCoef()
	{
		return $this->segregationCoef;
	}

	public function getSegregationExponent()
	{
		return $this->segregation_e;
	}

	public function getTransportExponent()
	{
		return $this->transport_e;
	}

	public function getTransport($temperature)
	{
		$kt = (BOLTZMANN * $temperature);
		return $this->getTransportCoef() * exp(-1 * $this->getTransportExponent() / $kt);
	}

	public function getSegregation($temperature)
	{
		$kt = (BOLTZMANN * $temperature);
		return $this->getSegregationCoef() * exp(-1 * $this->getSegregationExponent() / $kt);
	}




	public function setMobilityParams($mobilityParams)
	{
		$this->mobilityParams = $mobilityParams;
	}

	public function getMobilityParams()
	{
		return $this->mobilityParams;
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