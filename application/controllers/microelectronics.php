<?php if ( ! defined('BASEPATH')) exit('No direct script allowed');

use colossus\microelectronics\devices\Pmosfet;
use colossus\microelectronics\devices\Nmosfet;
use colossus\microelectronics\materials\SiliconDioxide;

class Microelectronics extends CI_Controller 
{
	



	function __construct ()
	{
		parent::__construct();
		$this->load->helper('html');
	}
	
	public function index()
	{
		$this->load->view('microelectronics/landing');
	}

	public function MCEE502HW2()
	{
		$gateThickness = 150; //angstroms
		$fieldThickness = 6500; //angstroms

		//calculates some simple threshold voltages
		$thinOx = new SiliconDioxide();
		$thinOx->setThickness($gateThickness);
		$thickOx = new SiliconDioxide();
		$thickOx->setThickness($fieldThickness);

		$pmos = new Pmosfet();
		$pmos->setDopingConcentration(3E16);
		$pmos->setGateDielectric($thinOx);
		$pmosThinVt = $pmos->calculateThresholdVoltage();
		$idealPmosThinVt = $pmos->calculateIdealThresholdVoltage();
		$pmos->setGateDielectric($thickOx);

		$pmosThickVt = $pmos->calculateThresholdVoltage(); 
		$idealPmosThickVt = $pmos->calculateIdealThresholdVoltage(); 

		$nmos = new Nmosfet();
		$nmos->setDopingConcentration(8e15);

		$nmos->setGateDielectric($thinOx);
		$nmosThinVt = $nmos->calculateThresholdVoltage();
		$idealNmosThinVt = $nmos->calculateIdealThresholdVoltage();

		$nmos->setGateDielectric($thickOx);
		$nmosThickVt = $nmos->calculateThresholdVoltage();
		$idealNmosThickVt = $nmos->calculateIdealThresholdVoltage();

		$nmos->setDopingConcentration(1e17);
		$idealChannelStopVt = $nmos->calculateIdealThresholdVoltage(); 
		$channelStopVt = $nmos->calculateThresholdVoltage(); 

		echo '<h2>Ideal</h2>';
		echo "nwell gate oxide ($gateThickness A): $idealPmosThinVt <br />";
		echo "nwell thick oxide ($fieldThickness A): $idealPmosThickVt <br />";
		echo "pwell gate oxide ($gateThickness A): $idealNmosThinVt <br />";
		echo "pwell thick oxide ($fieldThickness A): $idealNmosThickVt <br />";
		echo "pwell thick oxide with channel stop: $idealChannelStopVt <br />";

		echo '<h2>With Work Function Difference</h2>';
		echo "nwell gate oxide ($gateThickness A): $pmosThinVt <br />";
		echo "nwell thick oxide ($fieldThickness A): $pmosThickVt <br />";
		echo "pwell gate oxide ($gateThickness A): $nmosThinVt <br />";
		echo "pwell thick oxide ($fieldThickness A): $nmosThickVt <br />";
		echo "pwell thick oxide with channel stop: $channelStopVt <br />";
		
	}

	public function thresholdVoltage()
	{
		throw new Exception("Not yet implemented");
		if ($this->input->post())
		{
			$this->handleThresholdVoltagePost();
		}
		else
		{
			$this->displayThresholdVoltageForm();
		}
	}

	private function handleThresholdVoltagePost()
	{

	}

	private function displayThresholdVoltageForm()
	{

	}

	
	

}
