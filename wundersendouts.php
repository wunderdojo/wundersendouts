<?php
/**
Plugin Name: wundersendouts
Version: 1.0
Plugin URI: http://www.wunderdojo.com
Description: Custom plugin to integrate SendOuts API feeds with WordPress
Author: James Currie
Author URI: http://www.wunderdojo.com

------------------------------------------------------------------------
Copyright 2012 wunderdojo LLC

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*/

/**
 * @author James Currie
 * @package wundersendouts
 * @version 1.0

 */
 include('wundersendoutswidget.php');
 add_action('widgets_init', array('wundersendoutsWidget','register_widget'));

 class wunderSendOuts {
	/** class properties */
		static public $clientLogin="firststring";
		static public $UserName= "WSAccess";
		static public $Password= "74RQgXk3Jp";
		static public $sendouts_url= "https://server.sendouts.com/clientutils.asmx?wsdl";
		static public $soap_version= 'SOAP_1_2';
		
	function startSession(){
		$client = new SoapClient(self::$sendouts_url,array("trace" => 1));
		return $client;
	}
	
	static function openSession(){
		$client = self::startSession();
		$params = array(
			'ClientLogin'=> self::$clientLogin,
			'UserName' => self::$UserName,
			'Password' => self::$Password,
			'soap_version' => self::$soap_version
			);
		$result = $client->OpenSession($params);
		$params['SecId'] = $result->SecId;
		$params['JobCat'] = '39';
		return array('client'=>$client, 'params'=>$params);
	}
	
	function getListings(){
		$returnarray = wunderSendOuts::openSession();
		/** GetLiveJoPostsWithExtensions is a currently undoc'd SendOuts API call. 
		/* It doesn't accept any parameters 
		*/
		$jobs = $returnarray['client']->GetLiveJoPostsWithExtensions($returnarray['params']);
		$jobs_xml = new SimpleXMLElement($jobs->GetLiveJoPostsWithExtensionsResult);
		$jobs = $jobs_xml->data;
		return $jobs;
	}
 
 }//end of class
 ?>