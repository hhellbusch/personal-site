<?php
if (!function_exists("round_sig_figs"))
{
	function round_sig_figs($number, $sigdigs)
	{
		$multiplier = 1; 
		while ($number < 0.1) { 
			$number *= 10; 
			$multiplier /= 10; 
		} 
		while ($number >= 1) { 
			$number /= 10; 
			$multiplier *= 10; 
		} 
		return round($number, $sigdigs) * $multiplier; 
	}
}
