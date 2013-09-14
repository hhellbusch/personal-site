<?php

namespace colossus\microelectronics\materials;

use colossus\microelectronics\materials\Dielectric;

class SiliconDioxide extends Dielectric
{
	public function __construct()
	{
		$this->setRelativePermitivity(3.9);
	}


}

?>