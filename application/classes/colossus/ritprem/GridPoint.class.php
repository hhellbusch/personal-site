<?php

namespace colossus\ritprem;

use colossus\ritprem\Concentration;

class GridPoint
{
	private $dopants = array();
	private $material;

	public function __construct()
	{
		$this->material = 'silicon';
	}

	// public function __clone()
	// {
	// 	foreach ($this->dopants as $dopant)
	// 	{
	// 		$this->dopants[$dopant->getElementName()] = clone $dopant;
	// 	}
	// }

	public function addDopant(Concentration $concentration)
	{
		$this->dopants[$concentration->getElementName()] = clone $concentration; 
	}

	public function getDopants()
	{
		return $this->dopants;
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

}

?>

