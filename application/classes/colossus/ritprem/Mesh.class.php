<?php

namespace colossus\ritprem;


abstract class Mesh 
{


	/**
	 * Creates a data structure to pass directly into flot
	 * for graphing the dopant concentration.
	 */
	public abstract function getFlotData();


}

?>
