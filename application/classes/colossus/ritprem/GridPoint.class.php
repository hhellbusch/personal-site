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
		//check to see if we already have a record of the dopant type
		//if we, add on to it
		//lazy search...
		$foundDopant = false;
		foreach ($this->dopants as $key => $dopant)
		{
			if ($dopant->getName() == $concentration->getName())
			{
				$this->dopants[$key]->add($concentration->getAmount());
				$foundDopant = true;
				break;
			}
		}

		if ($foundDopant)
		{
			$dopants[] = $concentration;
		}
	}

	public function getDopants()
	{
		return $dopants;
	}
}

?>
