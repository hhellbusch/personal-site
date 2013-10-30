<?php

namespace colossus\ritprem;

use colossus\ritprem\Concentration;

class GridPoint
{
	private $dopants = array();
	private $material;
	private $time;

	public function __construct()
	{
		$this->material = 'silicon';
	}

	public function setTime($time)
	{
		$this->time = $time;
	}

	public function getTime()
	{
		return $this->time;
	}

	public function isInterface(GridPoint $gridPoint)
	{
		return ($gridPoint->material != $this->material);
	}

	public function setMaterial($material)
	{
		if ($material == 'SiO2' || $material == 'silicon')
		{
			$this->material = $material;
		}
		else
		{
			throw new RuntimeException("can't set material to " . $material . " because it isn't defined");
		}
	}

	public function getMaterial()
	{
		return $this->material;
	}

	public function addDopant(Concentration $concentration)
	{
		if (isset($this->dopants[$concentration->getElementName()]))
		{
			$this->dopants[$concentration->getElementName()]->add($concentration->getAmount());
		}
		else
		{
			$this->dopants[$concentration->getElementName()] = clone $concentration; 
		}
	}

	public function setDopantConcentration(Concentration $concentration)
	{
		$this->dopants[$concentration->getElementName()] = clone $concentration; 
	}

	public function getDopants()
	{
		return $this->dopants;
	}

	public function getAmountOfDopant($key)
	{
		$amount = 'n/a';
		if (isset($this->dopants[$key]))
		{
			$amount = $this->dopants[$key]->getAmount();
		}
		return $amount;
	}

	public function getAcceptorConc()
	{
		$sum = 0;
		foreach ($this->dopants as $dopant)
		{
			if ($dopant->isAcceptor())
			{
				$sum += $dopant->getConcentration();
			}
		}
		return $sum;
	}

	public function getDonorConc()
	{
		$sum = 0;
		foreach ($this->dopants as $dopant)
		{
			if ($dopant->isDonor())
			{
				$sum += $dopant->getConcentration();
			}
		}
		return $sum;
	}

	/**
	 * assumes the more concentrated dominates.
	 * @return [type] [description]
	 */
	public function calcMobility()
	{
		$maxDopant = $this->getDominateDoping();
		return $maxDopant->calcMobility();
	}

	public function getDominateDoping()
	{
		$maxDopant = null;
		foreach ($this->dopants as $dopant)
		{
			if (is_null($maxDopant)) $maxDopant = $dopant;
			if ($dopant->getAmount() >= $maxDopant->getAmount())
			{
				$maxDopant = $dopant;
			}
		}
		return $maxDopant;
	}

	public function getDominateDopingConc()
	{
		$maxDopant = $this->getDominateDoping();
		return $maxDopant->getConcentration();
	}

}

?>

