<?php
namespace colossus\microelectronics\materials;

abstract class Dielectric
{
	private $thickness;
	private $relativePermitivity;

	public function setThickness($thickness) //angstroms
	{
		$this->thickness = $thickness;
	}

	public function getThickness()
	{
		return $this->thickness;
	}

	public function setRelativePermitivity($relativePermitivity)
	{
		$this->relativePermitivity = $relativePermitivity;
	}
	
	public function getRelativePermitivity()
	{
		return $this->relativePermitivity;
	}

	public function getPermitivity()
	{
		return $this->getRelativePermitivity() * PERMITTIVITY_FREE_SPACE;
	}

	public function getCapacitance()
	{
		//convert thickness from angstrom to cm
		return $this->getPermitivity() / ($this->getThickness() * 1e-8);
	}

}

?>