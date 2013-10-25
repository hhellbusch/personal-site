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
 * Team Members: (supreme ruler) (so he says)Nate Walsh, 
 * 	Will Abisalih, Nicholas Edwards
 *
 * number of beers consumed while programming this:
 * 	Henry : 9
 * 	Nate  : 3
 * 	Nick  : 1
 *
 * number of wines consumed while programming this:
 * 	Nate  : 1
 *
 * number of whiskeys consumed while programming this:
 * 	Henry : 2
 */

use colossus\ritprem\Simulator;
use colossus\ritprem\Concentration;
use colossus\ritprem\ElementFactory;
use colossus\ritprem\Mesh1D;
use colossus\ritprem\Implanter;

class Ritprem extends CI_Controller 
{

	private $elemFactory;

	function __construct ()
	{
		parent::__construct();

		$this->elemFactory = new ElementFactory;

		$this->load->helper('html');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->helper('math');

		ini_set('xdebug.var_display_max_depth', '10');
		//ini_set('xdebug.var_display_max_children', 1E10);
		$this->output->enable_profiler(TRUE);
	}
	
	public function index()
	{
		$dopants = $this->elemFactory->getDopants();
		$this->load->view('microelectronics/ritprem_landing', array('dopants' => $dopants));
	}

	public function constantSource()
	{
		$flotData = $this->doSimulation();
		$this->load->view('microelectronics/ritprem_graph', array('graphData' => $flotData));
	}

	public function simulate()
	{
		$input = $this->input->post();
		if ($input === FALSE) redirect('ritprem');
		//use user input to run a simulation
		
		$backgroundConc = $input['backgroundBase'] * pow(10, $input['backgroundPower']);
		$backgroundDopant = new Concentration($this->elemFactory->getElement($input['backgroundDopant']), $backgroundConc);
		$mesh = new Mesh1D($input['depth'], $input['spacing'], $backgroundDopant);
		$outputMesh = null;
		$elemOfInterest = '';
		if ($input['simulationType'] == 'implant')
		{
			$this->benchmark->mark('implant_start');
			$specie = $this->elemFactory->getElement($input['implantDopant']);
			$elemOfInterest = $specie->getFullName();
			$implanter = new Implanter();
			$implanter->setMesh($mesh);
			$implantDose = $input['implantDose'] * pow(10, $input['implantDosePower']);
			$implanter->setDose($implantDose);
			$implanter->setEnergy($input['implantEnergy']);
			$implanter->setElement($specie);
			$outputMesh = $implanter->getImplantedMesh();
			$this->benchmark->mark('implant_end');
		}
		elseif($input['simulationType'] == 'constantSource')
		{

			$sim = new Simulator();
			$sim->setMesh($mesh);
			$temp = $input['constSourceTemp'] + 273;
			$sim->setTemperature($temp);
			$duration = $input['constSourceTime'];
			$sim->setDuration($duration); //seconds
			$surfaceElement = $this->elemFactory->getElement($input['constSourceDopant']);
			$elemOfInterest = $surfaceElement->getFullName();
			$constConcentration = $input['constSourceConcBase'] * pow(10, $input['constSourceConcPower']);
			$surface = new Concentration($surfaceElement, $constConcentration);
			$sim->setDiffusitivyModel($input['constantSourceModel']);
			$sim->consantSurfaceSourceDiffuse($surface);
			$outputMesh = $sim->getMesh();
		}
		else
		{
			echo 'you broke it!  try again and if it does it again please provide '
				. 'detailed steps to reproduce and send them to hhellbusch@gmail.com';
			return;
		}

		if (isset($input['diffuseTemp']))
		{
			for ($i = 0; $i < count($input['diffuseTemp']); $i++)
			{
				$this->benchmark->mark('diffuse_'.$i.'_start');
				$sim = new Simulator();
				$sim->setDiffusitivyModel($input['diffuseModel'][$i]);
				$temp = $input['diffuseTemp'][$i] + 273;
				$duration = $input['diffuseTime'][$i];
				$sim->setMesh($outputMesh);
				$sim->setTemperature($temp);
				$sim->setDuration($duration); //seconds
				$sim->diffuse();
				$outputMesh = $sim->getMesh();
				$this->benchmark->mark('diffuse_'.$i.'_end');
			}
		}
		

		$flotData = $outputMesh->getFlotData();
		$viewData = array(
			'graphData' => $flotData,
			'xj' => $outputMesh->getJunctionDepth(),
			'dose' => $outputMesh->getDose($elemOfInterest),
			'sheetResistance' => $outputMesh->getSheetResistance()
		);
		$this->load->view('microelectronics/ritprem_graph', $viewData);
	}

}
