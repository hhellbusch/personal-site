<?php if ( ! defined('BASEPATH')) exit('No direct script allowed');
/**
 * Purpose: To create a simple implementation of SURPREM
 * SUPREM stands for Standford University Process Emulation Module.
 * As such, this project has been named RITPREM for Rochester
 * Institute of Technology Process Emulation Module.
 *
 * Assignment Levels:
 * Level 1:
 *  - Model a predep process from a constant-source 
 *  	(fixed surface concentration)
 *  - Model an implant profile with specified energy and dose
 *  	(gaussian profile)
 *  - Redistribute the predep/implant profile - constant dose 
 *  	drive-in (capped surface)
 *  - Assume a constant D diffusion model
 *  - Extracts dose, junction depth, and sheet resistance
 *
 * Level 2:
 *  - Accommodate a constant D or fermi diffusion model
 *  - Accomodate dopant segregation between silicon and oxide
 *  - Model a predep from a doped glass 
 *  	(includes dopant segregation & interface transport)
 *
 * Level 3: 
 *  - Oxide growth and dopant redistribution 
 *  	including oxidation-ehanced diffusion.
 * 
 * Author: Henry Hellbusch
 * Date: 10/5/2013
 *
 * Team Members: Nate Walsh, Will Abisalih, Nicholas Edwards
 *
 * number of beers consumed while programming this:
 * 	Henry : 7
 *
 * number of wines consumed while programming this:
 * 	Nate: 1
 */

use colossus\ritprem\Simulator;
use colossus\ritprem\Concentration;
use colossus\ritprem\ElementFactory;
use colossus\ritprem\Mesh1D;
use colossus\ritprem\Implanter;

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
		$duration = 60;
		
		$sim->setDuration($duration); //seconds
		$surfaceElement = $elemFactory->getElement('As');
		$surface = new Concentration($surfaceElement, 1E20);

		$sim->consantSurfaceSource($surface);

		echo 'duration: ' . $duration / 60 . ' minutes';
		echo ' temperature: ' . ($temp - 273) . ' C';
		return $sim->getMesh()->getFlotData();
	}

	public function implant()
	{
		$flotData = $this->doImplant();
		$this->load->view('microelectronics/ritprem_landing', array('graphData' => $flotData));
	}

	private function doImplant()
	{
		$elemFactory = new ElementFactory;
		$baseConc = new Concentration($elemFactory->getElement('P'), 1E15);
		$mesh = new Mesh1D(1.5, 0.01, $baseConc);
		
		$specie = $elemFactory->getElement('B');
		$implanter = new Implanter();
		$implanter->setMesh($mesh);
		$implanter->setDose(2E15);
		$implanter->setEnergy(50);
		$implanter->setElement($specie);
		$implantedMesh = $implanter->getImplantedMesh();

		
		
		return $implantedMesh->getFlotData();
	}
	

}
