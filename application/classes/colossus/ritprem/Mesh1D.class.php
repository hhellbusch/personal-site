<?php

namespace colossus\ritprem;

use colossus\ritprem\Mesh;
use colossus\ritprem\Concentration;
use colossus\ritprem\GridPoint;
use colossus\ritprem\Element;
use \RuntimeException;
use \Exception;

class Mesh1D extends Mesh
{
	private $gridPoints;
	private $x;
	private $dx;
	private $uniqueElements;
	private $maxDiffusivitity;
	private $numOxidePoints;
	private $maxTransport;

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
		$this->numOxidePoints = 0;
	}

	public function getMaxDiffusivity($model = 'constant', $temperature)
	{
		if (is_null($this->maxDiffusivitity))
		{
			$constantModel = false;
			if ($model == 'constant') $constantModel = true;
			$diffusivities = array();
			foreach ($this->gridPoints as $gridPoint)
			{
				foreach ($gridPoint->getDopants() as $dopant)
				{
					try
					{ 
						$diffusivities[] = $dopant->getDiffusivity($temperature, $model);
					}
					catch (Exception $e)
					{
					}
					
					if ($constantModel) break;
				}
			}
			$this->maxDiffusivitity = max($diffusivities);
		}
		return $this->maxDiffusivitity;
	}

	public function getMaxTransport($temperature)
	{
		if (is_null($this->maxTransport))
		{
			$transports = array();
			foreach ($this->gridPoints as $gridPoint)
			{
				foreach ($gridPoint->getDopants() as $dopant)
				{
					$transports[] = $dopant->getElement()->getTransport($temperature);
					break;
				}
			}
			$this->maxTransport = max($transports);
		}
		return $this->maxTransport;
	}


	public function prepareForNewTimeIncrement()
	{
		$this->maxDiffusivitity = null;
	}

	public function addSurfaceOxide($oxideThickness)
	{
		//convert oxide thickness from angstroms to um
		$oxideThickness = 0.0001 * $oxideThickness;
		$numOxidePoints = ceil($oxideThickness / $this->dx);
		for ($i = 0; $i < $numOxidePoints; $i++)
		{
			$oxidePoint = new GridPoint();
			$oxidePoint->setMaterial("SiO2");
			$this->unshift($oxidePoint);
		}
		$this->numOxidePoints = $numOxidePoints;
	}

	public function hasOxide()
	{
		return $this->numOxidePoints > 0;
	}


	public function setGridPoint($i,GridPoint $gridPoint)
	{
		if ($i >= 0 && $i < count($this->gridPoints))
		{	
			$this->gridPoints[$i] = $gridPoint;
		}
		else
		{
			throw new RuntimeException("Index out of bounds");
		}
	}

	public function addDopantToGridPoint($i, Concentration $conc)
	{
		if ($i >= 0 && $i < count($this->gridPoints))
		{	
			$this->gridPoints[$i]->addDopant($conc);
		}
		else
		{
			var_dump($i);
			var_dump(count($this->gridPoints));
			throw new RuntimeException("Index out of bounds");
		}
	}

	public function getDopantAtGridPoint($i, $dopantKey)
	{
		$amount = 'n/a';
		if ($i >= 0 && $i < count($this->gridPoints))
		{	
			$amount = $this->gridPoints[$i]->getAmountOfDopant($dopantKey);
		}
		return $amount;
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

	public function getFlotMarkings()
	{
		$graphMarkings = array();
		$baseMark = array(
			'color' => "#99ccff"
		);
		$previousGridPoint = null;
		$lastMaterialStart = 0;
		foreach ($this->gridPoints as $i => $gridPoint)
		{
			if (is_null($previousGridPoint))
			{
				$previousGridPoint = $gridPoint;
			}
			if ($previousGridPoint->isInterface($gridPoint))
			{
				$x = $i * $this->dx;
				$xaxis = array(
					'from' => $lastMaterialStart, 
					'to' => $x
				);
				
				$mark = $baseMark;
				$mark['xaxis'] = $xaxis;
				$graphMarkings[] = $mark;
				$lastMaterialStart = $x;
			}
			$previousGridPoint = $gridPoint;
		}
		return $graphMarkings;
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
		$dopants = $newPoint->getDopants();
		foreach ($dopants as $dopant)
		{
			$this->addUniqueElement($dopant->getElement());
		}
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
					return $xj ;
				}
			}

			$previousDifference = $difference;
			$previousGridPoint = $gridPoint;
		}
		return 'unknown';
	}

	public function getJunctionDepthRelativeToSiSurface()
	{
		$junctionDepth = $this->getJunctionDepth();
		if (is_numeric($junctionDepth))
			return  $junctionDepth - ($this->numOxidePoints * $this->dx);
		else return $junctionDepth;
	}

	public function getSheetResistance()
	{
		$xj = $this->getJunctionDepth();
		$sumDepth = ceil($xj / $this->dx);
		$runningSum = 0;
		for ($i = 0; $i < $sumDepth; $i++)
		{
			$gridPoint = $this->gridPoints[$i];
			if ($gridPoint->getMaterial() != 'SiO2')
				$runningSum = $runningSum + ($gridPoint->calcMobility() * $this->getDx_cm() * $gridPoint->getDominateDopingConc());
		}
		$runningSum = $runningSum * ELECTRON_CHARGE;
		
		$sheetResistance = 'unknown';
		if ($runningSum > 0)
			$sheetResistance = 1 / $runningSum;
		return $sheetResistance;
	}

}

?>