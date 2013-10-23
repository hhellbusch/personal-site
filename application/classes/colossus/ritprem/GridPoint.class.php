<?php

namespace colossus\ritprem;

use colossus\ritprem\Concentration;

class GridPoint
{
	private $dopants = array();

	public function __construct()
	{

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
}

?>

