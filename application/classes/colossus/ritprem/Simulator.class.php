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

	private function calcDt($element)
	{
		$dx = $this->mesh->getDx();
		$dx_cm = $dx * 1E-4;
		$diffusivity = $element->getDiffusivity($this->temperature);
		$max_dt = pow($dx_cm, 2) / (2 * $diffusivity);

		$n = ceil($this->duration/$max_dt) + 10; //forces a finer time increment - produces a better graph
		$dt = $this->duration / $n;
		return $dt;
	}

	public function consantSurfaceSource(Concentration $surfaceConcentration)
	{
		$elemFactory = new ElementFactory();
		$topGridPoint = $this->mesh->shift();
		$this->mesh->unshift($topGridPoint);
		$surfaceGridPoint = clone $topGridPoint;
		$surfaceGridPoint->addDopant($surfaceConcentration);
		$dx = $this->mesh->getDx();
		$zeroConc = new Concentration($surfaceConcentration->getElement(), 0);
		$this->mesh->addBaseConc($zeroConc);
	
		$element = $surfaceConcentration->getElement();
		$dt = $this->calcDt($element);
		
		for ($currentTime = 0; $currentTime < $this->duration; $currentTime += $dt)
		{
			$previousMesh = clone $this->mesh;
			$newMesh = new Mesh1D($this->mesh->getX(), $this->mesh->getDx());
			
			$previousMesh->unshift($surfaceGridPoint);
			$previousGridPoints = $previousMesh->getGridPoints();
			$numGridPoints = count ($previousGridPoints);
			for ($index = 0; $index < $numGridPoints; $index++)
			{
				$newGridPoint = $this->diffuseDopantsAtIndex($previousGridPoints, $index, $dt, $dx);
				$newMesh->push($newGridPoint);
			}
			$newMesh->shift();
			$this->mesh = clone $newMesh;
		}
	}

	private function diffuseDopantsAtIndex($previousGridPoints, $i, $dt, $dx)
	{
		$dx_cm = $dx * 1E-4;
		$previousGridPoint = $previousGridPoints[$i];
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
		return $newGridPoint;
	}

	public function setDiffusitivy()
	{
		
	}


	// public function setMethod($methodLevel)
	// {

	// }
}

?>
