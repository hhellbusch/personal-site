<?php

namespace colossus\ritprem;

use colossus\ritprem\Mesh;
use colossus\ritprem\GridPoint;
use colossus\ritprem\Element;
use colossus\ritprem\Concentration;

class Implanter
{
	private $mesh;
	private $energy;
	private $dose;
	private $element;
	private $straggle;
	private $projectedRange;
	private $peakConc;

	public function __construct()
	{

	}

	public function setMesh(Mesh $mesh)
	{
		$this->mesh = clone $mesh;
	}

	/**
	 * [setEnergy description]
	 * @param [type] $energy units of KeV
	 */
	public function setEnergy($energy)
	{
		if ($energy < 10 || $energy > 200)
		{
			trigger_error(
				'Implant energies outside of the range 10-200 KeV are most likely incorrect.', 
				E_WARNING
			);
		}
		$this->energy = $energy;
		$this->calcStraggle();
		$this->calcProjectedRange();
	}

	public function setDose($dose)
	{
		$this->dose = $dose;
	}

	public function setElement(Element $element)
	{
		$this->element = $element;
		$this->calcStraggle();
		$this->calcProjectedRange();
	}

	private function calcStraggle()
	{
		if (!is_null($this->element) && !is_null($this->energy))
		{
			$this->straggle = $this->element->getStraggle($this->energy);
		}
	}

	private function calcProjectedRange()
	{
		if (!is_null($this->element) && !is_null($this->energy))
		{
			$this->projectedRange = $this->element->getProjectedRange($this->energy);
		}
	}




	public function getImplantedMesh()
	{
		$dx = $this->mesh->getDx();
		$gridPoints =& $this->mesh->getGridPoints();
		$this->peakConc = $this->calcPeakConc();
		foreach ($gridPoints as $i => $gridPoint)
		{
			$x = $dx * $i * pow(10, 4); //convert from um to A
			$amount = $this->calculateConcentration($x);
			$conc = new Concentration($this->element, $amount);
			$gridPoints[$i]->addDopant($conc); 
		}
		$this->mesh->addUniqueElement($this->element);
		return $this->mesh;
	}

	private function calculateConcentration($x)
	{
		if (is_null($this->peakConc)) $this->peakConc = $this->calcPeakConc();
		$diff = ($x - $this->projectedRange);
		$conc = $this->peakConc 
			* exp(
				-1 * pow($diff,2)
				/ (2 * pow($this->straggle,2))
			)
		;
		return $conc;
	}

	private function calcPeakConc()
	{
		$peakConc = $this->dose / (sqrt(2 * M_PI) * $this->straggle * pow(10, -8));
		return $peakConc;
	}
}

?>