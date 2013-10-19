<?php

namespace colossus\ritprem;

use colossus\ritprem\Mesh;
use colossus\ritprem\Concentration;
use colossus\ritprem\GridPoint;

class Mesh1D extends Mesh
{
	private $gridPoints;
	private $x;
	private $dx;

	public function __construct($x, $dx, $baseConcentration)
	{
		$this->gridPoints = array();
		$this->x = $x;
		$this->dx = $dx;

		$numPoints = $x / $dx;
		for ($i = 0; $i < $numPoints; $i++)
		{
			$this->gridPoints[$i] = new GridPoint();
			$this->gridPoints[$i]->addDopant($baseConcentration);
		}
	}

	/**
	 * Creates a data structure to pass directly into flot
	 * for graphing the dopant concentration.
	 */
	public function getFlotData()
	{

	}


}

?>
