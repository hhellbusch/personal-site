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
		$boron->setDiffusionCoef(1);
		$boron->setActivationEnergy(3.5);
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
		$boron->setMobilityParams(array(
			'mu_0' => 44.9,
			'mu_max' => 470.5,
			'mu_1' => 29,
			'c_r' => 2.23e17,
			'c_s' => 6.1e20,
			'alpha' => 0.719,
			'beta' => 2,
			'p_c' => 9.23e16
		));
		$boron->setSegregationCoef(1126);
		$boron->setSegregationExponent(0.91);
		$boron->setTransportCoef(1.55e-7);
		$boron->setTransportExponent(0);
		$boron->setFermiDiffusivityParams(array(
			'd0_0' => 0.05,
			'd0_e' => 3.5,
			'dsingle_0' => 0.95,
			'dsingle_e' => 3.5,
			'ddouble_0' => 0,
			'ddouble_e' => 0
		));

		$phosphorus = new Element();
		$phosphorus->setFullName('phosphorus');
		$phosphorus->setSymbol("P");
		$phosphorus->setAtomicWeight(30.973);
		$phosphorus->setAtomicNumber(15);
		$phosphorus->setDiffusionCoef(4.7);
		$phosphorus->setActivationEnergy(3.68);
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
		$phosphorus->setMobilityParams(array(
			'mu_0' => 68.5,
			'mu_max' => 1414,
			'mu_1' => 56.1,
			'c_r' => 9.2e16,
			'c_s' => 3.41e20,
			'alpha' => 0.711,
			'beta' => 1.98
		));
		$phosphorus->setSegregationCoef(30);
		$phosphorus->setSegregationExponent(0);
		$phosphorus->setTransportCoef(1.55e-7);
		$phosphorus->setTransportExponent(0);
		$phosphorus->setFermiDiffusivityParams(array(
			'd0_0' => 3.85,
			'd0_e' => 3.66,
			'dsingle_0' => 4.44,
			'dsingle_e' => 4,
			'ddouble_0' => 44.2,
			'ddouble_e' => 4.37
		));

		$arsenic = new Element();
		$arsenic->setFullName('arsenic');
		$arsenic->setSymbol("As");
		$arsenic->setAtomicWeight(74.922);
		$arsenic->setAtomicNumber(33);
		$arsenic->setDiffusionCoef(9.17);
		$arsenic->setActivationEnergy(3.99);
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
		$arsenic->setMobilityParams(array(
			'mu_0' => 52.2,
			'mu_max' => 1417,
			'mu_1' => 43.4,
			'c_r' => 9.68e16,
			'c_s' => 3.43e20,
			'alpha' => 0.68,
			'beta' => 2
		));
		$arsenic->setSegregationCoef(30);
		$arsenic->setSegregationExponent(0);
		$arsenic->setTransportCoef(1.55e-7);
		$arsenic->setTransportExponent(0);
		$arsenic->setFermiDiffusivityParams(array(
			'd0_0' => 0.011,
			'd0_e' => 3.44,
			'dsingle_0' => 31,
			'dsingle_e' => 4.15,
			'ddouble_0' => 0,
			'ddouble_e' => 0
		));

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
