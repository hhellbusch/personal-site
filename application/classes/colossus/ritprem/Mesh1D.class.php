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
	private $uniqueElements;

	public function __construct($x, $dx, $baseConcentration = null)
	{
		$this->x = $x;
		$this->dx = $dx;

		$numPoints = $x / $dx;
		$this->gridPoints = array();
		$this->uniqueElements = array();
		if (!is_null($baseConcentration))
		{
			for ($i = 0; $i < $numPoints; $i++)
			{
				$this->gridPoints[] = new GridPoint();
			}
			$this->addBaseConc($baseConcentration);
		}

	}

	public function addBaseConc(Concentration $concentration)
	{
		$this->addUniqueElement($concentration->getElement());
		for ($i = 0; $i < count($this->gridPoints); $i++)
		{
			$this->gridPoints[$i]->addDopant($concentration);
		}
	}

	public function addUniqueElement(Element $element)
	{
		$this->uniqueElements[$element->getFullName()] = $element;
	}

	public function getUniqueElements()
	{
		return $this->uniqueElements;
	}

	public function setUniqueElements(array $uniqueElements)
	{
		$this->uniqueElements = $uniqueElements;
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

	public function getDx_cm()
	{
		return $this->dx * pow(10, -4); //um to cm
	}

	public function getDose($elemName)
	{
		$sum = 0;
		foreach ($this->gridPoints as $gridPoint)
		{
			$dopants = $gridPoint->getDopants();
			foreach ($dopants as $dopant)
			{
				if ($dopant->getElementName() == strtolower($elemName))
					$sum += $dopant->getConcentration() * $this->getDx_cm();
			}
		}
		return $sum;
	}

	/**
	 * this is very brute force and could probably be optimized in some manner
	 *
	 * basically  it sweeps the grid looking for the points where the p type dopant
	 * and n type dopant differnce switches from neg to pos or pos to neg.
	 * when these points are found, intersection of the two lines is used.
	 *
	 * math originally figured out by drunk nate; fixed by sober henry
	 * @return double the point (depth in um) where the dopants intersect.
	 */
	public function getJunctionDepth()
	{
		$previousDifference = null;
		$previousGridPoint = null;
		foreach ($this->gridPoints as $i => $gridPoint)
		{
			if (is_null($previousGridPoint)) $previousGridPoint = $gridPoint;
			$difference = $previousGridPoint->getAcceptorConc() - $gridPoint->getDonorConc();
			
			if (!is_null($previousDifference))
			{
				//check to see if the signs are opposite
				if (
					($previousDifference > 0 && $difference < 0) 
					|| ($previousDifference < 0 && $difference > 0)
				) {
					$x1 = $this->dx * ($i - 1);
					$x2 = $this->dx * ($i);

					//find intersection point!
					$p2 = $gridPoint->getAcceptorConc();
					$p1 = $previousGridPoint->getAcceptorConc();
					$n2 = $gridPoint->getDonorConc();
					$n1 = $previousGridPoint->getDonorConc();

					$mn = ($n1-$n2)/($x1-$x2);
					$mp = ($p1-$p2)/($x1-$x2);

					$bp = $p1 - $mp * $x1;
					$bn = $n1 - $mn * $x1;

					$xj = ($bp - $bn)/($mn - $mp);
					return $xj;
				}
			}

			$previousDifference = $difference;
			$previousGridPoint = $gridPoint;
		}
		return 'unknown';
	}

	public function getSheetResistance()
	{
		$xj = $this->getJunctionDepth();
		$sumDepth = ceil($xj / $this->dx);
		$runningSum = 0;
		for ($i = 0; $i < $sumDepth; $i++)
		{
			$gridPoint = $this->gridPoints[$i];
			$runningSum = $runningSum + ($gridPoint->calcMobility() * $this->getDx_cm() * $gridPoint->getDominateDopingConc());
		}
		$runningSum = $runningSum * ELECTRON_CHARGE;
		return 1 / $runningSum;
	}

}

?>