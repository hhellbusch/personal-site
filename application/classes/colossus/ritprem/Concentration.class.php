<?php

namespace colossus\ritprem;

use colossus\ritprem\Element;
use \RuntimeException;

class Concentration
{
	private $element;
	private $amount;

	public function __construct(Element $element, $amount)
	{
		$this->element = $element;
		$this->amount = $amount;
		if ($this->amount < 0)
		{
			throw new RuntimeException("Concentration cannot be negative!");
		}
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

	public function calcMobility()
	{
		$mobility = 0;
		$mobilityParams = $this->element->getMobilityParams();
		$mu_0   = $mobilityParams['mu_0'];
		$mu_max = $mobilityParams['mu_max'];
		$mu_1   = $mobilityParams['mu_1'];
		$c_r    = $mobilityParams['c_r'];
		$c_s    = $mobilityParams['c_s'];
		$alpha  = $mobilityParams['alpha'];
		$beta   = $mobilityParams['beta'];
		//if ($this->amount == 0) return 0;
		
		if ($this->element->isDonor())
		{
			$mobility = $mu_0 
				+ ($mu_max - $mu_0)/(1+pow(($this->amount/$c_r),$alpha))
				- $mu_1 / (1 + pow( $c_s/$this->amount ,$beta));
		}
		elseif ($this->isAcceptor())
		{
			$p_c = $mobilityParams['p_c'];
			$mobility = $mu_0 * exp(-1 * $p_c / $this->amount)
				+ ($mu_max)/(1+pow(($this->amount/$c_r),$alpha))
				- $mu_1 / (1 + pow( $c_s/$this->amount ,$beta));
		}
		else
		{
			throw new Exception("i dont know how to calculate the mobility for this element!");
		}
		
		return $mobility;
	}

	public function getDiffusivity($temperature, $model = 'constant')
	{
		$diffusitivy = 0;
		if ($model == 'constant')
		{

			$diffusitivy = $this->element->getDiffusivity($temperature);
		}
		elseif ($model == 'fermi')
		{
			$diffusitivy = ($this->calcMobility() * BOLTZMANN * $temperature
				* exp(
					-1 * $this->element->getActivationEnergy() 
					/ (BOLTZMANN * $temperature)
				)
			);
		}
		else
		{
			throw new RuntimeException("diffusitivy model {" . $model . '} is not defined');
		}
		return $diffusitivy;
	}
}

?>
