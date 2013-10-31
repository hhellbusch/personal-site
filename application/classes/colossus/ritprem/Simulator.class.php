<?php

namespace colossus\ritprem;

use colossus\ritprem\Mesh;
use colossus\ritprem\Concentration;
use colossus\ritprem\ElementFactory;
use colossus\ritprem\Element;
use \RuntimeException;
use \Exception;

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
		if ($this->mesh->hasOxide())
		{
			return $dx_cm / (2*$this->mesh->getMaxTransport($this->temperature));
		}
		$maxDiffusivity = $this->mesh->getMaxDiffusivity($this->modelType, $this->temperature);
		
		$dt = pow($dx_cm, 2) / (2 * $maxDiffusivity);
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
		log_message('Simulator::consantSurfaceSourceDiffuse', 'debug');
		while($currentTime < $this->duration)
		{
			$this->mesh->unshift($surfaceGridPoint);
			$dt = $this->calcDt();
			$previousMesh = clone $this->mesh;
			$previousGridPoints = $previousMesh->getGridPoints();
			$numGridPoints = count ($previousGridPoints);
			for ($index = 0; $index < $numGridPoints; $index++)
			{
				$this->currentTime = $currentTime;
				$this->diffuseDopantsAtIndex($previousGridPoints, $index, $dt, $dx, $currentTime);
			}
			$this->mesh->shift();
			$this->mesh->prepareForNewTimeIncrement();
			$currentTime += $dt;
			log_message('debug','t:'.$currentTime);
		}
	}

	public function diffuse()
	{
		$dx = $this->mesh->getDx();
		
		$CI =& get_instance();

		$currentTime = 0;
		log_message('Simulator::diffuse', 'debug');
		$loopCount = 0;
		$dt = 0;
		while ($currentTime < $this->duration)
		{
			$dt = $this->calcDt();
			//var_dump($dt);
			$CI->benchmark->mark('diffuse_loop_'.$currentTime.'_start');
			$previousMesh = clone $this->mesh;
			$previousGridPoints = $previousMesh->getGridPoints();
			
			$numGridPoints = count ($previousGridPoints);
			for ($index = 0; $index < $numGridPoints; $index++)
			{
				$this->currentTime = $currentTime;
				$this->diffuseDopantsAtIndex($previousGridPoints, $index, $dt, $dx, $currentTime);
			}
			$this->mesh->prepareForNewTimeIncrement();
			$currentTime += $dt;
			$loopCount++;
			//if ($loopCount == 500) break;
		}
		//var_dump($dt);

		// foreach ($this->mesh->getGridPoints() as $i => $gridPoint)
		// {
		// 	$dopants = $gridPoint->getDopants();
		// 	$prettyDopants = array();
		// 	foreach ($dopants as $key => $dopant)
		// 	{
		// 		$prettyDopants[$key] = array(
		// 			'amount' => $dopant->getAmount(),
		// 			'segregation' =>$dopant->getElement()->getSegregation($this->temperature),
		// 			'transport' =>$dopant->getElement()->getTransport($this->temperature)
		// 		);
		// 	}
		// 	$data = array(
		// 			'index' => $i,
		// 			'dopant' => $prettyDopants,
		// 			'material' => $gridPoint->getMaterial()
		// 	);
		// 	//var_dump($data);
		// }
	}

	private function diffuseDopantsAtIndex(&$gridPoints, $i, $dt, $dx, $currentTime)
	{
		$dx_cm = $dx * 1E-4;
		$gridPoint = $gridPoints[$i];
		$dopants = $gridPoint->getDopants();
		$leftPoint = $gridPoint;
		if (isset($gridPoints[$i - 1]))
		{
			$leftPoint = $gridPoints[$i -1];
		}
		$rightPoint = $gridPoint;
		if (isset($gridPoints[$i + 1]))
		{
			$rightPoint = $gridPoints[$i + 1];
		}

		
		$rightDopants = $rightPoint->getDopants();
		$leftDopants = $leftPoint->getDopants();

		//for each dopant at each point
		$dopantKeys = array();
		$dopantKeys += array_keys($rightDopants);
		$dopantKeys += array_keys($leftDopants);
		
		foreach ($dopantKeys as $dopantKey)
		{
			if (!isset($dopants[$dopantKey])) continue;
			$dopant = $dopants[$dopantKey];
			$previousConc = $dopant->getConcentration();
			$diffusivity = $dopant->getDiffusivity($this->temperature, $this->modelType);
			$rightDiffusivity = $diffusivity;
			$leftDiffusivity = $diffusivity;

			$changeCoef = $dt / pow($dx_cm, 2);
			
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

			//determine the parts of the new conc
			$rightChangeCoef = $rightDiffusivity * $changeCoef;
			if ($this->mesh->atInterface($i) && $gridPoint->getMaterial() == 'SiO2')
			{
				$rightSegregation = $dopant->getElement()->getSegregation($this->temperature);
				$rightTransport = -1*$dopant->getElement()->getTransport($this->temperature);

				$rightConc = $rightConc / $rightSegregation;
				$rightChangeCoef = $rightTransport * $dt / pow($dx_cm,1);
			}
			$rightConcDiff = $rightConc - $previousConc;


			$leftChangeCoef = $leftDiffusivity * $changeCoef;
			if ($this->mesh->atInterface($i) && $gridPoint->getMaterial() == 'silicon')
			{
				$leftSegregation = $dopant->getElement()->getSegregation($this->temperature);
				$leftTransport = $dopant->getElement()->getTransport($this->temperature)/$leftSegregation;

				$leftConc = $leftConc * $leftSegregation;
				$leftChangeCoef = $leftTransport * $dt / pow($dx_cm,1);
			}
			$leftConcDiff = $leftConc - $previousConc;
			
			$dopantMovingFromLeft = ($leftChangeCoef * $leftConcDiff);
			$dopantMovingFromRight = ($rightChangeCoef * $rightConcDiff);
			$addToCurrent = 0;

			if (($i + 1) < count($gridPoints))
			{
				$right = new Concentration($dopant->getElement(), -1 * $dopantMovingFromRight);
				$this->mesh->addDopantToGridPoint($i + 1, $right);
				$addToCurrent = $addToCurrent +  $dopantMovingFromRight;
			}

			if ($i > 0)
			{
				$left = new Concentration($dopant->getElement(), -1 * $dopantMovingFromLeft);
				$this->mesh->addDopantToGridPoint($i - 1, $left);
				$addToCurrent = $addToCurrent + $dopantMovingFromLeft;
				
			}

			
			$current = new Concentration($dopant->getElement(), $addToCurrent);
			$this->mesh->addDopantToGridPoint($i, $current);
			

			// $data = array(
			// 		'index' => $i,
			// 		'leftDelta' => $dopantMovingFromLeft,
			// 		'leftConcOld' => $leftConc,
			// 		'leftConcNew' => $this->mesh->getDopantAtGridPoint($i - 1, $dopantKey),
			// 		'rightDelta' => $dopantMovingFromRight,
			// 		'rightConcOld' => $rightConc,
			// 		'rightConcNew' => $this->mesh->getDopantAtGridPoint($i + 1, $dopantKey),
			// 		'changeToCurrent' => $addToCurrent,
			// 		'currentConcOld' => $previousConc,
			// 		'currentConcNew' => $this->mesh->getDopantAtGridPoint($i, $dopantKey),
			// 		'dopant' => $dopantKey,
			// 		'leftMaterial' => $leftPoint->getMaterial(),
			// 		'material' => $gridPoint->getMaterial(),
			// 		'rightMaterial' => $rightPoint->getMaterial()
			// );
			//var_dump($data);
			

			if ($diffusivity * $dt / pow($dx, 2) > .5)
			{
				var_dump($diffusivity);

				throw new Exception("model went out of bounds!");
			}


			// if ($newConc < 0 || is_infinite($newConc))
			// {
			// 	$data = array(
			// 		'previousConc' => $previousConc,
			// 		'rightConc' => $rightConc,
			// 		'leftConc' => $leftConc,
			// 		'diffusivity' => $diffusivity,
			// 		'changeCoef' => $changeCoef,
			// 		'temperature' => $this->temperature,
			// 		'modelType' => $this->modelType,
			// 		'dt' => $dt,
			// 		//'validValue' => $validValue,
			// 		//'valid' => $valid,
			// 		'dx_cm' => $dx_cm,
			// 		'newConc' => $newConc,
			// 		'dopant' => $dopantKey,
			// 		'index' => $i,
			// 		'currentTime' => $this->currentTime
			// 	);
				
				
			// 	echo 'woops negative concentration or infinite concentration????  this is a bug.';
			// 	echo ' please email hhellbusch@gmail.com';
			// 	echo ' with detailed steps to reproduce (what the inputs were)';
			// 	echo ' and the data outputted below.';
			// 	var_dump($data);

			// 	echo 'left point:';
			// 	var_dump($leftPoint);
			// 	echo 'current point:';
			// 	var_dump($previousGridPoint);
			// 	echo 'right point:';
			// 	var_dump($rightPoint);

			// 	var_dump($rightChangeCoef);
			// 	var_dump($rightConcDiff);

			// 	var_dump($leftChangeCoef);
			// 	var_dump($leftConcDiff); 
				
			// 	// var_dump($rightConc/$rightSegregation);

			// 	exit;
			// }
		}
		
	}

	public function setDiffusitivyModel($model = 'constant')
	{
		$whiteList = array(
			'constant',
			'fermi',
			'nate'
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
}

?>
