<?php if ( ! defined('BASEPATH')) exit('No direct script allowed');
class Henry extends CI_Controller {
	
	function __construct ()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->load->helper('html');
		$this->load->helper('url');
		$contactBlocks = $this->getContactBlocks1();
		$contactBlocks = $this->getContactBlocks2();
		
		
		
		
		$array = array(
			'includes' => $this->load->view('aboutMeExternals', null, true),
			'jsBlock' => 'jQuery(document).ready(function(){onLoad();});',
			'contactLinks' => $this->generateLinksHTML($contactBlocks),
			'title' => 'Henry Hellbusch - Student @ RIT'
		);
		$this->load->view('defaultHeader', $array);
		$this->load->view('aboutMe');
		$this->load->view('defaultFooter');

	}

	private function getContactBlocks1()
	{
		return  array(
			'email' => array(
				'picture'=> 'images/email_32.png',
				'display' => "E-mail",
				'username' => 'hhellbusch@gmail.com',
				'link' => 'mailto:hhellbusch@gmail.com'
			),
			'linkedin' => array(
				'picture' => 'images/linkedin_32.png',
				'display' => "LinkedIn",
				'username' => 'hhellbusch',
				'link' => 'http://www.linkedin.com/in/hhellbusch'
			),
			'facebook' => array(
				'picture' => 'images/facebook_32.png',
				'display' => "Facebook",
				'username' => 'hhellbusch',
				'link' => 'http://www.facebook.com/hhellbusch'
			),
			'skype' => array(
				'picture' => 'images/skype_32.png',
				'display' => "Skype",
				'username' => 'henry.hellbusch',
				'link' => 'skype:henry.hellbusch?add&displayname=Henry+Hellbusch'
			),
			'google' => array(
				'picture' => 'images/google_32.png',
				'display'  => "Google",
				'username' => 'hhellbusch',
				'link' => 'https://plus.google.com/108247750832296214285'
			),
			'aim' => array(
				'picture' => 'images/aim_32.png',
				'display' => "AoL.im",
				'username' => 'henry.l.h',
				'link' => 'aim:goim?screenname=henry.l.h'
			),
			'github' => array(
				'picture' => 'images/github_32.png',
				'display' => "GitHub",
				'username' => 'hhellbusch',
				'link' => 'https://github.com/hhellbusch'
			),
		);
	}

	private function getContactBlocks2()
	{
		return  array(
			'email' => array(
				'picture'=> 'images/email.png',
				'display' => "E-mail",
				'username' => 'hhellbusch@gmail.com',
				'link' => 'mailto:hhellbusch@gmail.com'
			),
			'linkedin' => array(
				'picture' => 'images/linkedin.png',
				'display' => "LinkedIn",
				'username' => 'hhellbusch',
				'link' => 'http://www.linkedin.com/in/hhellbusch'
			),
			'facebook' => array(
				'picture' => 'images/facebook.png',
				'display' => "Facebook",
				'username' => 'hhellbusch',
				'link' => 'http://www.facebook.com/hhellbusch'
			),
			'skype' => array(
				'picture' => 'images/skype.png',
				'display' => "Skype",
				'username' => 'henry.hellbusch',
				'link' => 'skype:henry.hellbusch?add&displayname=Henry+Hellbusch'
			),
			'google' => array(
				'picture' => 'images/google.png',
				'display'  => "Google",
				'username' => 'hhellbusch',
				'link' => 'https://plus.google.com/108247750832296214285'
			),
			'aim' => array(
				'picture' => 'images/aim.png',
				'display' => "AoL.im",
				'username' => 'henry.l.h',
				'link' => 'aim:goim?screenname=henry.l.h'
			),
			'github' => array(
				'picture' => 'images/github.png',
				'display' => "GitHub",
				'username' => 'hhellbusch',
				'link' => 'https://github.com/hhellbusch'
			),
		);
	}
	
	/**
	 *	@param contactLinks - array of contact blocks
	 *	contact block is an array that defines 'picture', 
	 *  'display', 'username', 'link'
	 */
	private function generateLinksHTML($contactLinks)
	{
		$this->load->helper('html');
		$html = "<div class='column'>";
		$html .= $this->load->view('contact_link', $contactLinks['email'], true);
		$html .= $this->load->view('contact_link', $contactLinks['linkedin'], true);
		$html .= $this->load->view('contact_link', $contactLinks['google'], true);
		$html .= $this->load->view('contact_link', $contactLinks['github'], true);
		$html .= "</div><div class='column'>";
		$html .= $this->load->view('contact_link', $contactLinks['skype'], true);
		$html .= $this->load->view('contact_link', $contactLinks['facebook'], true);
		$html .= $this->load->view('contact_link', $contactLinks['aim'], true);
		$html .= "</div>";
		
		return $html;
	}
	

}
