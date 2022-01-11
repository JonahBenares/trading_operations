<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Masterfile extends CI_Controller {

	function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        date_default_timezone_set("Asia/Manila");
        $this->load->model('super_model');
        $this->load->database();
 
       
        function arrayToObject($array){
            if(!is_array($array)) { return $array; }
            $object = new stdClass();
            if (is_array($array) && count($array) > 0) {
                foreach ($array as $name=>$value) {
                    $name = strtolower(trim($name));
                    if (!empty($name)) { $object->$name = arrayToObject($value); }
                }
                return $object;
            } 
            else {
                return false;
            }
        }
    } 

	public function index()
	{
        

        $this->load->view('template/header');
        $this->load->view('masterfile/form');
		$this->load->view('template/footer');
	}

     public function generate_xml_01(){

        
        $submission_date = date("Y-m-d",strtotime($this->input->post('submission_date')));
        $generator= $this->input->post('generator');
        $standing_bid= $this->input->post('standing_bid');
        $start_date =  date("Y-m-d",strtotime($this->input->post('start_date')));
        $end_date =  date("Y-m-d",strtotime($this->input->post('end_date')));
        $default_price = $this->input->post('en_default_price');
      

        $count = $this->input->post('count');

        $count2=$this->input->post('count2');

        if(empty($count)){
            if(empty($this->input->post('start1'))){
                $count=0;
            } else {
                $count=1;
            }
        }else {
            $count=$count;
        }

          if(empty($count2)){
            if(empty($this->input->post('start1'))){
                $count2=0;
            } else {
                $count2=1;
            }
        }else {
            $count2=$count2;
        }


        $xmlString = '<?xml version="1.0" encoding="UTF-8"?>
            <m:RawBidSet xmlns:m="http://pemc/soa/RawBidSet.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pemc/soa/RawBidSet.xsd RawBidSet.xsd">
             <m:MessageHeader>
              <m:TimeDate>'.$submission_date.'T00:00:00Z</m:TimeDate>
              <m:Source>Default</m:Source>
             </m:MessageHeader>
            <m:MessagePayload>
             <m:GeneratingBid>
              <m:startTime>'.$start_date.'T00:00:00+08:00</m:startTime>
              <m:stopTime>'.$end_date.'T00:00:00+08:00</m:stopTime>';
              if($standing_bid==1){
                 $xmlString.='<m:dayType>ALL</m:dayType>';
              }

              $xmlString.='<m:RegisteredGenerator>
              <m:mrid>'.$generator.'</m:mrid>
              </m:RegisteredGenerator>
              <m:MarketParticipant>
               <m:mrid>CENPRI_03</m:mrid>
              </m:MarketParticipant>
              <m:ProductBid>
               <m:MarketProduct>
                <m:marketProductType>EN</m:marketProductType>
               </m:MarketProduct>';

               if($count==0){ 

                $xmlString .= '<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>'.$default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>4.5</m:xAxisData>
                      <m:y1AxisData>'.$default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';

                } else {

                    $end1 = $this->input->post('start1');

                    if($end1!=0){
                      $xmlString .= '<m:BidSchedule>
                        <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                        <m:timeIntervalEnd>'.$start_date.'T'.$end1.':00:00+08:00</m:timeIntervalEnd>
                        <m:BidPriceCurve>
                         <m:CurveSchedData>
                          <m:xAxisData>0</m:xAxisData>
                          <m:y1AxisData>'.$default_price.'</m:y1AxisData>
                         </m:CurveSchedData>
                         <m:CurveSchedData>
                          <m:xAxisData>4.5</m:xAxisData>
                          <m:y1AxisData>'.$default_price.'</m:y1AxisData>
                         </m:CurveSchedData>
                        </m:BidPriceCurve>
                       </m:BidSchedule>';
                   }



                    $end1 = $this->input->post('start1');
                /*    echo "00 - " . $end1 ."<br>";*/
                    $index=0;
                    $oldv=0;
                    for($x=1;$x<=$count;$x++){

                        $start_hour = $this->input->post('start'.$x);
                        $end_hour = $this->input->post('end'.$x);
                        $price_change = $this->input->post('price'.$x);
                        $capacity = $this->input->post('capacity'.$x);

                          if($index==0) {
                            $oldv = $start_hour;
                            $index = 1;
                           
                         }
                            $y=$x-1;
                            if($y>=1){
                            $previous_end_hour = $this->input->post('end'.$y);
                           
                            $new_start =  $previous_end_hour;
                            $new_end =  $end_hour-1;
                            } else {
                                $new_start='';
                                $new_end='';
                            }
                        
                        /*     echo $new_start . " - " .$new_end."<br>";
                            echo $start_hour . " - " .  $end_hour."<br>";*/
                           
                            $oldv = $start_hour;
                            if(!empty($new_start)){

                                if($new_start!= $oldv){
                                   $xmlString .= '<m:BidSchedule>
                                        <m:timeIntervalStart>'.$start_date.'T'. $new_start.':00:00+08:00</m:timeIntervalStart>
                                        <m:timeIntervalEnd>'.$start_date.'T'.$oldv.':00:00+08:00</m:timeIntervalEnd>
                                        <m:BidPriceCurve>
                                         <m:CurveSchedData>
                                          <m:xAxisData>0</m:xAxisData>
                                          <m:y1AxisData>'.$default_price.'</m:y1AxisData>
                                         </m:CurveSchedData>
                                         <m:CurveSchedData>
                                          <m:xAxisData>4.5</m:xAxisData>
                                          <m:y1AxisData>'.$default_price.'</m:y1AxisData>
                                         </m:CurveSchedData>
                                        </m:BidPriceCurve>
                                       </m:BidSchedule>';
                                }

                            }


                            if($end_hour != "00"){
                               $xmlString .= '<m:BidSchedule>
                                    <m:timeIntervalStart>'.$start_date.'T'. $start_hour.':00:00+08:00</m:timeIntervalStart>
                                    <m:timeIntervalEnd>'.$start_date.'T'.$end_hour.':00:00+08:00</m:timeIntervalEnd>
                                    <m:BidPriceCurve>
                                     <m:CurveSchedData>
                                      <m:xAxisData>0</m:xAxisData>
                                      <m:y1AxisData>'.$price_change.'</m:y1AxisData>
                                     </m:CurveSchedData>
                                     <m:CurveSchedData>
                                      <m:xAxisData>'.$capacity.'</m:xAxisData>
                                      <m:y1AxisData>'.$price_change.'</m:y1AxisData>
                                     </m:CurveSchedData>
                                    </m:BidPriceCurve>
                                   </m:BidSchedule>';
                            } else {
                                 $xmlString .= '<m:BidSchedule>
                                    <m:timeIntervalStart>'.$start_date.'T'. $start_hour.':00:00+08:00</m:timeIntervalStart>
                                    <m:timeIntervalEnd>'.$end_date.'T'.$end_hour.':00:00+08:00</m:timeIntervalEnd>
                                    <m:BidPriceCurve>
                                     <m:CurveSchedData>
                                      <m:xAxisData>0</m:xAxisData>
                                      <m:y1AxisData>'.$price_change.'</m:y1AxisData>
                                     </m:CurveSchedData>
                                     <m:CurveSchedData>
                                      <m:xAxisData>'.$capacity.'</m:xAxisData>
                                      <m:y1AxisData>'.$price_change.'</m:y1AxisData>
                                     </m:CurveSchedData>
                                    </m:BidPriceCurve>
                                   </m:BidSchedule>';
                            }
                            

                    }

                     $last = $this->input->post('end'.$count);

                     if($last!="00"){
                         $xmlString .= '<m:BidSchedule>
                                    <m:timeIntervalStart>'.$start_date.'T'. $last.':00:00+08:00</m:timeIntervalStart>
                                    <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                                    <m:BidPriceCurve>
                                     <m:CurveSchedData>
                                      <m:xAxisData>0</m:xAxisData>
                                      <m:y1AxisData>'.$default_price.'</m:y1AxisData>
                                     </m:CurveSchedData>
                                     <m:CurveSchedData>
                                      <m:xAxisData>4.5</m:xAxisData>
                                      <m:y1AxisData>'.$default_price.'</m:y1AxisData>
                                     </m:CurveSchedData>
                                    </m:BidPriceCurve>
                                   </m:BidSchedule>';
                     }
        
               }

              $xmlString .= '  </m:ProductBid>
                   <m:RampRateCurve>
                   <m:description>RAMP_RATE</m:description>
                   <m:CurveData>
                    <m:xAxisData>4.5</m:xAxisData>
                    <m:y1AxisData>0.2</m:y1AxisData>
                    <m:y2AxisData>0.2</m:y2AxisData>
                   </m:CurveData>
                  </m:RampRateCurve>
                 </m:GeneratingBid>
                </m:MessagePayload>
                </m:RawBidSet>';

$dom = new DOMDocument;
$dom->preserveWhiteSpace = FALSE;
$dom->loadXML($xmlString);

//Save XML as a file
$now = date('Ymd');
$dom->save('../../../XMLExport/'.$now.'_06CENPRI_U01.xml');     
    /*    $this->output->set_content_type('text/xml');
        $this->output->set_output($xmlString);*/
    }


    public function generate_xml_02(){

        $submission_date = date("Y-m-d",strtotime($this->input->post('submission_date')));
        $generator= $this->input->post('generator');
         $standing_bid= $this->input->post('standing_bid');
        $start_date =  date("Y-m-d",strtotime($this->input->post('start_date')));
        $end_date =  date("Y-m-d",strtotime($this->input->post('end_date')));
        $en_default_price = $this->input->post('en_default_price');



        $count = $this->input->post('count');
        
        if(empty($count)){
            if(empty($this->input->post('start1'))){
                $count=0;
            } else {
                $count=1;
            }
        }else {
            $count=$count;
        }
        $xmlString = '<?xml version="1.0" encoding="UTF-8"?>
        <m:RawBidSet xmlns:m="http://pemc/soa/RawBidSet.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pemc/soa/RawBidSet.xsd RawBidSet.xsd">
             <m:MessageHeader>
              <m:TimeDate>'. $submission_date .'T00:00:00Z</m:TimeDate>
              <m:Source>Default</m:Source>
             </m:MessageHeader>
            <m:MessagePayload>
             <m:GeneratingBid>
              <m:startTime>'.$start_date.'T00:00:00+08:00</m:startTime>
              <m:stopTime>'.$end_date.'T00:00:00+08:00</m:stopTime>';
                if($standing_bid==1){
                 $xmlString.='<m:dayType>ALL</m:dayType>';
              }
               $xmlString.='<m:RegisteredGenerator>
              <m:mrid>'.$generator.'</m:mrid>
              </m:RegisteredGenerator>
              <m:MarketParticipant>
               <m:mrid>CENPRI_03</m:mrid>
              </m:MarketParticipant>
              <m:ProductBid>
               <m:MarketProduct>
                <m:marketProductType>EN</m:marketProductType>
               </m:MarketProduct>';

               if($count==0){ 

                $xmlString .= '<m:BidSchedule>
                <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                <m:BidPriceCurve>
                 <m:CurveSchedData>
                  <m:xAxisData>0</m:xAxisData>
                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                 </m:CurveSchedData>
                 <m:CurveSchedData>
                  <m:xAxisData>4.5</m:xAxisData>
                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                 </m:CurveSchedData>
                </m:BidPriceCurve>
               </m:BidSchedule>';

                } else {

                    $en_end1 = $this->input->post('start1');
                 if($en_end1!=0){
                    $xmlString .= '<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$start_date.'T'.$en_end1.':00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>4.5</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';
               }



                    $en_end1 = $this->input->post('start1');
                /*    echo "00 - " . $end1 ."<br>";*/
                    $index=0;
                    $oldv=0;
                    for($x=1;$x<=$count;$x++){

                        $start_hour = $this->input->post('start'.$x);
                        $end_hour = $this->input->post('end'.$x);
                        $price_change = $this->input->post('price'.$x);
                        $capacity = $this->input->post('capacity'.$x);

                          if($index==0) {
                            $oldv = $start_hour;
                            $index = 1;
                           
                         }
                            $y=$x-1;
                            if($y>=1){
                            $previous_end_hour = $this->input->post('end'.$y);
                           
                            $new_start =  $previous_end_hour;
                            $new_end =  $end_hour-1;
                            } else {
                                $new_start='';
                                $new_end='';
                            }
                        
                                    
                          /*  if(($new_start<=10 && $start_hour<=10) || ($new_start>=20 && $start_hour>=20)){
                                     echo $new_start . " - " .$new_end."<br>";
                           
                                    echo $start_hour . " - " .  $end_hour."<br>";
                                }*/
                            $oldv = $start_hour;
                            if(!empty($new_start)){
                                 if($new_start!= $oldv){
                                    $xmlString .= '<m:BidSchedule>
                                    <m:timeIntervalStart>'.$start_date.'T'. $new_start.':00:00+08:00</m:timeIntervalStart>
                                    <m:timeIntervalEnd>'.$start_date.'T'.$oldv.':00:00+08:00</m:timeIntervalEnd>
                                    <m:BidPriceCurve>
                                     <m:CurveSchedData>
                                      <m:xAxisData>0</m:xAxisData>
                                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                     </m:CurveSchedData>
                                     <m:CurveSchedData>
                                      <m:xAxisData>4.5</m:xAxisData>
                                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                     </m:CurveSchedData>
                                    </m:BidPriceCurve>
                                   </m:BidSchedule>';
                               }
                            }

                        if($end_hour != "00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$capacity.'</m:xAxisData>
                                  <m:y1AxisData>'.$price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                        } else {
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T'.$end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$capacity.'</m:xAxisData>
                                  <m:y1AxisData>'.$price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                        }

                    }

                     $last = $this->input->post('end'.$count);

                     if($last!="00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $last.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>4.5</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                    }
        
               }


              $xmlString .= '</m:ProductBid>
               <m:RampRateCurve>
               <m:description>RAMP_RATE</m:description>
               <m:CurveData>
                <m:xAxisData>4.5</m:xAxisData>
                <m:y1AxisData>0.2</m:y1AxisData>
                <m:y2AxisData>0.2</m:y2AxisData>
               </m:CurveData>
              </m:RampRateCurve>
             </m:GeneratingBid>
            </m:MessagePayload>
            </m:RawBidSet>';
        /*$this->output->set_content_type('text/xml');
        $this->output->set_output($xmlString);*/

        $dom = new DOMDocument;
$dom->preserveWhiteSpace = FALSE;
$dom->loadXML($xmlString);

//Save XML as a file
$now = date('Ymd');
$dom->save('../../../XMLExport/'.$now.'_06CENPRI_U02.xml');     

    }

    public function generate_xml_03(){

        $submission_date = date("Y-m-d",strtotime($this->input->post('submission_date')));
        $generator= $this->input->post('generator');
        $standing_bid= $this->input->post('standing_bid');
        $start_date =  date("Y-m-d",strtotime($this->input->post('start_date')));
        $end_date =  date("Y-m-d",strtotime($this->input->post('end_date')));
        $en_default_price = $this->input->post('en_default_price');
       

        $count = $this->input->post('count');
        $count_dr = $this->input->post('count2');
        $count_set1 = $this->input->post('count_set1');
        $count_set2 = $this->input->post('count_set2');

        if(empty($count)){
            if(empty($this->input->post('en_start_en03_1'))){
                $count=0;
            } else {
                $count=1;
            }
        }else {
            $count=$count;
        }

        if(empty($count_dr)){
            if(empty($this->input->post('dr_start_int2_1'))){
                $count_dr=0;
            } else {
                $count_dr=1;
            }
        }else {
            $count_dr=$count_dr;
        }

         if(empty($count_set1)){
            if(empty($this->input->post('dr_start_set1_1'))){
                $count_set1=0;
            } else {
                $count_set1=1;
            }
        }else {
            $count_set1=$count_set1;
        }

         if(empty($count_set2)){
            if(empty($this->input->post('dr_start_set2_1'))){
                $count_set2=0;
            } else {
                $count_set2=1;
            }
        }else {
            $count_set2=$count_set2;
        }
        $xmlString ='<?xml version="1.0" encoding="UTF-8"?>
                <m:RawBidSet xmlns:m="http://pemc/soa/RawBidSet.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pemc/soa/RawBidSet.xsd RawBidSet.xsd">
                 <m:MessageHeader>
                  <m:TimeDate>'. $submission_date .'T00:00:00Z</m:TimeDate>
                  <m:Source>Default</m:Source>
                 </m:MessageHeader>
                <m:MessagePayload>
                 <m:GeneratingBid>
                  <m:startTime>'.$start_date.'T00:00:00+08:00</m:startTime>
                  <m:stopTime>'.$end_date.'T00:00:00+08:00</m:stopTime>';
                 if($standing_bid==1){
                     $xmlString.='<m:dayType>ALL</m:dayType>';
                  }
                   $xmlString.='<m:RegisteredGenerator>
                  <m:mrid>'.$generator.'</m:mrid>
                  </m:RegisteredGenerator>
                  <m:MarketParticipant>
                   <m:mrid>CENPRI_03</m:mrid>
                  </m:MarketParticipant>
                  <m:ProductBid>
                   <m:MarketProduct>
                    <m:marketProductType>EN</m:marketProductType>
                   </m:MarketProduct>';

               if($count==0){ 

                $xmlString .='<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>4.5</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';

                } else {

                    $en_end1 = $this->input->post('en_start_en03_1');
                 if($en_end1!=0){
                    $xmlString .= '<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$start_date.'T'.$en_end1.':00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>4.5</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';
               }


                     $en_end1 = $this->input->post('en_start_en03_1');
                    $index=0;
                    $oldv_en=0;
                    for($x=1;$x<=$count;$x++){

                        $en_start_hour = $this->input->post('en_start_en03_'.$x);
                        $en_end_hour = $this->input->post('en_end_en03_'.$x);
                        $en_price_change = $this->input->post('en_price_en03_'.$x);
                         $capacity = $this->input->post('en_cap_en03_'.$x);

                          if($index==0) {
                            $oldv_en = $en_start_hour;
                            $index = 1;
                           
                         }
                            $y=$x-1;
                            if($y>=1){
                            $previous_end_hour_en = $this->input->post('en_end_en03_'.$y);
                           
                            $new_start_en =  $previous_end_hour_en;
                            $new_end_en =  $en_end_hour-1;
                            } else {
                                $new_start_en='';
                                $new_end_en='';
                            }
                        
                          /*  echo $new_start_en . " - " .$new_end_en."<br>";
                            echo $en_start_hour . " - " .  $en_end_hour."<br>";
                           */
                            $oldv_en = $en_start_hour;
                            if(!empty($new_start_en)){

                                 if($new_start_en!= $oldv_en){
                                $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $new_start_en.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$oldv_en.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>4.5</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                                }

                            }

                          if($en_end_hour != "00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $en_start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$en_end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$capacity.'</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           } else {
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $en_start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T'.$en_end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$capacity.'</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           }

                    }

                     $en_last = $this->input->post('en_end_en03_'.$count);

                     if($en_last!="00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $en_last.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>4.5</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                        }

        
               }

              $xmlString .= '</m:ProductBid>';

                    /********************************* END OF EN ********************************/

                $xmlString .= '<m:ProductBid>
                   <m:MarketProduct>
                    <m:marketProductType>DR</m:marketProductType>
                   </m:MarketProduct>';

                   if($count_set1==0){
                        $xmlString .= '<m:BidSchedule>
                        <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                        <m:timeIntervalEnd>'.$start_date.'T10:00:00+08:00</m:timeIntervalEnd>
                        <m:BidPriceCurve>
                         <m:CurveSchedData>
                          <m:xAxisData>0</m:xAxisData>
                          <m:y1AxisData>0</m:y1AxisData>
                         </m:CurveSchedData>
                         <m:CurveSchedData>
                          <m:xAxisData>'. (empty($dr_default_price) ? '0' : $dr_default_price).'</m:xAxisData>
                          <m:y1AxisData>0</m:y1AxisData>
                         </m:CurveSchedData>
                        </m:BidPriceCurve>
                       </m:BidSchedule>';


                
                } else {


                   $dr_end_set1_1 = $this->input->post('dr_start_set1_1');
                if($dr_end_set1_1!=0){
                    $xmlString .= '<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$start_date.'T'.$dr_end_set1_1.':00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>0</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                      <m:y1AxisData>0</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';
               }


                     $dr_end_set1_1 = $this->input->post('dr_start_set1_1');
                    $index=0;
                    $oldv_dr=0;
                    for($x=1;$x<=$count_set1;$x++){

                        $dr_start_hour = $this->input->post('dr_start_set1_'.$x);
                        $dr_end_hour = $this->input->post('dr_end_set1_'.$x);
                        $dr_price_change = $this->input->post('dr_price_set1_'.$x);

                          if($index==0) {
                            $oldv_dr = $dr_start_hour;
                            $index = 1;
                           
                         }
                            $y=$x-1;
                            if($y>=1){
                            $previous_end_hour_dr = $this->input->post('dr_end_set1_'.$y);
                           
                            $new_start_dr =  $previous_end_hour_dr;
                            $new_end_dr =  $dr_end_hour-1;
                            } else {
                                $new_start_dr='';
                                $new_end_dr='';
                            }
                        
                          /*  echo $new_start_en . " - " .$new_end_en."<br>";
                            echo $en_start_hour . " - " .  $en_end_hour."<br>";
                           */
                            $oldv_dr = $dr_start_hour;
                            if(!empty($new_start_dr)){

                                if($new_start_dr!= $oldv_dr){
                                    $xmlString .= '<m:BidSchedule>
                                    <m:timeIntervalStart>'.$start_date.'T'. $new_start_dr.':00:00+08:00</m:timeIntervalStart>
                                    <m:timeIntervalEnd>'.$start_date.'T'.$oldv_dr.':00:00+08:00</m:timeIntervalEnd>
                                    <m:BidPriceCurve>
                                     <m:CurveSchedData>
                                      <m:xAxisData>0</m:xAxisData>
                                      <m:y1AxisData>0</m:y1AxisData>
                                     </m:CurveSchedData>
                                     <m:CurveSchedData>
                                      <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                                      <m:y1AxisData>0</m:y1AxisData>
                                     </m:CurveSchedData>
                                    </m:BidPriceCurve>
                                   </m:BidSchedule>';
                                 }

                            }


                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$dr_end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_price_change.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';

                    }

                     $dr_last = $this->input->post('dr_end_set1_'.$count_set1);

                        if($dr_last != '10'){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_last.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T10:00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                            }
                }
                        
                            /********************* END OF FIRST SET HOUR 00-10 ONLY ****************************/

                       if($count_set2==0){

                         $xmlString .= '<m:BidSchedule>
                            <m:timeIntervalStart>'.$start_date.'T20:00:00+08:00</m:timeIntervalStart>
                            <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                            <m:BidPriceCurve>
                             <m:CurveSchedData>
                              <m:xAxisData>0</m:xAxisData>
                              <m:y1AxisData>0</m:y1AxisData>
                             </m:CurveSchedData>
                             <m:CurveSchedData>
                              <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                              <m:y1AxisData>0</m:y1AxisData>
                             </m:CurveSchedData>
                            </m:BidPriceCurve>
                           </m:BidSchedule>';

                       } else {
                        $dr_end_set2_1 = $this->input->post('dr_start_set2_1');
                if($dr_end_set2_1!=20){
                    $xmlString .= '<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T20:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$start_date.'T'.$dr_end_set2_1.':00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>0</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                      <m:y1AxisData>0</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';
               }


                     $dr_end_set2_1 = $this->input->post('dr_start_set2_1');
                    $index_set2=0;
                    $oldv_dr_set2=0;
                    for($x2=1;$x2<=$count_set2;$x2++){

                        $dr_start_hour_set2 = $this->input->post('dr_start_set2_'.$x2);
                        $dr_end_hour_set2 = $this->input->post('dr_end_set2_'.$x2);
                        $dr_price_change_set2 = $this->input->post('dr_price_set2_'.$x2);

                          if($index_set2==0) {
                            $oldv_dr_set2 = $dr_start_hour_set2;
                            $index_set2 = 1;
                           
                         }
                            $y_set2=$x2-1;
                            if($y_set2>=1){
                            $previous_end_hour_dr_set2 = $this->input->post('dr_end_set2_'.$y_set2);
                           
                            $new_start_dr_set2 =  $previous_end_hour_dr_set2;
                            $new_end_dr_set2 =  $dr_end_hour_set2-1;
                            } else {
                                $new_start_dr_set2='';
                                $new_end_dr_set2='';
                            }
                        
                          /*  echo $new_start_en . " - " .$new_end_en."<br>";
                            echo $en_start_hour . " - " .  $en_end_hour."<br>";
                           */
                            $oldv_dr_set2 = $dr_start_hour_set2;
                            if(!empty($new_start_dr_set2)){
                                if($new_start_dr_set2!= $oldv_dr_set2){
                                $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $new_start_dr_set2.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$oldv_dr_set2.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                                }

                            }

                         if($dr_end_hour_set2 != "00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_start_hour_set2.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$dr_end_hour_set2.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_price_change_set2.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           } else {
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_start_hour_set2.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T'.$dr_end_hour_set2.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_price_change_set2.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';

                           }

                    }

                     $dr_last_set2 = $this->input->post('dr_end_set2_'.$count_set2);

                     if($dr_last_set2 != '24' || $dr_last_set2 != '00'){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_last_set2.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                     }


                
            }
 


              $xmlString .= '</m:ProductBid>
                   <m:RampRateCurve>
                   <m:description>RAMP_RATE</m:description>
                   <m:CurveData>
                    <m:xAxisData>4.5</m:xAxisData>
                    <m:y1AxisData>0.2</m:y1AxisData>
                    <m:y2AxisData>0.2</m:y2AxisData>
                   </m:CurveData>
                  </m:RampRateCurve>
                 </m:GeneratingBid>
                </m:MessagePayload>
                </m:RawBidSet>';
       /* $this->output->set_content_type('text/xml');
        $this->output->set_output($xmlString);*/
        $dom = new DOMDocument;
$dom->preserveWhiteSpace = FALSE;
$dom->loadXML($xmlString);

//Save XML as a file
//$dom->save('../../../XMLExport/cenpri03.xml');

$now = date('Ymd');
$dom->save('../../../XMLExport/'.$now.'_06CENPRI_U03.xml');  
    }



       public function generate_xml_04_1(){

        $submission_date = date("Y-m-d",strtotime($this->input->post('submission_date')));
        $generator= $this->input->post('generator');
         $standing_bid= $this->input->post('standing_bid');
        $start_date =  date("Y-m-d",strtotime($this->input->post('start_date')));
        $end_date =  date("Y-m-d",strtotime($this->input->post('end_date')));
        $en_default_price = $this->input->post('en_default_price');
        if(empty($this->input->post('dr_default_price'))){
             $dr_default_price = 0;
        } else {
             $dr_default_price = $this->input->post('dr_default_price');
        }


        $count = $this->input->post('count');
        $count_dr = $this->input->post('count2');

        if(empty($count)){
            if(empty($this->input->post('en_start_04int1_1'))){
                $count=0;
            } else {
                $count=1;
            }
        }else {
            $count=$count;
        }


        if(empty($count_dr)){
            if(empty($this->input->post('dr_start_04int1_1'))){
                $count_dr=0;
            } else {
                $count_dr=1;
            }
        }else {
            $count_dr=$count_dr;
        }

     
        $xmlString ='<?xml version="1.0" encoding="UTF-8"?>
                <m:RawBidSet xmlns:m="http://pemc/soa/RawBidSet.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pemc/soa/RawBidSet.xsd RawBidSet.xsd">
                 <m:MessageHeader>
                  <m:TimeDate>'. $submission_date .'T00:00:00Z</m:TimeDate>
                  <m:Source>Default</m:Source>
                 </m:MessageHeader>
                <m:MessagePayload>
                 <m:GeneratingBid>
                  <m:startTime>'.$start_date.'T00:00:00+08:00</m:startTime>
                  <m:stopTime>'.$end_date.'T00:00:00+08:00</m:stopTime>';
                   if($standing_bid==1){
                     $xmlString.='<m:dayType>ALL</m:dayType>';
                  }
                   $xmlString.='<m:RegisteredGenerator>
                  <m:mrid>'.$generator.'</m:mrid>
                  </m:RegisteredGenerator>
                  <m:MarketParticipant>
                   <m:mrid>CENPRI_03</m:mrid>
                  </m:MarketParticipant>
                  <m:ProductBid>
                   <m:MarketProduct>
                    <m:marketProductType>EN</m:marketProductType>
                   </m:MarketProduct>';

               if($count==0){ 

                $xmlString .='<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>6.7</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';

                } else {

                    $en_end1 = $this->input->post('en_start_04int1_1');
                     if($en_end1!=0){
                    $xmlString .= '<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$start_date.'T'.$en_end1.':00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>6.7</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';
               }


                     $en_end1 = $this->input->post('en_start_04int1_1');
                    // echo "**".$en_end1;
                    $index=0;
                    $oldv_en=0;
                    for($x=1;$x<=$count;$x++){
                        //echo "x".$x."<br>";
                        $en_start_hour = $this->input->post('en_start_04int1_'.$x);
                        $en_end_hour = $this->input->post('en_end_04int1_'.$x);
                        $en_price_change = $this->input->post('en_price_04int1_'.$x);
                         $capacity = $this->input->post('en_cap_04int1_'.$x);


                          if($index==0) {
                            $oldv_en = $en_start_hour;
                            $index = 1;
                           
                         }
                            $y=$x-1;
                            if($y>=1){
                            $previous_end_hour_en = $this->input->post('en_end_04int1_'.$y);
                           
                            $new_start_en =  $previous_end_hour_en;
                            $new_end_en =  $en_end_hour-1;
                            } else {
                                $new_start_en='';
                                $new_end_en='';
                            }
                        
                         /*   echo $new_start_en . " - " .$new_end_en."<br>";
                            echo $en_start_hour . " - " .  $en_end_hour."<br>";*/
                           
                            $oldv_en = $en_start_hour;
                            if(!empty($new_start_en)){
                                if($new_start_en!= $oldv_en){
                                    $xmlString .= '<m:BidSchedule>
                                    <m:timeIntervalStart>'.$start_date.'T'. $new_start_en.':00:00+08:00</m:timeIntervalStart>
                                    <m:timeIntervalEnd>'.$start_date.'T'.$oldv_en.':00:00+08:00</m:timeIntervalEnd>
                                    <m:BidPriceCurve>
                                     <m:CurveSchedData>
                                      <m:xAxisData>0</m:xAxisData>
                                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                     </m:CurveSchedData>
                                     <m:CurveSchedData>
                                      <m:xAxisData>6.7</m:xAxisData>
                                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                     </m:CurveSchedData>
                                    </m:BidPriceCurve>
                                   </m:BidSchedule>';
                                }

                            }

                           if($en_end_hour != "00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $en_start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$en_end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$capacity.'</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           } else {
                             $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $en_start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T'.$en_end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$capacity.'</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           }

                    }

                     $en_last = $this->input->post('en_end_04int1_'.$count);

                     if($en_last!="00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $en_last.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>6.7</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';

                           }

        
               }

              $xmlString .= '</m:ProductBid>';

                    /********************************* END OF EN ********************************/

                $xmlString .= '<m:ProductBid>
                   <m:MarketProduct>
                    <m:marketProductType>DR</m:marketProductType>
                   </m:MarketProduct>';

                   if($count_dr==0){
                        $xmlString .= '<m:BidSchedule>
                        <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                        <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                        <m:BidPriceCurve>
                         <m:CurveSchedData>
                          <m:xAxisData>0</m:xAxisData>
                          <m:y1AxisData>0</m:y1AxisData>
                         </m:CurveSchedData>
                         <m:CurveSchedData>
                          <m:xAxisData>'. (empty($dr_default_price) ? '0' : $dr_default_price).'</m:xAxisData>
                          <m:y1AxisData>0</m:y1AxisData>
                         </m:CurveSchedData>
                        </m:BidPriceCurve>
                       </m:BidSchedule>';


                      
                } else {


                    $dr_end1 = $this->input->post('dr_start_04int1_1');
                     if($dr_end1!=0){
                    $xmlString .= '<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$start_date.'T'.$dr_end1.':00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>0</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                      <m:y1AxisData>0</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';
               }


                     $dr_end1 = $this->input->post('dr_start_04int1_1');
                    $index=0;
                    $oldv_dr=0;
                    for($x=1;$x<=$count_dr;$x++){

                        $dr_start_hour = $this->input->post('dr_start_04int1_'.$x);
                        $dr_end_hour = $this->input->post('dr_end_04int1_'.$x);
                        $dr_price_change = $this->input->post('dr_price_04int1_'.$x);

                          if($index==0) {
                            $oldv_dr = $dr_start_hour;
                            $index = 1;
                           
                         }
                            $y=$x-1;
                            if($y>=1){
                            $previous_end_hour_dr = $this->input->post('dr_end_04int1_'.$y);
                           
                            $new_start_dr =  $previous_end_hour_dr;
                            $new_end_dr =  $dr_end_hour-1;
                            } else {
                                $new_start_dr='';
                                $new_end_dr='';
                            }
                        
                          /*  echo $new_start_en . " - " .$new_end_en."<br>";
                            echo $en_start_hour . " - " .  $en_end_hour."<br>";
                           */
                            $oldv_dr = $dr_start_hour;
                            if(!empty($new_start_dr)){
                                  if($new_start_dr!= $oldv_dr){
                                    $xmlString .= '<m:BidSchedule>
                                    <m:timeIntervalStart>'.$start_date.'T'. $new_start_dr.':00:00+08:00</m:timeIntervalStart>
                                    <m:timeIntervalEnd>'.$start_date.'T'.$oldv_dr.':00:00+08:00</m:timeIntervalEnd>
                                    <m:BidPriceCurve>
                                     <m:CurveSchedData>
                                      <m:xAxisData>0</m:xAxisData>
                                      <m:y1AxisData>0</m:y1AxisData>
                                     </m:CurveSchedData>
                                     <m:CurveSchedData>
                                      <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                                      <m:y1AxisData>0</m:y1AxisData>
                                     </m:CurveSchedData>
                                    </m:BidPriceCurve>
                                   </m:BidSchedule>';
                                }

                            }

                           if($dr_end_hour != "00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$dr_end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_price_change.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           } else{
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T'.$dr_end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_price_change.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           }

                    }

                     $dr_last = $this->input->post('dr_end_04int1_'.$count_dr);

                      if($dr_last!="00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_last.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';

                        }

                } //end else
 


              $xmlString .= '</m:ProductBid>
                   <m:RampRateCurve>
                   <m:description>RAMP_RATE</m:description>
                   <m:CurveData>
                    <m:xAxisData>6.7</m:xAxisData>
                    <m:y1AxisData>0.4</m:y1AxisData>
                    <m:y2AxisData>0.4</m:y2AxisData>
                   </m:CurveData>
                  </m:RampRateCurve>
                 </m:GeneratingBid>
                </m:MessagePayload>
                </m:RawBidSet>';
      /*  $this->output->set_content_type('text/xml');
        $this->output->set_output($xmlString);*/
        $dom = new DOMDocument;
$dom->preserveWhiteSpace = FALSE;
$dom->loadXML($xmlString);

//Save XML as a file
//$dom->save('../../../XMLExport/cenpri04_int1.xml');

$now = date('Ymd');
$dom->save('../../../XMLExport/'.$now.'_06CENPRI_U04.xml');  
    }


 public function generate_xml_04_2(){

        $submission_date = date("Y-m-d",strtotime($this->input->post('submission_date')));
        $generator= $this->input->post('generator');
         $standing_bid= $this->input->post('standing_bid');
        $start_date =  date("Y-m-d",strtotime($this->input->post('start_date')));
        $end_date =  date("Y-m-d",strtotime($this->input->post('end_date')));
        $en_default_price = $this->input->post('en_default_price');
         if(empty($this->input->post('dr_default_price'))){
             $dr_default_price = 0;
        } else {
             $dr_default_price = $this->input->post('dr_default_price');
        }


        $count = $this->input->post('count');
        $count_dr = $this->input->post('count2');
        $count_set1 = $this->input->post('count_set1');
        $count_set2 = $this->input->post('count_set2');

        if(empty($count)){
            if(empty($this->input->post('en_start_04int2_1'))){
                $count=0;
            } else {
                $count=1;
            }
        }else {
            $count=$count;
        }

        if(empty($count_dr)){
            if(empty($this->input->post('dr_start_int2_1'))){
                $count_dr=0;
            } else {
                $count_dr=1;
            }
        }else {
            $count_dr=$count_dr;
        }

         if(empty($count_set1)){
            if(empty($this->input->post('dr_start_int2s1_1'))){
                $count_set1=0;
            } else {
                $count_set1=1;
            }
        }else {
            $count_set1=$count_set1;
        }

         if(empty($count_set2)){
            if(empty($this->input->post('dr_start_int2s2_1'))){
                $count_set2=0;
            } else {
                $count_set2=1;
            }
        }else {
            $count_set2=$count_set2;
        }
        $xmlString ='<?xml version="1.0" encoding="UTF-8"?>
                <m:RawBidSet xmlns:m="http://pemc/soa/RawBidSet.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pemc/soa/RawBidSet.xsd RawBidSet.xsd">
                 <m:MessageHeader>
                  <m:TimeDate>'. $submission_date .'T00:00:00Z</m:TimeDate>
                  <m:Source>Default</m:Source>
                 </m:MessageHeader>
                <m:MessagePayload>
                 <m:GeneratingBid>
                  <m:startTime>'.$start_date.'T00:00:00+08:00</m:startTime>
                  <m:stopTime>'.$end_date.'T00:00:00+08:00</m:stopTime>';
                   if($standing_bid==1){
                     $xmlString.='<m:dayType>ALL</m:dayType>';
                  }
                  $xmlString.='<m:RegisteredGenerator>
                  <m:mrid>'.$generator.'</m:mrid>
                  </m:RegisteredGenerator>
                  <m:MarketParticipant>
                   <m:mrid>CENPRI_03</m:mrid>
                  </m:MarketParticipant>
                  <m:ProductBid>
                   <m:MarketProduct>
                    <m:marketProductType>EN</m:marketProductType>
                   </m:MarketProduct>';

               if($count==0){ 

                $xmlString .='<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>6.7</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';

                } else {

                    $en_end1 = $this->input->post('en_start_04int2_1');
                    if($en_end1!=0){
                             $xmlString .= '<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$start_date.'T'.$en_end1.':00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>6.7</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';
               }
                   
                    $index=0;
                    $oldv_en=0;
                    for($x=1;$x<=$count;$x++){

                        $en_start_hour = $this->input->post('en_start_04int2_'.$x);
                        $en_end_hour = $this->input->post('en_end_04int2_'.$x);
                        $en_price_change = $this->input->post('en_price_04int2_'.$x);
                         $capacity = $this->input->post('en_cap_04int2_'.$x);

                          if($index==0) {
                            $oldv_en = $en_start_hour;
                            $index = 1;
                           
                         }
                            $y=$x-1;
                            if($y>=1){
                            $previous_end_hour_en = $this->input->post('en_end_04int2_'.$y);
                           
                            $new_start_en =  $previous_end_hour_en;
                            $new_end_en =  $en_end_hour-1;
                            } else {
                                $new_start_en='';
                                $new_end_en='';
                            }
                        
                          /*  echo $new_start_en . " - " .$new_end_en."<br>";
                            echo $en_start_hour . " - " .  $en_end_hour."<br>";
                           */
                            $oldv_en = $en_start_hour;
                            if(!empty($new_start_en)){
                                 if($new_start_en!= $oldv_en){
                                $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $new_start_en.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$oldv_en.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>6.7</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                             }

                            }

                         if($en_end_hour != "00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $en_start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$en_end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$capacity.'</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           } else {
                             $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $en_start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T'.$en_end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$capacity.'</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           }

                    }

                     $en_last = $this->input->post('en_end_04int2_'.$count);

                     if($en_last!="00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $en_last.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>6.7</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                        }

        
               }

              $xmlString .= '</m:ProductBid>';

                    /********************************* END OF EN ********************************/

                $xmlString .= '<m:ProductBid>
                   <m:MarketProduct>
                    <m:marketProductType>DR</m:marketProductType>
                   </m:MarketProduct>';

                   if($count_set1==0){
                        $xmlString .= '<m:BidSchedule>
                        <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                        <m:timeIntervalEnd>'.$start_date.'T10:00:00+08:00</m:timeIntervalEnd>
                        <m:BidPriceCurve>
                         <m:CurveSchedData>
                          <m:xAxisData>0</m:xAxisData>
                          <m:y1AxisData>0</m:y1AxisData>
                         </m:CurveSchedData>
                         <m:CurveSchedData>
                          <m:xAxisData>'. (empty($dr_default_price) ? '0' : $dr_default_price).'</m:xAxisData>
                          <m:y1AxisData>0</m:y1AxisData>
                         </m:CurveSchedData>
                        </m:BidPriceCurve>
                       </m:BidSchedule>';


                        $xmlString .='<m:BidSchedule>
                            <m:timeIntervalStart>'.$start_date.'T20:00:00+08:00</m:timeIntervalStart>
                            <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                            <m:BidPriceCurve>
                             <m:CurveSchedData>
                              <m:xAxisData>0</m:xAxisData>
                              <m:y1AxisData>0</m:y1AxisData>
                             </m:CurveSchedData>
                             <m:CurveSchedData>
                              <m:xAxisData>'. (empty($dr_default_price) ? '0' : $dr_default_price).'</m:xAxisData>
                              <m:y1AxisData>0</m:y1AxisData>
                             </m:CurveSchedData>
                            </m:BidPriceCurve>
                           </m:BidSchedule>';
                } else {


                   $dr_end_set1_1 = $this->input->post('dr_start_int2s1_1');
                    if($dr_end_set1_1!=0){
                    $xmlString .= '<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$start_date.'T'.$dr_end_set1_1.':00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>0</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                      <m:y1AxisData>0</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';
               }


                     $dr_end_set1_1 = $this->input->post('dr_start_int2s1_1');
                    $index=0;
                    $oldv_dr=0;
                    for($x=1;$x<=$count_set1;$x++){

                        $dr_start_hour = $this->input->post('dr_start_int2s1_'.$x);
                        $dr_end_hour = $this->input->post('dr_end_int2s1_'.$x);
                        $dr_price_change = $this->input->post('dr_price_int2s1_'.$x);

                          if($index==0) {
                            $oldv_dr = $dr_start_hour;
                            $index = 1;
                           
                         }
                            $y=$x-1;
                            if($y>=1){
                            $previous_end_hour_dr = $this->input->post('dr_end_int2s1_'.$y);
                           
                            $new_start_dr =  $previous_end_hour_dr;
                            $new_end_dr =  $dr_end_hour-1;
                            } else {
                                $new_start_dr='';
                                $new_end_dr='';
                            }
                        
                          /*  echo $new_start_en . " - " .$new_end_en."<br>";
                            echo $en_start_hour . " - " .  $en_end_hour."<br>";
                           */
                            $oldv_dr = $dr_start_hour;
                            if(!empty($new_start_dr)){
                                  if($new_start_dr!= $oldv_dr){
                                $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $new_start_dr.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$oldv_dr.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                                }

                            }

                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$dr_end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_price_change.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';

                    }

                     $dr_last = $this->input->post('dr_end_int2s1_'.$count_set1);


                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_last.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T10:00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';

                
                            /********************* END OF FIRST SET HOUR 00-10 ONLY ****************************/

                     
                if($count_set2==0){
                       $dr_end_set2_1 = $this->input->post('dr_start_int2s2_1');
                    $xmlString .= '<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T20:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$end_date.'T'.$dr_end_set2_1.':00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>0</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                      <m:y1AxisData>0</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';
               } else {
         


                     $dr_end_set2_1 = $this->input->post('dr_start_int2s2_1');
                     if($dr_end_set2_1!=20){
                         $xmlString .= '<m:BidSchedule>
                            <m:timeIntervalStart>'.$start_date.'T20:00:00+08:00</m:timeIntervalStart>
                            <m:timeIntervalEnd>'.$start_date.'T'.$dr_end_set2_1.':00:00+08:00</m:timeIntervalEnd>
                            <m:BidPriceCurve>
                             <m:CurveSchedData>
                              <m:xAxisData>0</m:xAxisData>
                              <m:y1AxisData>0</m:y1AxisData>
                             </m:CurveSchedData>
                             <m:CurveSchedData>
                              <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                              <m:y1AxisData>0</m:y1AxisData>
                             </m:CurveSchedData>
                            </m:BidPriceCurve>
                           </m:BidSchedule>';
                       }
                        
                    $index_set2=0;
                    $oldv_dr_set2=0;
                    for($x2=1;$x2<=$count_set2;$x2++){

                        $dr_start_hour_set2 = $this->input->post('dr_start_int2s2_'.$x2);
                        $dr_end_hour_set2 = $this->input->post('dr_end_int2s2_'.$x2);
                        $dr_price_change_set2 = $this->input->post('dr_price_int2s2_'.$x2);

                          if($index_set2==0) {
                            $oldv_dr_set2 = $dr_start_hour_set2;
                            $index_set2 = 1;
                           
                         }
                            $y_set2=$x2-1;
                            if($y_set2>=1){
                            $previous_end_hour_dr_set2 = $this->input->post('dr_end_int2s2_'.$y_set2);
                           
                            $new_start_dr_set2 =  $previous_end_hour_dr_set2;
                            $new_end_dr_set2 =  $dr_end_hour_set2-1;
                            } else {
                                $new_start_dr_set2='';
                                $new_end_dr_set2='';
                            }
                        
                          /*  echo $new_start_en . " - " .$new_end_en."<br>";
                            echo $en_start_hour . " - " .  $en_end_hour."<br>";
                           */
                            $oldv_dr_set2 = $dr_start_hour_set2;

                     
                            if(!empty($new_start_dr_set2)){
                                  if($new_start_dr_set2!= $oldv_dr_set2){
                                $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $new_start_dr_set2.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$oldv_dr_set2.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                                }

                            }

                         if($dr_end_hour_set2 != "00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_start_hour_set2.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$dr_end_hour_set2.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_price_change_set2.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           } else {
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_start_hour_set2.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T'.$dr_end_hour_set2.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_price_change_set2.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           }

                    }

                     $dr_last_set2 = $this->input->post('dr_end_int2s2_'.$count_set2);

                      if($dr_last_set2!="00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_last_set2.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                            }


                } //end else
            }
 
            
              $xmlString .= '</m:ProductBid>
                   <m:RampRateCurve>
                   <m:description>RAMP_RATE</m:description>
                   <m:CurveData>
                    <m:xAxisData>6.7</m:xAxisData>
                    <m:y1AxisData>0.4</m:y1AxisData>
                    <m:y2AxisData>0.4</m:y2AxisData>
                   </m:CurveData>
                  </m:RampRateCurve>
                 </m:GeneratingBid>
                </m:MessagePayload>
                </m:RawBidSet>';
       /* $this->output->set_content_type('text/xml');
        $this->output->set_output($xmlString);*/
        $dom = new DOMDocument;
    $dom->preserveWhiteSpace = FALSE;
    $dom->loadXML($xmlString);

    //Save XML as a file
   // $dom->save('../../../XMLExport/cenpri04_int2.xml');

    $now = date('Ymd');
$dom->save('../../../XMLExport/'.$now.'_06CENPRI_U04.xml');  
    }


     public function generate_xml_05_1(){

       
        $submission_date = date("Y-m-d",strtotime($this->input->post('submission_date')));
        $generator= $this->input->post('generator');
         $standing_bid= $this->input->post('standing_bid');
        $start_date =  date("Y-m-d",strtotime($this->input->post('start_date')));
        $end_date =  date("Y-m-d",strtotime($this->input->post('end_date')));
        $en_default_price = $this->input->post('en_default_price');
         if(empty($this->input->post('dr_default_price'))){
             $dr_default_price = 0;
        } else {
             $dr_default_price = $this->input->post('dr_default_price');
        }


        $count = $this->input->post('count');
        $count_dr = $this->input->post('count2');

        if(empty($count)){
            if(empty($this->input->post('en_start_04int1_1'))){
                $count=0;
            } else {
                $count=1;
            }
        }else {
            $count=$count;
        }


        if(empty($count_dr)){
            if(empty($this->input->post('dr_start_04int1_1'))){
                $count_dr=0;
            } else {
                $count_dr=1;
            }
        }else {
            $count_dr=$count_dr;
        }

     
        $xmlString ='<?xml version="1.0" encoding="UTF-8"?>
                <m:RawBidSet xmlns:m="http://pemc/soa/RawBidSet.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pemc/soa/RawBidSet.xsd RawBidSet.xsd">
                 <m:MessageHeader>
                  <m:TimeDate>'. $submission_date .'T00:00:00Z</m:TimeDate>
                  <m:Source>Default</m:Source>
                 </m:MessageHeader>
                <m:MessagePayload>
                 <m:GeneratingBid>
                  <m:startTime>'.$start_date.'T00:00:00+08:00</m:startTime>
                  <m:stopTime>'.$end_date.'T00:00:00+08:00</m:stopTime>';
                   if($standing_bid==1){
                     $xmlString.='<m:dayType>ALL</m:dayType>';
                  }
                  $xmlString.='<m:RegisteredGenerator>
                  <m:mrid>'.$generator.'</m:mrid>
                  </m:RegisteredGenerator>
                  <m:MarketParticipant>
                   <m:mrid>CENPRI_03</m:mrid>
                  </m:MarketParticipant>
                  <m:ProductBid>
                   <m:MarketProduct>
                    <m:marketProductType>EN</m:marketProductType>
                   </m:MarketProduct>';

               if($count==0){ 

                $xmlString .='<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>6.7</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';

                } else {

                    $en_end1 = $this->input->post('en_start_04int1_1');
                    if($en_end1!=0){
                    $xmlString .= '<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$start_date.'T'.$en_end1.':00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>6.7</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';
               }


                     $en_end1 = $this->input->post('en_start_04int1_1');
                    // echo "**".$en_end1;
                    $index=0;
                    $oldv_en=0;
                    for($x=1;$x<=$count;$x++){
                        //echo "x".$x."<br>";
                        $en_start_hour = $this->input->post('en_start_04int1_'.$x);
                        $en_end_hour = $this->input->post('en_end_04int1_'.$x);
                        $en_price_change = $this->input->post('en_price_04int1_'.$x);
                        $capacity = $this->input->post('en_cap_04int1_'.$x);

                          if($index==0) {
                            $oldv_en = $en_start_hour;
                            $index = 1;
                           
                         }
                            $y=$x-1;
                            if($y>=1){
                            $previous_end_hour_en = $this->input->post('en_end_04int1_'.$y);
                           
                            $new_start_en =  $previous_end_hour_en;
                            $new_end_en =  $en_end_hour-1;
                            } else {
                                $new_start_en='';
                                $new_end_en='';
                            }
                        
                         /*   echo $new_start_en . " - " .$new_end_en."<br>";
                            echo $en_start_hour . " - " .  $en_end_hour."<br>";*/
                           
                            $oldv_en = $en_start_hour;
                            if(!empty($new_start_en)){
                                 if($new_start_en!= $oldv_en){
                                $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $new_start_en.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$oldv_en.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>6.7</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                                }

                            }
                        if($en_end_hour != "00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $en_start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$en_end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$capacity.'</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           } else {
                             $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $en_start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T'.$en_end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$capacity.'</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           }

                    }

                     $en_last = $this->input->post('en_end_04int1_'.$count);

                      if($en_last!="00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $en_last.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>6.7</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                        }

        
               }

              $xmlString .= '</m:ProductBid>';

                    /********************************* END OF EN ********************************/

                $xmlString .= '<m:ProductBid>
                   <m:MarketProduct>
                    <m:marketProductType>DR</m:marketProductType>
                   </m:MarketProduct>';

                   if($count_dr==0){
                        $xmlString .= '<m:BidSchedule>
                        <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                        <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                        <m:BidPriceCurve>
                         <m:CurveSchedData>
                          <m:xAxisData>0</m:xAxisData>
                          <m:y1AxisData>0</m:y1AxisData>
                         </m:CurveSchedData>
                         <m:CurveSchedData>
                          <m:xAxisData>'. (empty($dr_default_price) ? '0' : $dr_default_price).'</m:xAxisData>
                          <m:y1AxisData>0</m:y1AxisData>
                         </m:CurveSchedData>
                        </m:BidPriceCurve>
                       </m:BidSchedule>';


                      
                } else {


                    $dr_end1 = $this->input->post('dr_start_04int1_1');
                     if($dr_end1!=0){
                    $xmlString .= '<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$start_date.'T'.$dr_end1.':00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>0</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                      <m:y1AxisData>0</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';
               }

                     $dr_end1 = $this->input->post('dr_start_04int1_1');
                    $index=0;
                    $oldv_dr=0;
                    for($x=1;$x<=$count_dr;$x++){

                        $dr_start_hour = $this->input->post('dr_start_04int1_'.$x);
                        $dr_end_hour = $this->input->post('dr_end_04int1_'.$x);
                        $dr_price_change = $this->input->post('dr_price_04int1_'.$x);

                          if($index==0) {
                            $oldv_dr = $dr_start_hour;
                            $index = 1;
                           
                         }
                            $y=$x-1;
                            if($y>=1){
                            $previous_end_hour_dr = $this->input->post('dr_end_04int1_'.$y);
                           
                            $new_start_dr =  $previous_end_hour_dr;
                            $new_end_dr =  $dr_end_hour-1;
                            } else {
                                $new_start_dr='';
                                $new_end_dr='';
                            }
                        
                          /*  echo $new_start_en . " - " .$new_end_en."<br>";
                            echo $en_start_hour . " - " .  $en_end_hour."<br>";
                           */
                            $oldv_dr = $dr_start_hour;
                            if(!empty($new_start_dr)){

                                if($new_start_dr!= $oldv_dr){
                                $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $new_start_dr.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$oldv_dr.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                                }

                            }

                        if($dr_end_hour != "00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$dr_end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_price_change.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           } else {
                                $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T'.$dr_end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_price_change.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           }

                    }

                     $dr_last = $this->input->post('dr_end_04int1_'.$count_dr);

                     if($dr_last!="00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_last.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                            }

                } //end else
 


              $xmlString .= '</m:ProductBid>
                   <m:RampRateCurve>
                   <m:description>RAMP_RATE</m:description>
                   <m:CurveData>
                    <m:xAxisData>6.7</m:xAxisData>
                    <m:y1AxisData>0.4</m:y1AxisData>
                    <m:y2AxisData>0.4</m:y2AxisData>
                   </m:CurveData>
                  </m:RampRateCurve>
                 </m:GeneratingBid>
                </m:MessagePayload>
                </m:RawBidSet>';
        /*$this->output->set_content_type('text/xml');
        $this->output->set_output($xmlString);*/
        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = FALSE;
        $dom->loadXML($xmlString);

        //Save XML as a file
        //$dom->save('../../../XMLExport/cenpri05_int1.xml');

        $now = date('Ymd');
$dom->save('../../../XMLExport/'.$now.'_06CENPRI_U05.xml');  
    }


 public function generate_xml_05_2(){

           $submission_date = date("Y-m-d",strtotime($this->input->post('submission_date')));
        $generator= $this->input->post('generator');
        $standing_bid= $this->input->post('standing_bid');
        $start_date =  date("Y-m-d",strtotime($this->input->post('start_date')));
        $end_date =  date("Y-m-d",strtotime($this->input->post('end_date')));
        $en_default_price = $this->input->post('en_default_price');
        if(empty($this->input->post('dr_default_price'))){
             $dr_default_price = 0;
        } else {
             $dr_default_price = $this->input->post('dr_default_price');
        }


        $count = $this->input->post('count');
        $count_dr = $this->input->post('count2');
        $count_set1 = $this->input->post('count_set1');
        $count_set2 = $this->input->post('count_set2');

        if(empty($count)){
            if(empty($this->input->post('en_start_04int2_1'))){
                $count=0;
            } else {
                $count=1;
            }
        }else {
            $count=$count;
        }

        if(empty($count_dr)){
            if(empty($this->input->post('dr_start_int2_1'))){
                $count_dr=0;
            } else {
                $count_dr=1;
            }
        }else {
            $count_dr=$count_dr;
        }

         if(empty($count_set1)){
            if(empty($this->input->post('dr_start_int2s1_1'))){
                $count_set1=0;
            } else {
                $count_set1=1;
            }
        }else {
            $count_set1=$count_set1;
        }

         if(empty($count_set2)){
            if(empty($this->input->post('dr_start_int2s2_1'))){
                $count_set2=0;
            } else {
                $count_set2=1;
            }
        }else {
            $count_set2=$count_set2;
        }
        $xmlString ='<?xml version="1.0" encoding="UTF-8"?>
                <m:RawBidSet xmlns:m="http://pemc/soa/RawBidSet.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://pemc/soa/RawBidSet.xsd RawBidSet.xsd">
                 <m:MessageHeader>
                  <m:TimeDate>'. $submission_date .'T00:00:00Z</m:TimeDate>
                  <m:Source>Default</m:Source>
                 </m:MessageHeader>
                <m:MessagePayload>
                 <m:GeneratingBid>
                  <m:startTime>'.$start_date.'T00:00:00+08:00</m:startTime>
                  <m:stopTime>'.$end_date.'T00:00:00+08:00</m:stopTime>';
                   if($standing_bid==1){
                     $xmlString.='<m:dayType>ALL</m:dayType>';
                  }
                  $xmlString.='<m:RegisteredGenerator>
                  <m:mrid>'.$generator.'</m:mrid>
                  </m:RegisteredGenerator>
                  <m:MarketParticipant>
                   <m:mrid>CENPRI_03</m:mrid>
                  </m:MarketParticipant>
                  <m:ProductBid>
                   <m:MarketProduct>
                    <m:marketProductType>EN</m:marketProductType>
                   </m:MarketProduct>';

               if($count==0){ 

                $xmlString .='<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>6.7</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';

                } else {

                    $en_end1 = $this->input->post('en_start_04int2_1');
                     if($en_end1!=0){
                             $xmlString .= '<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$start_date.'T'.$en_end1.':00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>6.7</m:xAxisData>
                      <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';
               }
                   
                    $index=0;
                    $oldv_en=0;
                    for($x=1;$x<=$count;$x++){

                        $en_start_hour = $this->input->post('en_start_04int2_'.$x);
                        $en_end_hour = $this->input->post('en_end_04int2_'.$x);
                        $en_price_change = $this->input->post('en_price_04int2_'.$x);
                        $capacity = $this->input->post('en_cap_04int2_'.$x);


                          if($index==0) {
                            $oldv_en = $en_start_hour;
                            $index = 1;
                           
                         }
                            $y=$x-1;
                            if($y>=1){
                            $previous_end_hour_en = $this->input->post('en_end_04int2_'.$y);
                           
                            $new_start_en =  $previous_end_hour_en;
                            $new_end_en =  $en_end_hour-1;
                            } else {
                                $new_start_en='';
                                $new_end_en='';
                            }
                        
                          /*  echo $new_start_en . " - " .$new_end_en."<br>";
                            echo $en_start_hour . " - " .  $en_end_hour."<br>";
                           */
                            $oldv_en = $en_start_hour;
                            if(!empty($new_start_en)){
                                 if($new_start_en!= $oldv_en){
                                $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $new_start_en.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$oldv_en.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>6.7</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                            }

                            }

                        if($en_end_hour != "00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $en_start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$en_end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$capacity.'</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           } else {
                                $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $en_start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T'.$en_end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$capacity.'</m:xAxisData>
                                  <m:y1AxisData>'.$en_price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           }

                    }

                     $en_last = $this->input->post('en_end_04int2_'.$count);

                     if($en_last!="00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $en_last.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>6.7</m:xAxisData>
                                  <m:y1AxisData>'.$en_default_price.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';

                        }

        
               }

              $xmlString .= '</m:ProductBid>';

                    /********************************* END OF EN ********************************/

                $xmlString .= '<m:ProductBid>
                   <m:MarketProduct>
                    <m:marketProductType>DR</m:marketProductType>
                   </m:MarketProduct>';

                   if($count_set1==0){
                        $xmlString .= '<m:BidSchedule>
                        <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                        <m:timeIntervalEnd>'.$start_date.'T10:00:00+08:00</m:timeIntervalEnd>
                        <m:BidPriceCurve>
                         <m:CurveSchedData>
                          <m:xAxisData>0</m:xAxisData>
                          <m:y1AxisData>0</m:y1AxisData>
                         </m:CurveSchedData>
                         <m:CurveSchedData>
                          <m:xAxisData>'. (empty($dr_default_price) ? '0' : $dr_default_price).'</m:xAxisData>
                          <m:y1AxisData>0</m:y1AxisData>
                         </m:CurveSchedData>
                        </m:BidPriceCurve>
                       </m:BidSchedule>';


                        $xmlString .='<m:BidSchedule>
                            <m:timeIntervalStart>'.$start_date.'T20:00:00+08:00</m:timeIntervalStart>
                            <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                            <m:BidPriceCurve>
                             <m:CurveSchedData>
                              <m:xAxisData>0</m:xAxisData>
                              <m:y1AxisData>0</m:y1AxisData>
                             </m:CurveSchedData>
                             <m:CurveSchedData>
                              <m:xAxisData>'. (empty($dr_default_price) ? '0' : $dr_default_price).'</m:xAxisData>
                              <m:y1AxisData>0</m:y1AxisData>
                             </m:CurveSchedData>
                            </m:BidPriceCurve>
                           </m:BidSchedule>';
                } else {


                   $dr_end_set1_1 = $this->input->post('dr_start_int2s1_1');
                    if($dr_end_set1_1!=0){
                    $xmlString .= '<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T00:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$start_date.'T'.$dr_end_set1_1.':00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>0</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                      <m:y1AxisData>0</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';
               }

                     $dr_end_set1_1 = $this->input->post('dr_start_int2s1_1');
                    $index=0;
                    $oldv_dr=0;
                    for($x=1;$x<=$count_set1;$x++){

                        $dr_start_hour = $this->input->post('dr_start_int2s1_'.$x);
                        $dr_end_hour = $this->input->post('dr_end_int2s1_'.$x);
                        $dr_price_change = $this->input->post('dr_price_int2s1_'.$x);

                          if($index==0) {
                            $oldv_dr = $dr_start_hour;
                            $index = 1;
                           
                         }
                            $y=$x-1;
                            if($y>=1){
                            $previous_end_hour_dr = $this->input->post('dr_end_int2s1_'.$y);
                           
                            $new_start_dr =  $previous_end_hour_dr;
                            $new_end_dr =  $dr_end_hour-1;
                            } else {
                                $new_start_dr='';
                                $new_end_dr='';
                            }
                        
                          /*  echo $new_start_en . " - " .$new_end_en."<br>";
                            echo $en_start_hour . " - " .  $en_end_hour."<br>";
                           */
                            $oldv_dr = $dr_start_hour;
                            if(!empty($new_start_dr)){
                                  if($new_start_dr!= $oldv_dr){
                                $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $new_start_dr.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$oldv_dr.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                             }

                            }

                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$dr_end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_price_change.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';

                    }

                     $dr_last = $this->input->post('dr_end_int2s1_'.$count_set1);


                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_last.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T10:00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';

                
                            /********************* END OF FIRST SET HOUR 00-10 ONLY ****************************/

                     
                if($count_set2==0){
                       $dr_end_set2_1 = $this->input->post('dr_start_int2s2_1');
                    $xmlString .= '<m:BidSchedule>
                    <m:timeIntervalStart>'.$start_date.'T20:00:00+08:00</m:timeIntervalStart>
                    <m:timeIntervalEnd>'.$end_date.'T'.$dr_end_set2_1.':00:00+08:00</m:timeIntervalEnd>
                    <m:BidPriceCurve>
                     <m:CurveSchedData>
                      <m:xAxisData>0</m:xAxisData>
                      <m:y1AxisData>0</m:y1AxisData>
                     </m:CurveSchedData>
                     <m:CurveSchedData>
                      <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                      <m:y1AxisData>0</m:y1AxisData>
                     </m:CurveSchedData>
                    </m:BidPriceCurve>
                   </m:BidSchedule>';
               } else {
         


                     $dr_end_set2_1 = $this->input->post('dr_start_int2s2_1');
                     if($dr_end_set2_1!=20){
                         $xmlString .= '<m:BidSchedule>
                            <m:timeIntervalStart>'.$start_date.'T20:00:00+08:00</m:timeIntervalStart>
                            <m:timeIntervalEnd>'.$start_date.'T'.$dr_end_set2_1.':00:00+08:00</m:timeIntervalEnd>
                            <m:BidPriceCurve>
                             <m:CurveSchedData>
                              <m:xAxisData>0</m:xAxisData>
                              <m:y1AxisData>0</m:y1AxisData>
                             </m:CurveSchedData>
                             <m:CurveSchedData>
                              <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                              <m:y1AxisData>0</m:y1AxisData>
                             </m:CurveSchedData>
                            </m:BidPriceCurve>
                           </m:BidSchedule>';
                        }
                    $index_set2=0;
                    $oldv_dr_set2=0;
                    for($x2=1;$x2<=$count_set2;$x2++){

                        $dr_start_hour_set2 = $this->input->post('dr_start_int2s2_'.$x2);
                        $dr_end_hour_set2 = $this->input->post('dr_end_int2s2_'.$x2);
                        $dr_price_change_set2 = $this->input->post('dr_price_int2s2_'.$x2);

                          if($index_set2==0) {
                            $oldv_dr_set2 = $dr_start_hour_set2;
                            $index_set2 = 1;
                           
                         }
                            $y_set2=$x2-1;
                            if($y_set2>=1){
                            $previous_end_hour_dr_set2 = $this->input->post('dr_end_int2s2_'.$y_set2);
                           
                            $new_start_dr_set2 =  $previous_end_hour_dr_set2;
                            $new_end_dr_set2 =  $dr_end_hour_set2-1;
                            } else {
                                $new_start_dr_set2='';
                                $new_end_dr_set2='';
                            }
                        
                          /*  echo $new_start_en . " - " .$new_end_en."<br>";
                            echo $en_start_hour . " - " .  $en_end_hour."<br>";
                           */
                            $oldv_dr_set2 = $dr_start_hour_set2;

                     
                            if(!empty($new_start_dr_set2)){
                                  if($new_start_dr_set2!= $oldv_dr_set2){
                                $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $new_start_dr_set2.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$oldv_dr_set2.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                                }

                            }

                        if($dr_end_hour_set2 != "00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_start_hour_set2.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$dr_end_hour_set2.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_price_change_set2.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           } else {
                             $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_start_hour_set2.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T'.$dr_end_hour_set2.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_price_change_set2.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           }

                    }

                     $dr_last_set2 = $this->input->post('dr_end_int2s2_'.$count_set2);

                    if($dr_last_set2!="00"){
                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $dr_last_set2.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$end_date.'T00:00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>'.$dr_default_price.'</m:xAxisData>
                                  <m:y1AxisData>0</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';
                           }


                } //end else
            }
 
            
              $xmlString .= '</m:ProductBid>
                   <m:RampRateCurve>
                   <m:description>RAMP_RATE</m:description>
                   <m:CurveData>
                    <m:xAxisData>6.7</m:xAxisData>
                    <m:y1AxisData>0.4</m:y1AxisData>
                    <m:y2AxisData>0.4</m:y2AxisData>
                   </m:CurveData>
                  </m:RampRateCurve>
                 </m:GeneratingBid>
                </m:MessagePayload>
                </m:RawBidSet>';


  
        /*$this->output->set_content_type('text/xml');
        $this->output->set_output($xmlString);*/

          $dom = new DOMDocument;
        $dom->preserveWhiteSpace = FALSE;
        $dom->loadXML($xmlString);

        //Save XML as a file
       // $dom->save('../../../XMLExport/cenpri05_int2.xml');
        $now = date('Ymd');
$dom->save('../../../XMLExport/'.$now.'_06CENPRI_U05.xml');  
    }

}