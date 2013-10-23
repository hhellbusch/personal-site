<?php

namespace colossus\ritprem;

use colossus\ritprem\Element;

class ElementFactory
{
	private $lookupTable;

	public function __construct()
	{
		//source for element details
		//http://www.webelements.com/
		$boron = new Element();
		$boron->setFullName("boron");
		$boron->setSymbol("B");
		$boron->setAtomicWeight(10.811);
		$boron->setAtomicNumber(5);
		$boron->setDiffusionCoef(10.5);
		$boron->setActivationEnergy(3.69);
		$boron->setDopantType('p');
		$boron->setStraggleFunc(
			function($energy){
				return 315.96 * log($energy, M_E) - 559.69;
			}
		);
		$boron->setProjectedRangeFunc(
			function($energy){
				return 53.789*pow($energy, 0.8862);
			}
		);
		

		$phosphorus = new Element();
		$phosphorus->setFullName('phosphorus');
		$phosphorus->setSymbol("P");
		$phosphorus->setAtomicWeight(30.973);
		$phosphorus->setAtomicNumber(15);
		$phosphorus->setDiffusionCoef(10.5);
		$phosphorus->setActivationEnergy(3.69);
		$phosphorus->setDopantType('n');
		$phosphorus->setStraggleFunc(
			function($energy){
				return -0.0077*pow($energy,2)+5.1189*$energy+36.85;
			}
		);
		$phosphorus->setProjectedRangeFunc(
			function($energy){
				return -0.0016*pow($energy, 2)+13.199*$energy+36.045;
			}
		);

		$arsenic = new Element();
		$arsenic->setFullName('arsenic');
		$arsenic->setSymbol("As");
		$arsenic->setAtomicWeight(74.922);
		$arsenic->setAtomicNumber(33);
		$arsenic->setDiffusionCoef(0.32);
		$arsenic->setActivationEnergy(3.56);
		$arsenic->setDopantType('n');
		$arsenic->setStraggleFunc(
			function($energy){
				return 9.0659*pow($energy, 0.68);
			}
		);
		$arsenic->setProjectedRangeFunc(
			function($energy){
				return 18.693*pow($energy,0.787);
			}
		);

		$this->lookupTable = array(
			'B' => $boron,
			'P' => $phosphorus,
			'As' => $arsenic
		);
	}

	public function getDopants()
	{
		$dopants = array();
		foreach ($this->lookupTable as $elem)
		{
			$dopants[] = $elem;
		}
		return $dopants;
	}

	public function getElement($symbol)
	{
		$element = null;
		if (isset($this->lookupTable[$symbol]))
		{
			$element = $this->lookupTable[$symbol];
		}
		return $element;
	}
}

?>
