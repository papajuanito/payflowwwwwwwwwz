<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Payflow {

 const HTTP_RESPONSE_OK = 200;
 const KEY_MAP_ARRAY = 'map';
 
 public $data;
 public $headers = array();
 public $gateway_retries = 3;
 public $gateway_retry_wait = 5; //seconds
 public $environment = 'test';
 //public $environment = 'live';
 
 public $vps_timeout = 90;
 public $curl_timeout = 135;
 
 public $gateway_url_live = 'https://payflowpro.paypal.com';
 public $gateway_url_devel = 'https://pilot-payflowpro.paypal.com';
 public $report_url_live = 'https://payments-reports.paypal.com/reportingengine';
 public $report_url_devel = 'https://payments-reports.paypal.com/test-reportingengine';

 public $avs_addr_required	= 1;
 public $avs_zip_required	= 1;
 public $cvv2_required		= 1;
 public $fraud_protection	= TRUE;
 
 public $raw_response;
 //public $response;
 public $response_arr = array();
 
 public $txn_successful = null;
 public $raw_result;
 
 public $debug = FALSE;
 
 public function __construct() {
  
  $this->load_config();
  
  
 }
 
 public function load_config() {
  
  if ( defined('PAYFLOWPRO_USER') ) {
   $this->data['USER'] = constant('PAYFLOWPRO_USER');
  }
  
  if ( defined('PAYFLOWPRO_PWD') ) {
   $this->data['PWD'] = constant('PAYFLOWPRO_PWD');
  }

  if ( defined('PAYFLOWPRO_PARTNER') ) {
   $this->data['PARTNER'] = constant('PAYFLOWPRO_PARTNER');
  }
  
  if ( defined('PAYFLOWPRO_VENDOR') ) { 
   $this->data['VENDOR'] = constant('PAYFLOWPRO_VENDOR');
  }
  else {
   if ( isset($this->data['USER']) ) {
    $this->data['VENDOR'] = $this->data['USER'];
   }
   else {
    $this->data['VENDOR'] = null;
   }
  }
  
 }
 
 public function __set( $key, $val ) {
  
  $this->data[$key] = $val;
  
 }
 
 public function __get( $key ) {
  
  if ( isset($this->data[$key]) ) {
   return $this->data[$key];
  }
  
  return null;
 }
 
 public function get_gateway_url() {
  
  if ( strtolower($this->environment) == 'live' ) {
   return $this->gateway_url_live;
  }
  else {
   return $this->gateway_url_devel;
  }
  
 }
 
  
 public function get_report_url() {

  if ( strtolower($this->environment) == 'live' ) {
   return $this->report_url_live;
  }
  else {
   return $this->report_url_devel;
  }
  
 }
 
 
 public function get_data_string() {
  
  $query = array();

  if ( !isset($this->data['VENDOR']) || !$this->data['VENDOR'] ) {
 $this->data['VENDOR'] = $this->data['USER'];
  }

  
  foreach ( $this->data as $key => $value) {
   
   if ( $this->debug ) {
    echo "{$key} = {$value}
";
   }
   
   $query[] = strtoupper($key) . '[' .strlen($value).']='.$value;
   
  }
  
  return implode('&', $query);
  
 }


//+++++ REPORT Functions 

// Run report request 
//
	public function run_report_request($report_name, $parameters) {
	
	  
		$xml = '<?xml version="1.0"  ?><reportingEngineRequest>
				<authRequest>
					<user>'.$this->data['USER'].'</user> 
					<vendor>'.$this->data['VENDOR'].'</vendor> 
					<partner>'.$this->data['PARTNER'].'</partner> 
					<password>'.$this->data['PWD'].'</password>
				</authRequest>
				<runReportRequest>
				<reportName>'.$report_name.'</reportName>';
		foreach ($parameters as $key=>$val)
		{
			$xml .= '<reportParam>
					<paramName>'.$key.'</paramName>
					<paramValue>'.$val.'</paramValue>
				</reportParam>';
		}	 
		$xml .= '</runReportRequest>
				</reportingEngineRequest>';
		
		//$xml =  simplexml_load_string($xml);
		$response = $this->proccess_xml_curl_request($xml);
		//echo $xml;
		return $response;
	}
	
// Run Search request 
//
	public function run_search_request($report_name, $parameters) {
	
	  
		$xml = '<?xml version="1.0" ?><reportingEngineRequest>
				<authRequest>
					<user>'.$this->data['USER'].'</user> 
					<vendor>'.$this->data['VENDOR'].'</vendor> 
					<partner>'.$this->data['PARTNER'].'</partner> 
					<password>'.$this->data['PWD'].'</password>
				</authRequest>
				<runsearchRequest>
				<searchname>'.$report_name.'</searchname>';
		foreach ($parameters as $key=>$val)
		{
			$xml .= '<reportparam>
					<paramname>'.$key.'</paramname>
					<paramvalue>'.$val.'</paramvalue>
				</reportparam>';
		}	 
		$xml .= '</runsearchRequest>
				</reportingEngineRequest>';
		$response = $this->proccess_xml_curl_request($xml);
		
		return $response;
	}
	
// get result  request 
//
	public function get_result_request($report_id) {
	
	  
		$xml = '<?xml version="1.0" ?><reportingEngineRequest>
				<authRequest>
					<user>'.$this->data['USER'].'</user> 
					<vendor>'.$this->data['VENDOR'].'</vendor> 
					<partner>'.$this->data['PARTNER'].'</partner> 
					<password>'.$this->data['PWD'].'</password>
				</authRequest>
				<getResultsRequest>
					<reportId>'.$report_id.'</reportId>
				</getResultsRequest>
				</reportingEngineRequest>';
		
		//echo $xml;
		$response = $this->proccess_xml_curl_request($xml);
		
		return $response;
	}
	
// get metadata  request 
//
	public function get_metadata_request($report_id) {
	
	  
		$xml = '<?xml version="1.0" ?><reportingEngineRequest>
				<authRequest>
					<user>'.$this->data['USER'].'</user> 
					<vendor>'.$this->data['VENDOR'].'</vendor> 
					<partner>'.$this->data['PARTNER'].'</partner> 
					<password>'.$this->data['PWD'].'</password>
				</authRequest>
				<getMetaDataRequest>
				<reportId>'.$report_id.'</reportId>
				</getMetaDataRequest>
				</reportingEngineRequest>';
		
		$response = $this->proccess_xml_curl_request($xml);
		
		return $response;
	}


// get metadata  request 
//
	public function get_data_request($report_id, $pageNum = '1') {
	
	  
		$xml = '<reportingEngineRequest>
				<authRequest>
					<user>'.$this->data['USER'].'</user> 
					<vendor>'.$this->data['VENDOR'].'</vendor> 
					<partner>'.$this->data['PARTNER'].'</partner> 
					<password>'.$this->data['PWD'].'</password>
				</authRequest>
				<getDataRequest>
				<reportId>'.$report_id.'</reportId>
				<pageNum>'.$pageNum.'</pageNum>
				</getDataRequest>
				</reportingEngineRequest>';
		
		$response = $this->proccess_xml_curl_request($xml);
		
		return $response;
	}


// process xml curl request 
	public function proccess_xml_curl_request($xml){
	  if(empty($_SERVER['HTTP_USER_AGENT']))
	  {
	  $userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';
	  }
	  else{
	  	$userAgent = $_SERVER['HTTP_USER_AGENT'];
	  }
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->get_report_url() );
      //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      //curl_setopt ($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
      curl_setopt($ch, CURLOPT_USERAGENT, $userAgent );
      //curl_setopt($ch, CURLOPT_HEADER, 0);                // tells curl to include headers in response
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        // return into a variable
      curl_setopt($ch, CURLOPT_TIMEOUT, 300);              // times out after 90 secs
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);        // this line makes it work under https
      curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);        //adding POST data
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);       //verifies ssl certificate
      curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);       //forces closure of connection when done
      curl_setopt($ch, CURLOPT_POST, 1);          //data sent as POST
 
 		
      $response = curl_exec($ch);
      //$headers = curl_getinfo($ch);
      //print_r($headers);
      curl_close($ch);
   	  //$obj = $result; 
   	  //$obj = new SimpleXMLElement($result);
   	  //$obj = simplexml_load_string($result);
   	  //print_r($obj);
	  //echo $txml;
	  //echo "OMG";
   	  ///
   	  return $response;
 }
 
 // parse xml response 
	public function parse_xml_response($response)
	{
		$obj = new SimpleXMLElement($response);
		
		return $obj;
	}
 //generate field array 

	public function generate_field_array($responseObj){
	
		$columnMetaData = $responseObj -> getMetaDataResponse->columnMetaData;
		$count = count($columnMetaData);
		
		for($i = 0; $i < $count; $i++)
		{
			$val = $columnMetaData[$i]->dataName;
			$fields[$i] = "".$val."";
		}
		
		return $fields;
	}
