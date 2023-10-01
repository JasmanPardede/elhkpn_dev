<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
* 
*/
class kpk_ldap
{
	function lhkpn_cari_ldap($cari) 
	{
		require("adLDAP.php");
	        //$mycari = str_replace("#520", " ", $cari);
	        $mycari = $cari;
		$search = (($cari=="") ? "*":$mycari."*");
			try {
					$adldap = new adLDAP();
				}catch (adLDAPException $e) 
				{
					echo $e; exit();   
				}
		
		if ($adldap->authenticate('TEST2','1234')){
			$result=$adldap->all_users(true,$search,true);
		}

		$q=1;
		$arr = array();
	    $temp=array();
	   	return $result;
	}

	function cari_ldap($cari) 
	{
		require("adLDAP.php");
	        //$mycari = str_replace("#520", " ", $cari);
	        $mycari = $cari;
		$search = (($cari=="") ? "*":$mycari."*");
			try {
					$adldap = new adLDAP();
				}catch (adLDAPException $e) 
				{
					echo $e; exit();   
				}
		
		if ($adldap->authenticate('TEST2','1234')){
			$result=$adldap->all_users(true,$search,true);
		}

		$q=1;
		$arr = array();
	    $temp=array();
	   
	   
			if(is_array($result)){
				foreach ($result as $k=>$v) { 
				$q++;
					//echo $k . '-';
					//echo $v;
					//echo '<br/>';
					
					$temp = array('id' => $k,
									'value' => $k,
									'info'=> $v
									);
					$arr[] = $temp;
				}
				
				return "{ \"results\": ".json_encode($arr)."}";
			}else{
				return "";
			}
			
	}


	function cari_ldap2($cari) 
	{
		require("adLDAP.php");
	        //$mycari = str_replace("#", " ", $cari);
	        $mycari = $cari;
		$search = (($cari=="") ? "*":$mycari."*");
			try {
					$adldap = new adLDAP();
				}catch (adLDAPException $e) 
				{
					echo $e; exit();   
				}
		
		if ($adldap->authenticate('TEST2','1234')){
			$result=$adldap->all_users(true,$search,true);
		}

		$q=1;
		$arr = array();
	    $temp=array();
	   
	   
			if(is_array($result)){
				foreach ($result as $k=>$v) { 
				$q++;
					//echo $k . '-';
					//echo $v;
					//echo '<br/>';
					
					$temp = array('id' => $v,
									'value' => $v,
									'info'=> $k
									);
					$arr[] = $temp;
				}
				
				return "{ \"results\": ".json_encode($arr)."}";
			}else{
				return "";
			}
			
	}

	function cari_ldap_by_fullname($cari) 
	{
		require("adLDAP.php");
	        //$mycari = str_replace("#", " ", $cari);
	        $mycari = $cari;
		$search = (($cari=="") ? "*":$mycari."*");
			try {
					$adldap = new adLDAP();
				}catch (adLDAPException $e) 
				{
					echo $e; exit();   
				}
		
		if ($adldap->authenticate('TEST2','1234')){
			$result=$adldap->all_users(true,$search,true);
		}

		$q=1;
		$arr = array();
	    $temp=array();
	   
	   
			if(is_array($result)){
				foreach ($result as $k=>$v) { 
				$q++;
					//echo $k . '-';
					//echo $v;
					//echo '<br/>';
					
					$temp = array('id' => $k,
									'value' => $v //,
									//'info'=> $v
									);
					$arr[] = $temp;
				}
				
				return "{ \"results\": ".json_encode($arr)."}";
			}else{
				return "";
			}
			
	}

	function cek_login($username,$password) {

		   require("adLDAP.php");
		//$search = (($cari=="") ? "*":$cari."*");
			try {
					$adldap = new adLDAP();
					
					
				}catch (adLDAPException $e) 
				{
					echo $e; 
					exit();   
				}
		
		if ($adldap->authenticate($username,$password)){
			//$result=$adldap->all_users(true,$search,true);
			
			
			return  TRUE;
		}else {
		
			
			return  FALSE;
		}
	}	
}

$kpk_ldap = new kpk_ldap();

?>