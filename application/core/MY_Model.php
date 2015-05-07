<?php
class MY_Model extends CI_Model{

	public function __construct(){
		parent::__construct();
		ini_set('memory_limit', '-1');
	}
	
	public function get_yearmonth_categories($from,$to){

		$datemonth = array();
		
		$from_year        = (int) Date("Y",strtotime($from));
		$from_month       = (int) Date("m",strtotime($from));
		$to_year          = (int) Date("Y",strtotime($to));
		$to_month         = (int) Date("m",strtotime($to));

		for($y=$from_year; $y <= $to_year;$y++){
			for($m=1;($m <= 12);$m++){
				if( $y==$from_year ){
					if($m>=$from_month ){
						$datemonth[] = $y."-".$m;
					}
				}elseif( $y==$to_year ){
					if($m<=$to_month ){
						$datemonth[] = $y."-".$m;
					}
				}else{
					$datemonth[] = $y."-".$m;
				}
			}
		}

            //print_r($datemonth);

		return $datemonth;
	}
	
	
	public function tester(){
		return "Works";
	}
}