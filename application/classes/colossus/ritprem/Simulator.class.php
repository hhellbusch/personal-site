<?php

namespace colossus\ritprem;

use colossus\ritprem\Mesh;
use colossus\ritprem\Concentration;
use colossus\ritprem\ElementFactory;
use colossus\ritprem\Element;

class Simulator
{

	private $temperature;
	private $duration; //seconds
	private $mesh;
	private $modelType;
	
	public function __construct()
	{

	}

	public function setMesh(Mesh $mesh)
	{
		$this->mesh = clone $mesh;
	}

	public function simulate()
	{

	}

	public function setTemperature($temp) 
	{
		$this->temperature = $temp;
	}

	public function setDuration($duration)
	{
		$this->duration = $duration;
	}

	public function getMesh()
	{
		return $this->mesh;
	}

	public function consantSurfaceSource(Concentration $surfaceConcentration)
	{
		$elemFactory = new ElementFactory();
		$topGridPoint = $this->mesh->shift();
		$this->mesh->unshift($topGridPoint);
		$surfaceGridPoint = clone $topGridPoint;
		$surfaceGridPoint->addDopant($surfaceConcentration);
	
		$zeroConc = new Concentration($surfaceConcentration->getElement(), 0);
		$this->mesh->addBaseConc($zeroConc);
	
		$element = $surfaceConcentration->getElement();
		$dx = $this->mesh->getDx();
		$dx_cm = $dx * 1E-4;
		$diffusivity = $element->getDiffusivity($this->temperature);
		$dt = pow($dx_cm, 2) / (2 * $diffusivity);
		//change dt such that it properly fits into the given duration
		//dont want to simulate duration * 1.2 instead of just duration.

		
		
		//for all time
		for ($currentTime = 0; $currentTime < $this->duration; $currentTime += $dt)
		{
			// echo '<h2>new time loop! </h2>';
			$previousMesh = clone $this->mesh;
			$newMesh = new Mesh1D($this->mesh->getX(), $this->mesh->getDx());
			
			$previousMesh->unshift($surfaceGridPoint);
			$previousGridPoints = $previousMesh->getGridPoints();
			
			// for each point
			// echo 'looking at grid points....<br />';
			// echo 'input: <br />';

			foreach ($previousGridPoints as $i => $previousGridPoint)
			{
				// echo '<hr />';
				// echo 'looking at! :'.$i.' <br />';
				// var_dump($previousGridPoint);
				$dopants = $previousGridPoint->getDopants();
				$leftPoint = $previousGridPoint;
				if (isset($previousGridPoints[$i - 1]))
				{
					$leftPoint = $previousGridPoints[$i -1];
				}
				$rightPoint = $previousGridPoint;
				if (isset($previousGridPoints[$i + 1]))
				{
					$rightPoint = $previousGridPoints[$i + 1];
				}
				// echo 'left point:<br />';
				// var_dump($leftPoint);
				// echo 'right point:<br />';
				// var_dump($rightPoint);
				$rightDopants = $rightPoint->getDopants();
				$leftDopants = $leftPoint->getDopants();
				
				//for each dopant at each point
				$newGridPoint = new GridPoint();
				foreach ($dopants as $dopantKey => $dopant)
				{
					//new conc = previous conc + D * dt / dx^2 * (neighborConc - 2* prevConc - otherNeighborConc)
					$previousConc = $dopant->getConcentration();
					$changeCoef = $dopant->getElement()->getDiffusivity($this->temperature) * $dt / pow($dx_cm, 2);
					
					$leftCoef = $leftDopants[$dopantKey]->getConcentration();
					$rightCoef = $rightDopants[$dopantKey]->getConcentration();
					$newConc = $previousConc + $changeCoef * ($leftCoef + $rightCoef - 2 * $previousConc);
					$newConcObj = new Concentration($dopant->getElement(), $newConc);
					
					$newGridPoint->addDopant($newConcObj);
				}
				// echo 'new grid point:<br />';
				// var_dump($newGridPoint);
				$newMesh->push($newGridPoint);
			}
			//var_dump($newMesh);
			$newMesh->shift();
			$this->mesh = clone $newMesh;
		} // end for all time
		// var_dump($this->mesh);
	}

	public function setDiffusitivy()
	{
		
	}


	// public function setMethod($methodLevel)
	// {

	// }
}

?>
