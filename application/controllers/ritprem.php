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
 * 	Henry : 11
 * 	Nate  : 5
 * 	Nick  : 2
 * 	Will  : 1
 *
 * number of wines consumed while programming this:
 * 	Nate  : 2
 *
 * number of whiskeys consumed while programming this:
 * 	Henry : 2
 *
 * mkaing bacon pancakessssssssssssssss
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
		//$this->output->enable_profiler(TRUE);
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

		$input = $this->validateFormInput();
		if ($input === FALSE) return;
		//use user input to run a simulation
		ini_set('MAX_EXECUTION_TIME', 60*10); //max execute of 10 minutes

		$backgroundConc = $input['backgroundBase'] * pow(10, $input['backgroundPower']);
		$backgroundDopant = new Concentration($this->elemFactory->getElement($input['backgroundDopant']), $backgroundConc);
		$mesh = new Mesh1D($input['depth'], $input['spacing'], $backgroundDopant);
		$mesh->addSurfaceOxide($input['oxideThicknes']);
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
			$duration = $input['constSourceTime'] * 60;
			$sim->setDuration($duration); //seconds
			$surfaceElement = $this->elemFactory->getElement($input['constSourceDopant']);
			$elemOfInterest = $surfaceElement->getFullName();
			$constConcentration = $input['constSourceConcBase'] * pow(10, $input['constSourceConcPower']);
			$surface = new Concentration($surfaceElement, $constConcentration);
			$sim->setDiffusitivyModel($input['constantSourceModel']);
			$sim->consantSurfaceSourceDiffuse($surface);
			$outputMesh = $sim->getMesh();
		}
		elseif($input['simulationType'] == 'glass')
		{
			$elem = $this->elemFactory->getElement($input['glassDopant']);
			$amount = $input['glassConcBase'] * pow(10, $input['glassConcPower']);
			$mesh->addGlass($input['glassThickness'], new Concentration($elem, $amount));
			$outputMesh = $mesh;
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
				$duration = $input['diffuseTime'][$i] * 60;
				$sim->setMesh($outputMesh);
				$sim->setTemperature($temp);
				$sim->setDuration($duration); //seconds
				$sim->diffuse();
				$outputMesh = $sim->getMesh();
				$this->benchmark->mark('diffuse_'.$i.'_end');
			}
		}
		

		$flotData = $outputMesh->getFlotData();
		$markings = $outputMesh->getFlotMarkings();
		$viewData = array(
			'graphData' => $flotData,
			'graphMarkings' => $markings,
			'xj' => $outputMesh->getJunctionDepthRelativeToSiSurface(),
			'dose' => $outputMesh->getDose($elemOfInterest),
			'sheetResistance' => $outputMesh->getSheetResistance()
		);
		$this->load->view('microelectronics/ritprem_graph', $viewData);
	}

	private function getFormConfig()
	{
		$formConfig = array(
			array(
				'field' => 'backgroundBase',
				'label' => 'Background Concentration Base',
				'rules' => 'required|numeric|less_than[10]|greater_than[0]'
			),
			array(
				'field' => 'backgroundPower',
				'label' => 'Background Concnetration Exponent',
				'rules' => 'required|integer|greater_than[8]|less_than[20]'
			),
			array(
				'field' => 'backgroundDopant',
				'label' => 'Background Doping Element',
				'rules' => 'required|alpha'
			),
			array(
				'field' => 'spacing',
				'label' => 'Grid Size',
				'rules' => 'required|numeric|greater_than[0.001]'
			),
			array(
				'field' => 'depth',
				'label' => 'Grid Depth',
				'rules' => 'required|numeric|less_than[10]'
			),
			array(
				'field' => 'oxideThicknes',
				'label' => 'Surface Oxide Thickness',
				'rules' => 'numeric'
			),
			array(
				'field' => 'diffuseTemp[]',
				'label' => 'Diffusion Step Temperature',
				'rules' => 'numeric|greater_than[700]|less_than[1200]'
			),
			array(
				'field' => 'diffuseTime[]',
				'label' => 'Diffusion Step Duration',
				'rules' => 'numeric|greater_than[0]|less_than[14400]'
			),
			array(
				'field' => 'diffuseModel[]',
				'label' => 'Diffusion Model',
				'rules' => 'alpha'
			)
		);
		return $formConfig;
	}

	private function validateFormInput()
	{
		$input = $this->input->post();
		$formConfig = $this->getFormConfig();
		$this->load->library('form_validation');

		if ($input['simulationType'] == 'implant')
		{
			$formConfig[] = array(
				'field' => 'implantDopant',
				'label' => 'Implant Dopant',
				'rules' => 'required|alpha'
			);
			$formConfig[] = array(
				'field' => 'implantDose',
				'label' => 'Implant Dose Base',
				'rules' => 'required|numeric|greater_than[0]|less_than[10]'
			);
			$formConfig[] = array(
				'field' => 'implantDosePower',
				'label' => 'Implant Dose Exponent',
				'rules' => 'required|numeric|greater_than[8]|less_than[20]'
			);
			$formConfig[] = array(
				'field' => 'implantEnergy',
				'label' => 'Implant Energy',
				'rules' => 'required|numeric|greater_than[9]|less_than[201]'
			);
		}
		elseif($input['simulationType'] == 'constantSource')
		{
			$formConfig[] = array(
				'field' => 'constSourceDopant',
				'label' => "Constant Source Dopant",
				'rules' => 'required|alpha'
			);
			$formConfig[] = array(
				'field' => 'constSourceConcBase',
				'label' => 'Constant Source Concentration Base',
				'rules' => 'required|numeric|less_than[10]|greater_than[0]'
			);
			$formConfig[] = array(
				'field' => 'constSourceConcPower',
				'label' => 'Constant Source Concentration Exponent',
				'rules' => 'required|integer|greater_than[8]|less_than[20]'
			);
			$formConfig[] = array(
				'field' => 'constSourceTemp',
				'label' => 'Constant Source Temperature',
				'rules' => 'required|numeric|greater_than[700]|less_than[1200]'
			);
			$formConfig[] = array(
				'field' => 'constSourceTime',
				'label' => 'Constant Source Duration',
				'rules' => 'required|numeric|greater_than[0]|less_than[14400]'
			);
			$formConfig[] = array(
				'field' => 'constantSourceModel',
				'label' => 'Constant Source Model',
				'rules' => 'required|alpha'
			);
		}
		elseif($input['simulationType'] == 'glass')
		{
			$formConfig[] = array(
				'field' => 'glassThickness',
				'label' => 'Glass Thickness',
				'rules' => 'required|numeric|greater_than[99]'
			);
			$formConfig[] = array(
				'field' => 'glassDopant',
				'label' => "Glass Dopant",
				'rules' => 'required|alpha'
			);
			$formConfig[] = array(
				'field' => 'glassConcBase',
				'label' => 'Glass Concentration Base',
				'rules' => 'required|numeric|less_than[10]|greater_than[0]'
			);
			$formConfig[] = array(
				'field' => 'glassConcPower',
				'label' => 'Glass Concentration Exponent',
				'rules' => 'required|integer|greater_than[8]|less_than[20]'
			);
		}

		$this->form_validation->set_rules($formConfig);

		if ($this->form_validation->run() === FALSE)
		{
			$this->index();
			return false;
		}
		return $input;
	}

}
