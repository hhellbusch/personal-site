<?php

namespace colossus\microelectronics\devices;

use colossus\microelectronics\devices\Mosfet;

//well is n-type
class Pmosfet extends Mosfet
{
	

	public function calculateMetalWorkFunction()
	{
		//assumes n+ degenerately doped SI for now.
		return -1 * $this->getBandGap() / 2 + $this->calculateFermiEnergy();
	}

	public function calculateIdealThresholdVoltage()
	{
		return round(2 * $this->calculateFermiEnergy() 
			+ abs($this->calculateQsdMax() / $this->getOxideCapicatance()), 3);
	}
}

?>