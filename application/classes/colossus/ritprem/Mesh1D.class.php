<?php

namespace colossus\ritprem;

use colossus\ritprem\Mesh;
use colossus\ritprem\Concentration;
use colossus\ritprem\GridPoint;
use colossus\ritprem\Element;

class Mesh1D extends Mesh
{
	private $gridPoints;
	private $x;
	private $dx;
	private $dopantElements;


	public function __construct($x, $dx, $baseConcentration = null)
	{
		$this->x = $x;
		$this->dx = $dx;

		$numPoints = $x / $dx;
		$this->gridPoints = array();
		if (!is_null($baseConcentration))
		{
			$this->gridPoints = array_pad(array(),$numPoints, new GridPoint());
			$this->addBaseConc($baseConcentration);
		}
	}

	public function addBaseConc(Concentration $concentration)
	{
		for ($i = 0; $i < count($this->gridPoints); $i++)
		{
			$this->gridPoints[$i]->addDopant($concentration);
		}
	}

	private function addDopantType(Element $element)
	{
		$this->dopantElements[] = $element;
	}

	/**
	 * Creates a data structure to pass directly into flot
	 * for graphing the dopant concentration.
	 */
	public function getFlotData()
	{
		/**
		 * flotData needs to look like:
		 * array(
		 * 	'label' => 'labelname'
		 * 	'data' => array(
		 * 		array(x, y),
		 * 		array(x, y),
		 * 		....
		 * 	)
		 * )
		 */
		$flotData = array();
		
		$organizedData = array();
		$numGridPoints = count($this->gridPoints);
		for ($i = 0; $i < $numGridPoints; $i++)
		{
			$gridPoint = $this->gridPoints[$i];
			$dopants = $gridPoint->getDopants();
			foreach ($dopants as $dopant)
			{
				if ($dopant->getConcentration() > 1E9)
				{
					$organizedData[$dopant->getElement()->getFullName()][] = 
						array($i * $this->dx, $dopant->getConcentration());
				}
			}
		}


		foreach ($organizedData as $label => $data)
		{
			$flotData[] = array(
				'label' => $label,
				'data' => $data
			);
		}

		return $flotData;
	}

	public function getDx()
	{
		return $this->dx;
	}
	public function getX()
	{
		return $this->x;
	}

	public function getGridPoints()
	{
		return $this->gridPoints;
	}

	public function unshift(GridPoint $newPoint)
	{
		array_unshift($this->gridPoints, $newPoint);
	}

	public function shift()
	{
		return array_shift($this->gridPoints);
	}

	public function push(GridPoint $newPoint)
	{
		array_push($this->gridPoints, $newPoint);
	}

}

?>
