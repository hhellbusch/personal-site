<?php
if (!function_exists("round_sig_figs"))
{
	function round_sig_figs($number, $sigdigs)
	{
		log_message('debug', 'round_sig_figs: number->' . $number. ' sigfigs ' . $sigdigs);
		if(!is_numeric($number))  return '';
		if ($number == 0 ) return $number;
		$multiplier = 1; 
		while ($number < 0.1) { 
			$number *= 10; 
			$multiplier /= 10; 
		} 
		while ($number >= 1) { 
			$number /= 10; 
			$multiplier *= 10; 
		}
		log_message('debug', 'exiting round_sig_figs');
		return round($number, $sigdigs) * $multiplier; 
	}
}
