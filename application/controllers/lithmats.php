<?php if ( ! defined('BASEPATH')) exit('No direct script allowed');

require_once 'Math/Complex.php';
require_once 'Math/ComplexOp.php';

class Lithmats extends CI_Controller 
{

	private $imaginary;

	function __construct ()
	{

		parent::__construct();

		$this->imaginary = new Math_Complex(0, 1);

		$this->load->helper('html');
		$this->load->helper('url');
		$this->load->helper('form');
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


	/**
	 * provides the construct to do all the stuff for the film statck reflectivity assigment
	 * http://people.rit.edu/deeemc/LithM&P/Film%20stack%20simulation_505_605_2131.pdf
	 *
	 * Not the best wrote - could be rewrote to be more dynamic with the number of films.
	 *
	 * Attempts to utilize the vector approach presented in Modern optics by robert d guenther
	 */
	public function filmStackReflectivity()
	{
		$viewData = array();

		$defaultValues = array(
			'substrateIndexReal' => 1.58,
			'substrateIndexImaginary' => "3.60",
			'polyIndexReal' => 1.69,
			'polyIndexImaginary' => 2.76,
			'resistIndexReal' => 1.76,
			'resistIndexImaginary' => 0.007,
			'polyThickness' => 15,
			'resistThickness' => 800,
			'barcIndexStart' => 1,
			'barcIndexEnd' => 2,
			'barcIndexStep' => 0.5,
			'barcExtinctionStart' => 0.1,
			'barcExtinctionEnd' => 0.3,
			'barcExtinctionStep' => 0.1,
			'barcThicknessStart' => 100,
			'barcThicknessEnd' => 300,
			'barcThicknessStep' => 1,
			'wavelength' => 248
		);

		//TODO: add form validations
		if ($this->input->post() !== FALSE)
		{
			//process input
			$subN = $this->input->post('substrateIndexReal');
			$subK = $this->input->post('substrateIndexImaginary');
			$subIndex = new Math_Complex(
				$subN, 
				$subK
			);

			$polyN =$this->input->post('polyIndexReal');
			$polyK = $this->input->post('polyIndexImaginary');
			$polyIndex = new Math_Complex(
				$polyN,
				$polyK
			);

			$resistN = $this->input->post('resistIndexReal');
			$resistK = $this->input->post('resistIndexImaginary');
			$resistIndex = new Math_Complex(
				$resistN,
				$resistK
			);
			$polyThickness = $this->input->post('polyThickness');
			$resistThickness = $this->input->post('resistThickness');

			$barcIndexStart = $this->input->post('barcIndexStart');
			$barcIndexEnd = $this->input->post('barcIndexEnd');
			$barcIndexStep = $this->input->post('barcIndexStep');

			$barcExtinctionStart = $this->input->post('barcExtinctionStart');
			$barcExtinctionEnd = $this->input->post('barcExtinctionEnd');
			$barcExtinctionStep = $this->input->post('barcExtinctionStep');

			$barcThicknessStart = $this->input->post('barcThicknessStart');
			$barcThicknessEnd = $this->input->post('barcThicknessEnd');
			$barcThicknessStep = $this->input->post('barcThicknessStep');

			$wavelength = $this->input->post('wavelength');

			$defaultValues = array(
				'substrateIndexReal' => $subN,
				'substrateIndexImaginary' => $subK,
				'polyIndexReal' => $polyN,
				'polyIndexImaginary' => $polyK,
				'resistIndexReal' => $resistN,
				'resistIndexImaginary' => $resistK,
				'polyThickness' => $polyThickness,
				'resistThickness' => $resistThickness,
				'barcIndexStart' => $barcIndexStart,
				'barcIndexEnd' => $barcIndexEnd,
				'barcIndexStep' => $barcIndexStep,
				'barcExtinctionStart' => $barcExtinctionStart,
				'barcExtinctionEnd' => $barcExtinctionEnd,
				'barcExtinctionStep' => $barcIndexStep,
				'barcThicknessStart' => $barcThicknessStart,
				'barcThicknessEnd' => $barcThicknessEnd,
				'barcThicknessStep' => $barcThicknessStep,
				'wavelength' => $wavelength
			);

			$reflectivities = array();

			//calc thing that dont change
			$indexAir = new Math_Complex(1, 0);

			$stack = array(
				0 => array(
					'material' => 'air',
					'index' => new Math_Complex(1,0)
				),
				1 => array(
					'material' => 'resist',
					'index' => $resistIndex,
					'thickness' => $resistThickness,
				),
				2 => array(
					'material' => 'barc',
					'index' => "variable",
					'thickness' => "variable",
					'nStart' => $barcIndexStart,
					'nEnd' => $barcIndexEnd,
					'nStep' => $barcIndexStep,

					'kStart' => $barcExtinctionStart,
					'kEnd' => $barcExtinctionEnd,
					'kStep' => $barcExtinctionStep,

					'thicknessStart' => $barcThicknessStart,
					'thicknessEnd' => $barcThicknessEnd,
					'thicknessStep' => $barcThicknessStep
				),
				3 => array(
					'material' => 'poly',
					'index' => $polyIndex,
					'thickness' => $polyThickness
				),
				4 => array(
					'material' => 'silicon',
					'index' => $subIndex,
					'thickness' => 'substrate'
				)
			);

			$data = $this->calcCurveData($stack, 2, 2, $wavelength);

			$viewData['graphData'] = $data;
		}
		
		foreach ($defaultValues as $param => $value)
		{
			$viewData[$param] = $value;
		}

		$this->load->view('microelectronics/lithmats/filmStack', $viewData);
	}


	private function calcTransmissionCoef(Math_Complex $fromIndex, Math_Complex $toIndex)
	{
		$top = Math_ComplexOp::mult(new Math_Complex(2,0), $fromIndex);
		$bot = Math_ComplexOp::add($fromIndex, $toIndex);
		return  Math_ComplexOp::div($top, $bot) ;
	}

	private function calcReflectanceCoef(Math_Complex $fromIndex, Math_Complex $toIndex)
	{
		$top = Math_ComplexOp::sub($fromIndex, $toIndex);
		$bot = Math_ComplexOp::add($fromIndex, $toIndex);
		return Math_ComplexOp::div($top, $bot);
	}

	private function calcDelta($thickness, Math_Complex $index, $wavelength)
	{
		$delta = Math_ComplexOp::mult(new Math_Complex(2, 0), new Math_Complex(M_PI, 0));
		$delta = Math_ComplexOp::mult($delta, $index);
		$delta = Math_ComplexOp::mult($delta, new Math_Complex($thickness, 0));
		$delta = Math_ComplexOp::div($delta, new Math_Complex($wavelength, 0));
		return   $delta;
	}
	
	private function calcCurveData($stack, $variedStackElementIndex, $reflectanceOffOfIndex, $wavelength)
	{
		$variedElement = $stack[$variedStackElementIndex];
		$data = array();

		//NEST ALL THE LOOPS
		for (
			$n = $variedElement['nStart'];
			$n < $variedElement['nEnd'];
			$n = $n + $variedElement['nStep']
		) {
			for (
				$k = $variedElement['kStart']; 
				$k < $variedElement['kEnd']; 
				$k = $k + $variedElement['kStep']
			) {
				$reflectivityData = array(
					'label' => 'n: ' . $n . ', k: ' . $k,
					'data' => array()
				);
				$index = new Math_Complex($n, $k);
				$stack[$variedStackElementIndex]['index'] = $index;

				for (
					$thickness = $variedElement['thicknessStart']; 
					$thickness < $variedElement['thicknessEnd']; 
					$thickness = $thickness + $variedElement['thicknessStep']
				) {
					$stack[$variedStackElementIndex]['thickness'] = $thickness;
					$stack = $this->calcStackData($stack, $wavelength);

					$refCoef = $stack[$variedStackElementIndex - 1]['refCoefDown'];
					
					$extinction = new Math_Complex(1,0);
					for ($i = $variedStackElementIndex ; $i < count($stack) - 1; $i++)
					{
						$extinction = Math_ComplexOp::mult($extinction, Math_ComplexOp::exp($stack[$i]['imaginary_delta']));
						$rCoefFilm = $stack[$i]['refCoefDown'];
						$refCoef = Math_ComplexOp::add($refCoef, Math_ComplexOp::mult($rCoefFilm, $extinction));
					}
					$reflectance = $refCoef->abs2();
					$reflectivityData['data'][] = array($thickness, $reflectance);

				}
				$data[] = $reflectivityData;
			}
		}
		
		
		//flotify the data as needed...


		return $data;
	}



	/**
	 * calculates transmissions up and down
	 * and reflectivity up and down
	 * @param  [type] $stack [description]
	 * @return [type]        [description]
	 */
	private function calcStackData($stack, $wavelength)
	{
		$stackSize = count ($stack);
		foreach ($stack as $key => $member)
		{
			if ($key != 0)
			{
				//find stuff going up
				$transCoefUp = $this->calcTransmissionCoef($member['index'], $stack[$key-1]['index']);
				$refCoefUp = $this->calcReflectanceCoef($member['index'], $stack[$key-1]['index']);
				$stack[$key]['transCoefUp'] = $transCoefUp;
				$stack[$key]['refCoefUp'] = $refCoefUp;
			}

			if ($key != $stackSize - 1)
			{
				$transCoefDown = $this->calcTransmissionCoef($stack[$key+1]['index'], $member['index']);
				$refCoefDown = $this->calcReflectanceCoef($stack[$key+1]['index'], $member['index']);
				$stack[$key]['transCoefDown'] = $transCoefDown;
				$stack[$key]['refCoefDown'] = $refCoefDown;
			}

			if (
				isset($member['thickness']) 
				&& $member['thickness'] != 'substrate' 
				&& $member['thickness'] != 'variable'
			) {
				$delta = $this->calcDelta($member['thickness'], $member['index'], $wavelength);
				$stack[$key]['imaginary_delta'] = Math_ComplexOp::mult($this->imaginary, $delta);
			}
		}

		return $stack;
	}

}
