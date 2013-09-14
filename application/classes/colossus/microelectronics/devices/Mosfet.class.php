<?php

namespace colossus\microelectronics\devices;

use colossus\microelectronics\materials;
use \RuntimeException;
use colossus\microelectronics\PhysicsConstants;

abstract class Mosfet
{

	private $gateDielectric;
	private $gateMetal;
	private $wellDopingConc;
	private $relativePermitivity = 11.7;
	private $celsius = 20;
	private $intrinsicConcentration = 1.45e10;
	private $bandGap = 1.12; //eV

	abstract function calculateMetalWorkFunction();
	abstract function calculateIdealThresholdVoltage();

	public function calculateThresholdVoltage()
	{
		$fermiEnergy = $this->calculateFermiEnergy();
		$qsd_max = $this->calculateQsdMax();
		$vt = $this->calculateFlatBandVoltage() 
			+ $this->calculateIdealThresholdVoltage();
		return $vt;
	}

	public function getPermitivity()
	{
		return PERMITTIVITY_FREE_SPACE * $this->relativePermitivity;
	}

	public function calculateFermiEnergy()
	{
		if (is_null($this->wellDopingConc))
		{
			throw new RuntimeException("Unable to calculate fermi energy because the ".
				"doping concentration was not defined.");
		}

		return BOLTZMANN * $this->getKelvinTemperature() 
			* log($this->wellDopingConc / $this->intrinsicConcentration); 
		//units is volts
	}

	protected function calculateQsdMax()
	{
		return sqrt(
			4 * ELECTRON_CHARGE 
			* $this->getPermitivity() 
			* $this->wellDopingConc 
			* $this->calculateFermiEnergy()
		);
	}

	public function getKelvinTemperature()
	{
		return $this->celsius + 273.15;
	}

	public function setCelsiusTemperature($temp)
	{
		$this->celsius = $temp;
	}

	public function setDopingConcentration($concentration)
	{
		$this->wellDopingConc = $concentration;
	}

	public function setGateDielectric($dielectric)
	{
		$this->gateDielectric = $dielectric;
	}

	public function getBandGap()
	{
		return $this->bandGap;
	}


	public function calculateFlatBandVoltage()
	{
		//doesn't account for surface charges.
		return $this->calculateMetalWorkFunction();
	}

	public function getOxideCapicatance()
	{
		return $this->gateDielectric->getCapacitance();
	}


}

?>