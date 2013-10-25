<?php

namespace colossus\ritprem;

use colossus\ritprem\Mesh;
use colossus\ritprem\Concentration;
use colossus\ritprem\ElementFactory;
use colossus\ritprem\Element;
use \RuntimeException;

class Simulator
{

	private $temperature;
	private $duration; //seconds
	private $mesh;
	private $modelType;
	
	public function __construct()
	{
		$this->setDiffusitivyModel('constant');
	}

	public function setMesh(Mesh $mesh)
	{
		$this->mesh = clone $mesh;
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

	private function calcDt()
	{
		$dx = $this->mesh->getDx();
		$dx_cm = $dx * 1E-4;
		$maxDiffusivity = $this->mesh->getMaxDiffusivity($this->modelType, $this->temperature);
		
		$max_dt = pow($dx_cm, 2) / (2 * $maxDiffusivity);

		$n = ceil($this->duration/$max_dt); 
		//forces a finer time increment - produces a better graph
		if ($n < 10)
		{
			$n = ceil($this->duration/($max_dt/2));
		}
		$dt = $this->duration / $n;
		return $dt;
	}

	public function consantSurfaceSourceDiffuse(Concentration $surfaceConcentration)
	{
		$elemFactory = new ElementFactory();
		$topGridPoint = $this->mesh->shift();
		$this->mesh->unshift($topGridPoint);
		$surfaceGridPoint = clone $topGridPoint;
		$surfaceGridPoint->addDopant($surfaceConcentration);
		$dx = $this->mesh->getDx();
		$currentTime = 0;

		while($currentTime < $this->duration)
		{
			$this->mesh->unshift($surfaceGridPoint);
			$dt = $this->calcDt();
			$previousMesh = clone $this->mesh;
			$previousGridPoints = $previousMesh->getGridPoints();
			$numGridPoints = count ($previousGridPoints);
			for ($index = 0; $index < $numGridPoints; $index++)
			{
				$this->diffuseDopantsAtIndex($previousGridPoints, $index, $dt, $dx);
			}
			$this->mesh->shift();
			$this->mesh->prepareForNewTimeIncrement();
			$currentTime += $dt;
		}
	}

	public function diffuse()
	{
		$dx = $this->mesh->getDx();
		$dt = $this->calcDt();
		$CI =& get_instance();
		for ($currentTime = 0; $currentTime <= $this->duration; $currentTime += $dt)
		{
			$CI->benchmark->mark('diffuse_loop_'.$currentTime.'_start');
			$previousMesh = clone $this->mesh;
			$previousGridPoints = $previousMesh->getGridPoints();
			
			$numGridPoints = count ($previousGridPoints);
			for ($index = 0; $index < $numGridPoints; $index++)
			{
				$this->diffuseDopantsAtIndex($previousGridPoints, $index, $dt, $dx);
			}
		}
	}

	private function diffuseDopantsAtIndex(&$previousGridPoints, $i, $dt, $dx)
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
		$dopants = array_merge($dopants, $rightDopants, $leftDopants);
		foreach ($dopants as $dopantKey => $dopant)
		{
			//new conc = previous conc + D * dt / dx^2 * (neighborConc - 2* prevConc - otherNeighborConc)
			$previousConc = $dopant->getConcentration();
			$diffusivity = $dopant->getDiffusivity($this->temperature, $this->modelType);
			
			$validValue =  pow($dx_cm,2)/(2*$diffusivity); // must be less than dt
			$valid = ($dt <= $validValue);
			if (!$valid)
			{
				//trigger_error('model is borked.  write code to handle this!');
			}
			$changeCoef = $diffusivity * $dt / pow($dx_cm, 2);
			$leftConc = 0;
			$rightConc = 0;
			if (isset($leftDopants[$dopantKey]))
			{
				$leftConc = $leftDopants[$dopantKey]->getConcentration();
			}
			if (isset($rightDopants[$dopantKey]))
			{
				$rightConc = $rightDopants[$dopantKey]->getConcentration();
			}
			$newConc = $previousConc + $changeCoef * ($leftConc + $rightConc - 2 * $previousConc);
			if ($newConc < 0)
			{
				$data = array(
					'previousConc' => $previousConc,
					'rightConc' => $rightConc,
					'leftConc' => $leftConc,
					'diffusivity' => $diffusivity,
					'changeCoef' => $changeCoef,
					'temperature' => $this->temperature,
					'modelType' => $this->modelType,
					'dt' => $dt,
					'validValue' => $validValue,
					'valid' => $valid,
					'dx_cm' => $dx_cm,
					'newConc' => $newConc,
					'dopant' => $dopantKey,
					'index' => $i
				);
				
				echo 'woops negative concentration?  this is a bug.';
				echo ' please email hhellbusch@gmail.com';
				echo ' with detailed steps to reproduce (what the inputs were)';
				echo ' and the data outputted below.';
				var_dump($data);
				exit;
			}
			$newConcObj = new Concentration($dopant->getElement(), $newConc);
			
			$newGridPoint->addDopant($newConcObj);
		}
		if ($this->modelType == 'fermi')
		{
			// echo '------'.$i;
			// var_dump($newGridPoint);
		}
		
		
		
		$this->mesh->setGridPoint($i, $newGridPoint);
	}

	public function setDiffusitivyModel($model = 'constant')
	{
		$whiteList = array(
			'constant',
			'fermi'
		);
		if (in_array($model, $whiteList))
		{
			$this->modelType = $model;
		}
		else
		{
			throw new RuntimeException("Unable to set diffuseion model to " . $model);
		}

	}


	// public function setMethod($methodLevel)
	// {

	// }
}

?>
