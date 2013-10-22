<?php if ( ! defined('BASEPATH')) exit('No direct script allowed');

class Lithmats extends CI_Controller 
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
		$flotData = array();
		$flotData[] = array(
			'label' => 'a',
			'data' => $this->generateData(5,-2,1000,0.01)
		);
		$flotData[] = array(
			'label' => 'b',
			'data' => $this->generateData(7,-2,1000,0.01)
		);
		$flotData[] = array(
			'label' => 'c',
			'data' => $this->generateData(15,2,1000,0.01)
		);
		$flotData[] = array(
			'label' => 'd',
			'data' => $this->generateData(10,2,1000,0.01)
		);
		$this->load->view(
			'microelectronics/lithmats/drm',
			array('graphData' => $flotData)
		);
		
	}

	public function drm()
	{
		$this->index();
	}

	private function a($n, $mth)
	{
		return ($n+1)/($n-1) * pow((1-$mth),$n);
	}

	private function rate($a, $m, $n, $rmax, $rmin)
	{
		return $rmin + $rmax * 
			( ($a+1) * pow((1-$m), $n) ) /
				( $a + pow((1-$m),$n) ) ;
	}

	private function generateData($n, $mth, $rmax, $rmin)
	{
		$a = $this->a($n, $mth);
		$data = array();
		for ($m = 0; $m < 1; $m += 0.0001)
		{
			$data[] = array($m,$this->rate($a, $m, $n, $rmax, $rmin));
		}
		return $data;
	}



	

}
