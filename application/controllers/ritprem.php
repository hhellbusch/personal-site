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

		 ini_set('xdebug.var_display_max_depth', '10');
		 ini_set('xdebug.var_display_max_children', 1E10);
	}
	
	public function index()
	{
		$flotData = $this->doSimulation();
		$this->load->view('microelectronics/ritprem_landing', array('graphData' => $flotData));
		
	}

	private function doSimulation()
	{
		$elemFactory = new ElementFactory;
		$sim = new Simulator();
		$baseConc = new Concentration($elemFactory->getElement('B'), 1E15);
		$mesh = new Mesh1D(1.5, 0.01, $baseConc);
		$sim->setMesh($mesh);
		$sim->setDiffusitivy('constant');
		$temp = 1000 + 273;
		$sim->setTemperature($temp);
		$duration = 60 * 20;
		
		$sim->setDuration($duration); //seconds
		$surface = new Concentration($elemFactory->getElement('P'), 1E20);

		$sim->consantSurfaceSource($surface);

		echo 'duration: ' . $duration / 60 . ' minutes';
		echo ' temperature: ' . ($temp - 273) . ' C';
		return $sim->getMesh()->getFlotData();
	}
	

}
