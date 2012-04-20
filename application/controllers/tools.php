<?php
class Tools extends CI_Controller {

	
	// get confirmed payments from payflow and inserts to db. 
	public function from_payflow_toDB()
	{
		if($this->input->is_cli_request()){
		
		$this->load->helper ('date');
		$this->load->model ('Guerrero_model');
		$this->load->library ('Payflow');
		$last_counts = $this->Guerrero_model->get_last_seven_money_entries();
		// setup payflow variables 
		$this->payflow->PARTNER  = 'verisign';
		$this->payflow->VENDOR   = 'rmfoundation';
		$this->payflow->USER     = 'guerreros';
		$this->payflow->PWD      = '00guerreroscash00';
		$this->payflow->environment = 'live';
	 	
	 	
	 	//$date = date("Y-m-d", strtotime("-1 day"));
	 	$date =   date('Y-m-d');
	 	$money = array(
	 		 'per_day' => 0, 
	 		 'total_money' => $last_counts[6]->total_money
	 	);
	 	/* this for is for manually update 
for ($i = 0; $date != '2012-04-04'; $i++)
	 	{
*/	
	 	
	 			
		$parameters = array(
	 					'start_date'=> $date.' 00:00:00',
	 					'end_date'	=> $date.' 23:59:59'
	 				);
	 				
		$response = $this->payflow->run_report_request('CustomReport', $parameters);
	 	
	 	$responseObj = $this->payflow->parse_xml_response($response);
		$reportId = $responseObj->runReportResponse->reportId;
		
		$response = $this->payflow->get_result_request($reportId);
		
		$response = $this->payflow->get_metadata_request($reportId);
		
		$responseObj = $this->payflow->parse_xml_response($response);
		
		$fields = $this->payflow->generate_field_array($responseObj);
		
		$response = $this->payflow->get_data_request($reportId);
		
				//print_r($response);		
		$responseObj = $this->payflow->parse_xml_response($response);
			//print_r($responseObj);
			
		if ($responseObj->baseResponse->responseMsg =='Request has completed successfully')
		{
			
			$data = $this->payflow->generate_data_array($responseObj, $fields);
		
	
			 foreach( $data as $t)
			 {
			 	//echo $t['Recurring'];
			 	if($t['Transaction State'] == 9 && (int)$t['Amount']/100 == 2) //usar los tres amounts 
			 	{
			 		//echo ((int)$t['Amount'])/100 . '<br>';
			 		$money['per_day'] += ((int)$t['Amount'])/100;
			 	}
			 	else if(((int)$t['Amount']/100 == 10 || (int)$t['Amount']/100 == 25 || (int)$t['Amount']/100 == 50 ) && $t['Transaction State'] == 8) 
			 	{
			 	$money['per_day'] += ((int)$t['Amount'])/100;
			 	}
			 }//end data foreach
			 
		 }
		 else{
		 	
		 	$money['per_day'] = 0;
		 }
		 $money['total_money'] += $money['per_day'];
		 $money['day_date'] = $date;
		 
		 $result = $this->Guerrero_model->insert_money($money);
		 
		 if($result)
		 {
		 	echo "Good Job, inserted : per_day:".$money['per_day'].", day_date:".$money['day_date'].", total_money:".$money['total_money'].". Inserted date: ".mnow() ;
		 }
		 else{
		 	echo "Something went wrong in the insert_money() function";
		 }
		
		}
		else{
			echo "You can not access this script from web";
		}
		//reset money per day
		//$money['per_day'] = 0;
		
		//increment date 	 	
/*
	 	$date = strtotime($date); //assuming it's not a timestamp\
		$date = $date + (60 * 60 * 24); //increase date by 1 day
		$date =  date('Y-m-d', $date);
*/
		
		
		
		//}//end perday for

	
	
	}

}
?>