//generate data array 
	public function generate_data_array($responseObj, $fields){
	
		$reportDataRow = $responseObj -> getDataResponse->reportDataRow;
		$count = count($reportDataRow);
		
		for($i = 0 ; $i < $count; $i++){
			$val = $reportDataRow[$i]->columnData;
			$count2 = count($fields);
			for($j = 0; $j < $count2 ; $j++){
				$data[$i][$fields[$j]] = "".$val[$j]->data."";
			}
		
		}
		
		return $data;
	
	}
///**** END of Reporting Functions 



 public function before_send_transaction() {
  
  $this->txn_successful = false;
  $this->raw_response = null; //reset raw result
  $this->response_arr = array();
 } 
 
 public function reset() {
  
  $this->txn_successful = null;
  $this->raw_response = null; //reset raw result
  $this->response_arr = array();
  $this->data = array();
  $this->load_config();
 } 
 
 
 public function send_transaction() {
  
  try { 
   
   $this->before_send_transaction();
    
   $data_string = $this->get_data_string();
   
      $headers[] = "Content-Type: text/namevalue"; //or text/xml if using XMLPay.
      $headers[] = "Content-Length: " . strlen ($data_string);  // Length of data to be passed 
      $headers[] = "X-VPS-Timeout: {$this->vps_timeout}";
      $headers[] = "X-VPS-Request-ID:" . uniqid(rand(), true);
   $headers[] = "X-VPS-VIT-Client-Type: PHP/cURL";          // What you are using
   
   $headers = array_merge( $headers, $this->headers );
 
   if ( $this->debug ) {
    echo  __METHOD__ . ' Sending: ' . $data_string . '
';
   }
 
 	  if(empty($_SERVER['HTTP_USER_AGENT']))
	  {
	  $userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';
	  }
	  else{
	  	$userAgent = $_SERVER['HTTP_USER_AGENT'];
	  }
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->get_gateway_url() );
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
      curl_setopt($ch, CURLOPT_HEADER, 0);                // tells curl to include headers in response
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        // return into a variable
      curl_setopt($ch, CURLOPT_TIMEOUT, 90);              // times out after 90 secs
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);        // this line makes it work under https
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);        //adding POST data
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);       //verifies ssl certificate
      curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);       //forces closure of connection when done
      curl_setopt($ch, CURLOPT_POST, 1);          //data sent as POST
 
   $i = 0;
 
      while ($i++ <= $this->gateway_retries) {
          
          $result = curl_exec($ch);
          $headers = curl_getinfo($ch);
 
          if (array_key_exists('http_code', $headers) && $headers['http_code'] != self::HTTP_RESPONSE_OK) {
              sleep($this->gateway_retry_wait);  // Let's wait to see if its a temporary network issue.
          }
          else  {
              // we got a good response, drop out of loop.
              break;
          }
      }  

      if ( !array_key_exists('http_code', $headers) || $headers['http_code'] != self::HTTP_RESPONSE_OK ) {
    throw new InvalidResponseCodeException;
      }

   $this->raw_response = $result;
   
   $result = strstr($result, "RESULT");
   $ret = array();

      while( strlen($result) > 0 ){

          $keypos = strpos($result,'=');
          $keyval = substr($result,0,$keypos);
 
          // value
          $valuepos = strpos($result,'&') ? strpos($result,'&'): strlen($result);
          $valval = substr($result,$keypos+1,$valuepos-$keypos-1);

          // decoding the respose
          $ret[$keyval] = $valval;
        
          $result = substr($result, $valuepos+1, strlen($result) );
	}
	
	$this->response_arr = $ret;
	 
	return $ret;
  }
  catch( Exception $e ) {
   @curl_close($ch);
   throw $e;
  }
 }
 
	public function response_handler( $response_arr ) {
	
		
		if (!defined ('CSC_ERROR_MSG'))
			define ('CSC_ERROR_MSG', 'El código de seguridad no es válido.'); //Your card code is invalid. Please re-enter.
		try {
			$result_code = $response_arr['RESULT']; // get the result code to validate.
			
			if ($this->debug) {
				echo __METHOD__ . ' response=' . print_r( $response_arr, true) . '
				';
				echo __METHOD__ . ' RESULT=' . $result_code . '
				';
			}
   
			if ($result_code == 0) {
			
				//
				// Even on zero, still check AVS
				//
	          
				if ($this->avs_addr_required) {
					$err_msg = 'La dirección indicada no es válida para esta tarjeta.'; //Your billing (street) information does not match.
					
					if (isset ($response_arr['AVSADDR'])) {
						if ($response_arr['AVSADDR'] == 'N') {
							throw new StreetAVSException ($err_msg);
						}
					}
					else {
						if ($this->avs_addr_required == 2) {
							throw new StreetAVSException ($err_msg);
						}
					}
				}
  
				if ($this->avs_zip_required) {
					$err_msg = 'El código postal indicado no es válido para esta tarjeta.'; //Your billing (zip) information does not match. Please re-enter.
					
					if (isset ($response_arr['AVSZIP'])) {
						if ($response_arr['AVSZIP'] == 'N') {
							throw new ZipAVSException ($err_msg);
						}
					}
					else {
						if ($this->avs_zip_required == 2) {
							throw new ZipAVSException ($err_msg);
						}
					}
				}
          
				if ($this->require_cvv2_match) {
					if (array_key_exists('CVV2MATCH', $response_arr)) {
						if ($response_arr['CVV2MATCH'] != "Y") {
							throw new CVV2Exception (CSC_ERROR_MSG);
						}
					}
					else {
						if ($this->require_cvv2_match == 2) {
							throw new CVV2Exception (CSC_ERROR_MSG);
						}
					}
				}
				
				//
				// Return code was 0 and no AVS exceptions raised
				//
				$this->txn_successful = true;
			}
			
			else if ($result_code == 1 || $result_code == 26) {
				throw new InvalidCredentialsException(); //Invalid API Credentials
			}
			else if ($result_code == 12) {
				// Hard decline from bank
				throw new TransactionDataException ('La transacción fue rechazada. Contacta el servicio al cliente de tu tarjeta e intenta nuevamente.'); //Your transaction was declined.
			}
			else if ($result_code == 13) {
				// Voice authorization required
				throw new TransactionDataException ('La transacción no se pudo aprobar electrónicamente. Contacta el servicio al cliente de tu tarjeta e intenta nuevamente.'); // Your Transaction is pending. Contact Customer Service to complete your order.
			}
			else if ($result_code == 23) {
				// Issue with credit card number
				throw new CardNumberException ('El número de tarjeta indicado no es válido.'); //$msg = 'Invalid credit card information: ' . $response_arr['RESPMSG'];
			}
			else if ($result_code == 24) {
				// Issue with expiration date
				throw new ExpDateException ('La fecha de vencimiento indicada no es válida.');
			}
			else if ($result_code == 114) {
				// Issue with CVV2
				throw new CVV2Exception (CSC_ERROR_MSG);
			}
			else {
				// Using the Fraud Protection Service.
				// This portion of code would be is you are using the Fraud Protection Service, this is for US merchants only.
				if ($this->fraud_protection) {
					if (in_array ($result_code, array (125, 126, 127))) {
						// Fraud Filters set to Decline.
						throw new FraudProtectionException ('La transacción fue rechazada. Contacta el servicio al cliente de tu tarjeta antes de intentar el pago.');
						//Your Transaction has been declined. Contact Customer Service to place your order.
						//Your Transaction is Under Review. We will notify you via e-mail if accepted.
					}
				}
			      
				//
				// Throw generic response
				//
				throw new Exception(); //$response_arr['RESPMSG']
			}
		}
		
		catch (Exception $e) {
			throw $e;
		}
	}

 public function process() {
 
  try { 
  
   return $this->response_handler($this->send_transaction());
  }
  catch( Exception $e ) {
   throw $e;
  }
 
 }

 public function apply_associative_array( $arr, $options = array() ) {
  
  try { 
   
   $map_array = array();
     
   if ( isset($options[self::KEY_MAP_ARRAY]) ) {
    $map_array = $options[self::KEY_MAP_ARRAY];
   }
  
   foreach( $arr as $cur_key => $val ) {

    if( isset($map_array[$cur_key]) ) {
     $cur_key = $map_array[$cur_key];
    }
    else {
     if ( isset($options['require_map']) && $options['require_map'] ) {
      continue;
     }
    }
    
    $this->data[strtoupper($cur_key)] = $val;
   
   }
  }
  catch( Exception $e ) {
   throw $e;
  }
  
 }
	
	
	public function credit_card_dropdown()
	{		
		return array (
			0 => 'Visa',
			1 => 'MasterCard',
			2 => 'Discover',
			3 => 'American Express',
			8 => 'Otro'
		);
	}
}


class InvalidCredentialsException extends Exception {
 
}

class GatewayException extends Exception {
 
}

class InvalidResponseCodeException extends GatewayException {
 
}


class TransactionDataException extends Exception {
 
}

class CardNumberException extends TransactionDataException {
 
}

class ExpDateException extends TransactionDataException {
 
}

class AVSException extends TransactionDataException {
 
}

class StreetAVSException extends AVSException {
 
}

class ZipAVSException extends AVSException {
 
}

class CVV2Exception extends TransactionDataException {
 
}

class FraudProtectionException extends Exception {

}


/* End of file payflow.php */
/* Location: ./application/payflow.php */