<?php

namespace colossus\ritprem;

use colossus\ritprem\Concentration;

class GridPoint
{
	private $dopants = array();

	public function __construct()
	{

	}

	public function addDopant(Concentration $concentration)
	{
		$this->dopants[$concentration->getElementName()] = $concentration;
	}

	public function getDopants()
	{
		return $this->dopants;
	}
}

?>
