<?php if ( ! defined('BASEPATH')) exit('No direct script allowed');

use colossus\ritprem\Simulator;
use colossus\ritprem\Concentration;
use colossus\ritprem\ElementFactory;
use colossus\ritprem\Mesh1D;

class Ritprem extends CI_Controller 
{

	function __construct ()
	{
		parent::__construct();
		$this->load->helper('html');
	}
	
	public function index()
	{
		$this->load->view('microelectronics/ritprem_landing');
	}

	private function doSimulation()
	{
		$elemFactory = new ElementFactory;
		$sim = new Simulator();
		$baseConc = new Concentration($elemFactory->getElement('B'), 1E15);
		$mesh = new Mesh1D(5, 0.01, $baseConc);
		$sim->setDiffusitivy('constant');
		
		$sim->simulate();
	}
	

}
