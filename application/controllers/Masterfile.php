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
        $start_date =  date("Y-m-d",strtotime($this->input->post('start_date')));
        $end_date =  date("Y-m-d",strtotime($this->input->post('end_date')));
        $default_price = $this->input->post('default_price');


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
              <m:TimeDate>'.$submission_date.'T00:00:00Z</m:TimeDate>
              <m:Source>Default</m:Source>
             </m:MessageHeader>
            <m:MessagePayload>
             <m:GeneratingBid>
              <m:startTime>'.$start_date.'T00:00:00+08:00</m:startTime>
              <m:stopTime>'.$end_date.'T00:00:00+08:00</m:stopTime>
              <m:RegisteredGenerator>
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

                    $end1 = $this->input->post('start1') - 1;

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



                    $end1 = $this->input->post('start1') - 1;
                /*    echo "00 - " . $end1 ."<br>";*/
                    $index=0;
                    $oldv=0;
                    for($x=1;$x<=$count;$x++){

                        $start_hour = $this->input->post('start'.$x);
                        $end_hour = $this->input->post('end'.$x);
                        $price_change = $this->input->post('price'.$x);

                          if($index==0) {
                            $oldv = $start_hour;
                            $index = 1;
                           
                         }
                            $y=$x-1;
                            if($y>=1){
                            $previous_end_hour = $this->input->post('end'.$y);
                           
                            $new_start =  $previous_end_hour+1;
                            $new_end =  $end_hour-1;
                            } else {
                                $new_start='';
                                $new_end='';
                            }
                        
                        /*     echo $new_start . " - " .$new_end."<br>";
                            echo $start_hour . " - " .  $end_hour."<br>";*/
                           
                            $oldv = $start_hour;
                            if(!empty($new_start)){

                               $xmlString .= '<m:BidSchedule>
                                    <m:timeIntervalStart>'.$start_date.'T'. $new_start.':00:00+08:00</m:timeIntervalStart>
                                    <m:timeIntervalEnd>'.$start_date.'T'.$new_end.':00:00+08:00</m:timeIntervalEnd>
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

                               $xmlString .= '<m:BidSchedule>
                                    <m:timeIntervalStart>'.$start_date.'T'. $start_hour.':00:00+08:00</m:timeIntervalStart>
                                    <m:timeIntervalEnd>'.$start_date.'T'.$end_hour.':00:00+08:00</m:timeIntervalEnd>
                                    <m:BidPriceCurve>
                                     <m:CurveSchedData>
                                      <m:xAxisData>0</m:xAxisData>
                                      <m:y1AxisData>'.$price_change.'</m:y1AxisData>
                                     </m:CurveSchedData>
                                     <m:CurveSchedData>
                                      <m:xAxisData>4.5</m:xAxisData>
                                      <m:y1AxisData>'.$price_change.'</m:y1AxisData>
                                     </m:CurveSchedData>
                                    </m:BidPriceCurve>
                                   </m:BidSchedule>';

                    }

                     $last = $this->input->post('end'.$count)+1;


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
        $this->output->set_content_type('text/xml');
        $this->output->set_output($xmlString);
    }


    public function generate_xml_02(){

        $submission_date = date("Y-m-d",strtotime($this->input->post('submission_date')));
        $generator= $this->input->post('generator');
        $start_date =  date("Y-m-d",strtotime($this->input->post('start_date')));
        $end_date =  date("Y-m-d",strtotime($this->input->post('end_date')));
        $default_price = $this->input->post('default_price');


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
              <m:stopTime>'.$end_date.'T00:00:00+08:00</m:stopTime>
              <m:RegisteredGenerator>
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

                    $end1 = $this->input->post('start1') - 1;

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



                    $end1 = $this->input->post('start1') - 1;
                /*    echo "00 - " . $end1 ."<br>";*/
                    $index=0;
                    $oldv=0;
                    for($x=1;$x<=$count;$x++){

                        $start_hour = $this->input->post('start'.$x);
                        $end_hour = $this->input->post('end'.$x);
                        $price_change = $this->input->post('price'.$x);

                          if($index==0) {
                            $oldv = $start_hour;
                            $index = 1;
                           
                         }
                            $y=$x-1;
                            if($y>=1){
                            $previous_end_hour = $this->input->post('end'.$y);
                           
                            $new_start =  $previous_end_hour+1;
                            $new_end =  $end_hour-1;
                            } else {
                                $new_start='';
                                $new_end='';
                            }
                        
                        /*     echo $new_start . " - " .$new_end."<br>";
                            echo $start_hour . " - " .  $end_hour."<br>";*/
                           
                            $oldv = $start_hour;
                            if(!empty($new_start)){
                                $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $new_start.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$new_end.':00:00+08:00</m:timeIntervalEnd>
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

                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>4.5</m:xAxisData>
                                  <m:y1AxisData>'.$price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';

                    }

                     $last = $this->input->post('end'.$count)+1;


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
        $this->output->set_content_type('text/xml');
        $this->output->set_output($xmlString);
    }

    public function generate_xml_03(){

        $submission_date = date("Y-m-d",strtotime($this->input->post('submission_date')));
        $generator= $this->input->post('generator');
        $start_date =  date("Y-m-d",strtotime($this->input->post('start_date')));
        $end_date =  date("Y-m-d",strtotime($this->input->post('end_date')));
        $default_price = $this->input->post('default_price');


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
                  <m:stopTime>'.$end_date.'T00:00:00+08:00</m:stopTime>
                  <m:RegisteredGenerator>
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

                    $end1 = $this->input->post('start1') - 1;

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



                    $end1 = $this->input->post('start1') - 1;
                /*    echo "00 - " . $end1 ."<br>";*/
                    $index=0;
                    $oldv=0;
                    for($x=1;$x<=$count;$x++){

                        $start_hour = $this->input->post('start'.$x);
                        $end_hour = $this->input->post('end'.$x);
                        $price_change = $this->input->post('price'.$x);

                          if($index==0) {
                            $oldv = $start_hour;
                            $index = 1;
                           
                         }
                            $y=$x-1;
                            if($y>=1){
                            $previous_end_hour = $this->input->post('end'.$y);
                           
                            $new_start =  $previous_end_hour+1;
                            $new_end =  $end_hour-1;
                            } else {
                                $new_start='';
                                $new_end='';
                            }
                        
                        /*     echo $new_start . " - " .$new_end."<br>";
                            echo $start_hour . " - " .  $end_hour."<br>";*/
                           
                            $oldv = $start_hour;
                            if(!empty($new_start)){
                                $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $new_start.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$new_end.':00:00+08:00</m:timeIntervalEnd>
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

                            $xmlString .= '<m:BidSchedule>
                                <m:timeIntervalStart>'.$start_date.'T'. $start_hour.':00:00+08:00</m:timeIntervalStart>
                                <m:timeIntervalEnd>'.$start_date.'T'.$end_hour.':00:00+08:00</m:timeIntervalEnd>
                                <m:BidPriceCurve>
                                 <m:CurveSchedData>
                                  <m:xAxisData>0</m:xAxisData>
                                  <m:y1AxisData>'.$price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                 <m:CurveSchedData>
                                  <m:xAxisData>4.5</m:xAxisData>
                                  <m:y1AxisData>'.$price_change.'</m:y1AxisData>
                                 </m:CurveSchedData>
                                </m:BidPriceCurve>
                               </m:BidSchedule>';

                    }

                     $last = $this->input->post('end'.$count)+1;


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
        $this->output->set_content_type('text/xml');
        $this->output->set_output($xmlString);
    }


}
