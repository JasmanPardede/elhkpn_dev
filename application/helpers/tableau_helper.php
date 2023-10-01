<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

	
	function get_trusted_url_tableau($userTableau,$serverTableau,$view_url, $using_https = TRUE, $toolbar = NULL) {
          if ($toolbar){
              $params = ':embed=yes&:toolbar=no&:original_view=y';
          }else{
              $params = ':embed=yes&:toolbar=yes&:original_view=y';
          }
          
	  $ticket = get_trusted_ticket_tableau($serverTableau, $userTableau, $_SERVER['REMOTE_ADDR']);
          
	  if($ticket != '-1') {
              return "https://$serverTableau/trusted/$ticket/$view_url?$params";
	  }
	  else 
		return 0;
	}

	function get_trusted_ticket_tableau($wgserver, $userTableau, $remote_addr) {
	  $params = array(
		'username' => $userTableau,
		'client_ip' => $remote_addr
	  );
          //return $params;
	  return do_post_request_tableau("https://$wgserver/trusted", $params);
	}

	function do_post_request_tableau($url, $data, $optional_headers = null)
	{
            //return $url;
	  $params = array('http' => array(
				  'method' => 'POST',
				  'content' => http_build_query($data)
				));
	  if ($optional_headers !== null) {
		$params['http']['header'] = $optional_headers;
	  }
	  $ctx = stream_context_create($params);
          //return $url;
          //$url = 'http://10.102.2.22/info.php';
	  $fp = @fopen($url, 'rb', false, $ctx);
          //return $ctx;
	  if (!$fp) {
		throw new Exception("Problem with $url");
	  }
	  $response = @stream_get_contents($fp);
	  if ($response === false) {
		throw new Exception("Problem reading data from $url");
	  }
	  return $response;
	}


?>