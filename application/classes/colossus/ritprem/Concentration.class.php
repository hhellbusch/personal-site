<?php

namespace colossus\ritprem;

use colossus\ritprem\Element;
use \RuntimeException;
use \Exception;

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
		
		if ($this->amount == 0) return 0;
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
		elseif ($model == 'nate')
		{
			// D = D_0 * exp(-Ea/kT)
			// D_0 = \mu * k_b * T (einstien relation)
			$diffusitivy = ($this->calcMobility() * BOLTZMANN * $temperature
				* exp(
					-1 * $this->element->getActivationEnergy() 
					/ (BOLTZMANN * $temperature)
				)
			);
			
		}
		elseif($model == 'fermi')
		{
			$ni = $this->calcIntrinsicCarrierConc($temperature);
			$fermiParams = $this->element->getFermiDiffusivityParams();
			$d0      = $fermiParams['d0_0']      * exp(-1 * $fermiParams['d0_e']      / (BOLTZMANN * $temperature));
			$dsingle = $fermiParams['dsingle_0'] * exp(-1 * $fermiParams['dsingle_e'] / (BOLTZMANN * $temperature));
			$ddouble = $fermiParams['ddouble_0'] * exp(-1 * $fermiParams['ddouble_e'] / (BOLTZMANN * $temperature));
			$diffusitivy = $d0 + ($dsingle * $this->amount / $ni) + ($ddouble * pow($this->amount / $ni, 2));
		}
		else
		{
			throw new RuntimeException("diffusitivy model {" . $model . '} is not defined');
		}
		return $diffusitivy;
	}

	private function calcIntrinsicCarrierConc($temp)
	{
		//http://www.stevesque.com/calculators/intrinsic-carrier-concentration/
		$a1 = 3.1e16;
		$a2 = 7000;
		return $a1 * pow($temp, 3/2) * exp(-1 * $a2 / $temp);
	}
}

?>
