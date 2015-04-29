<?php 
/* This class simply generates FACILITY CONSUMPTION DATA REPORT & REQUEST for each facility, places the content in a pdf and sends
them via mail to HCMP, DHCP */
class fcdrr_auto_email extends MY_Controller{

	function __construct()
	{
		parent::__construct();

		$this->load->config->item('email');
		$this->load->library('email');

		$this->load->model('fcdrr_model');
	}

	function index(){

		$this->load->library('mpdf/mpdf');// Load the mpdf library

		$CHAI_team=array('brianhawi92@gmail.com','tngugi@clintonhealthaccess.org','mwangikevinn@gmail.com');

		//calculate previous dates
		if(date('m')==1)
		{
			$previous_month=12;
			$year=date('Y')-1;
		}
		else
		{
			$previous_month=date('m')-1;
			$year=date('Y');
		}

		$fromdate=$year.'-'.$previous_month.'-01';
		$num_of_days=cal_days_in_month(CAL_GREGORIAN, $previous_month,$year);
		$todate=$year.'-'.$previous_month.'-'.$num_of_days;

		// $result_fcdrr_list="";
		// $mflcode=0;

		//table styling
		$css_styling = '<style>table.data-table {border: 1px solid #DDD;margin: 10px auto;border-spacing: 0px;}
						table.data-table th {border: none;color: #036;text-align: center;background-color: #F5F5F5;border: 1px solid #DDD;border-top: none;max-width: 450px;}
						table.data-table td, table th {padding: 4px;}
						table.data-table td {border: none;border-left: 1px solid #DDD;border-right: 1px solid #DDD;height: 30px;margin: 0px;border-bottom: 1px solid #DDD;}
						.col5{background:#D8D8D8;}</style>';

		$header='<h2>FACILITY CONSUMPTION DATA REPORT & REQUEST(F-CDRR) FOR ART LABORATORY MONITORING REAGENTS</h2>';

		// $fromdate='2015-02-01';
		// $todate='2015-02-28';

		$fcdrr_list="";

		$fcdrr_list=$this->fcdrr_model->get_fcdrr_list($fromdate,$todate);

		foreach($fcdrr_list->result_array() as $fcdrr_result)
		{	
			$final_pdf_data=$this->fcdrr_model->get_fcdrr_content($fcdrr_result);

			$pdf_document=$css_styling.$header.$final_pdf_data;

			$mpdf=new mPDF(); 
			$mpdf->AddPage('', 'A4-L', 0, '', 15, 15, 16, 16, 9, 9, ''); 
			$mpdf->SetDisplayMode('fullpage');
			$mpdf->simpleTables = true;

			$mpdf->SetDisplayMode('fullpage');
			$mpdf->simpleTables = true;

			$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
			$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
			//Generate pdf using mpdf
			               
	        $mpdf ->SetWatermarkText("Nascop",-5);
	        $mpdf ->watermark_font = "sans-serif";
	        $mpdf ->showWatermarkText = true;
			$mpdf ->watermark_size="0.5";

			$filename=str_replace('/','-', $fcdrr_result['name']);

			$mpdf->WriteHTML($pdf_document);
			try
			{
				$filename='c:/xampp/htdocs/web-lims/pdf_documents/'.$filename.'.pdf';

				$mpdf->Output($filename,'F');
			}
			catch(exception $e)
			{
				$e->getMessage();
			}

			/* Get Partner Emails and County Coordinators Emails and have them as recepients */

			$county_receipients=array();
			$partner_receipients=array();
			$email_receipients=array();
			
			$county_coordinator_email=$this->send_mail_model->get_county_email($fcdrr_result['sub_county_id']);

			foreach($county_coordinator_email as $cemail)
			{
				$county_receipients[]=$cemail;
			}
			
			$partner_email=$this->send_mail_model->get_partner_email($fcdrr_result['partner_id']);

			foreach($partner_email as $pemail)
			{
				$partner_receipients[]=$pemail;
			}

			$this->email->from('cd4system@gmail.com', 'CD4 Administrator');

			$email_receipients=array_merge($partner_receipients,$county_receipients);
				
			$this->email->to($email_receipients); //send to specific receiver
			$this->email->bcc($CHAI_team); //CHAI team

			$month_name=GetMonthName($previous_month);
			$this->email->subject('CD4 FCDRR Commodity Reports for '.$month_name.' - '.$year.' '); //subject

			$message="Good Day<br />Find attached the ".$file_counter."  FCDRR Reports For ART Lab Monitoring Reagents for ".$month_name." ".$year.".<br />
					Regards.
					<br /><br />CD4 Support Team";
			
			$this->email->message($message);// the message

			$this->email->attach($filename);//attach the pdf document // add fcdrr facility attachments

			if($this->email->send())//send email and check if the email was sent
			{	
				$this->email->clear(TRUE);//clear any attachments on the email
				echo "Email to ".$fcdrr_result['name']." supporting partners and county Coordinators has been sent <br />";
			}
			else 
			{
				show_error($this->email->print_debugger());//show error message
			}


		} //end foreach loop

		// $file_counter=0;
		// $dir='c:/xampp/htdocs/web-lims/pdf_documents/'; //temporary directory
		// $dh = opendir($dir); //open the directory

		// /* loop and attach files */
	  //       while ($file = readdir($dh) ) 
	  //       {
	  //       	if(!is_dir($file) && strpos($file, '.pdf')>0) { 

	        		
	  //       		$file_counter++;
	  //    		 }
	  //       }
	  //       closedir($dh);

	}

}