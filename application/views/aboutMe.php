<!-- It should be noted that i used Curt Hostetter's page as inspiration http://curthostetter.com/ -->
<div id="container">
    <div id="top_header"> 
		<div id="header_left" >
		<table>
		<tr>
			<td id="initials"> HH </td>
			<td id="name">
				<div>Henry</div> <div>Hellbusch</div>
			</td>
		</tr>
		</table>
		</div>
		<div id="header_right" >
			<div class='button_row'>
				<div id="contact_button" class="button selected">contact</div>
				<div id="about_button" class="button">about</div>
				<div id="works_button" class="button">works</div>
			</div>
			<div class='text_row'>
				studying <a href="http://www.rit.edu/kgcoe/eme/MicroEoverview">microelectronics</a> 
				@ <a href="http://www.rit.edu">rit</a>
			</div>
			<div class='text_row'>
				software engineer @ <a href="http://www.awstruepower.com">AWS Truepower</a>
			</div>
		</div>
	</div>
	<div id="content_container">
		<div id="content">

			<div id="about">
				<div>
					Hello!  I'm currently a student at Rochester Institute of Technology (rit),
					 mastering in microelectronics engineering
					and minoring in computer science.  
					The microelectronics program focuses on the manufacturing and physics of 
					semiconductor devices.
				</div>
				<div>
					Here is my 
					<a href="docs/HenryHellbusch_resume.pdf">resume</a> 
					if you are interested in such things. Last updated 4/03/2013
				</div>
			</div>

			<div id="contact">
				<div id="contact_title">contact information</div>
				<div id="contact_buttons">
					<?php echo $contactLinks; ?>
				</div>
				<div class='clear-both'></div>
			</div>
			
			
			
			<div id="works">
				<h3>semiconductor related reports written while at rit:</h3>
				<ul>
					<li><a href="/docs/DOE_Resist_Characterization.pdf">Designed Experiment to Characterize Positive Photoresist</a></li>
					<li><a href="/docs/ICTech_PMOS_Process.pdf">PMOS Manufacturing Process - Oxidation, Lithography, Ion Implant and annealing</a></li>
					<li><a href="/docs/ICTech_PMOS_Simulation_VLSI.pdf">PMOS Simulation and VLSI Design</a></li>
				</ul>
				<h3>public websites I've contributed to:</h3>
				<ul>
					<li>
						<a href="http://www.windnavigator.com">
							windNavigator - web based tools for assessing and managing 
							renewable energy resources
						</a>
					</li>
					<li>
						<a href="http://idea3.rit.edu/hgl9008/msse/">
							Science Signs Lexicon - a way for teachers to build and share a library
							 of sign language signs
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
    <div id="bottom_footer">copyright &copy; 1991-<?php echo date('Y', strtotime('now')); ?> me</div>
    <!-- page rendered in {elapsed_time} -->
</div>