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

		$phosphorus = new Element();
		$phosphorus->setFullName('phosphorus');
		$phosphorus->setSymbol("P");
		$phosphorus->setAtomicWeight(30.973);
		$phosphorus->setAtomicNumber(15);

		$arsenic = new Element();
		$arsenic->setFullName('arsenic');
		$arsenic->setSymbol("As");
		$arsenic->setAtomicWeight(74.922);
		$arsenic->setAtomicNumber(33);

		$this->lookupTable = array(
			'B' => $boron,
			'P' => $phosphorus,
			'As' => $arsenic
		);
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