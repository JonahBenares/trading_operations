<style>
#hidden {
    display: none;
}
#type_en {
    display: none;
}
#type_dr {
    display: none;
}
#type_dr_interval1 {
    display: none;
}
#type_dr_interval2 {
    display: none;
}
</style>
<div class="page-wrapper" style="margin: 0px;"> 
    <div class="container-fluid" style="margin-top:50px">
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="card card-new">
                    <div class="card-body card-margin" >
                        <form method='POST' name='generate_xml' id='generate_xml' target='_blank'  accept-charset="UTF-8" >
                            <div class="row">                              
                                <div class="col-12">
                                    <h2><span class="mdi mdi-file-document"></span> FORM</h2>
                                      <div class="form-group">    
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <p class="margin-b0">Submission Date:</p>
                                                <input type="date" class="form-control" name="submission_date" required>
                                            </div>
                                            <div class="col-lg-6">
                                                <p class="margin-b0">Generator:</p>
                                                <select class="form-control" name='generator' id='generator' onchange="genXML()"  required >
                                                    <option value='' selected>Select Generator</option>
                                                    <option value='06CENPRI_U01'>06CENPRI_U01</option>
                                                    <option value='06CENPRI_U02'>06CENPRI_U02</option>
                                                    <option value='06CENPRI_U03'>06CENPRI_U03</option>
                                                    <option value='06CENPRI_U04'>06CENPRI_U04</option>
                                                    <option value='06CENPRI_U05'>06CENPRI_U05</option>
                                                </select>   
                                            </div>
                                        </div>                                                        
                                    </div> 
                                    <div class="form-group" id='hidden'>    
                                        <p class="margin-b0">Intervals:</p>
                                        <select class="form-control" name='interval' id='interval' onchange="interval_type()" >
                                            <option value='' selected>Select Interval</option>
                                            <option value='1'>24 Hrs</option>
                                            <option value='2'>1-10 & 21-24</option>
                                        </select>                                                                      
                                    </div> 
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <p class="margin-b0">Start Date:</p>
                                                <input type="date" class="form-control" name="start_date" required>
                                            </div>
                                            <div class="col-lg-6">
                                                <p class="margin-b0">End Date:</p>
                                                <input type="date" class="form-control" name="end_date" required>
                                            </div>
                                        </div>                                    
                                    </div>  
                                    <div class="form-group">
                                         <div class="row">
                                            <div class="col-lg-6">
                                                <p class="margin-b0">EN Default Price:</p>
                                                <input type="number" class="form-control" name="en_default_price">
                                            </div>

                                            <div class="col-lg-6">
                                                <p class="margin-b0">DR Capacity (MW):</p>
                                                <input type="number" class="form-control" step="any" name="dr_default_price">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                         <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group" style=" display: flex; /* or inline-flex */">
                                                    <p class="margin-b0">Standing Bid:</p>
                                                    <input type="checkbox" name="standing_bid" value='1' style="margin-left:10px;margin-top: 3px;">
                                                </div>                                                
                                            </div>

                                          
                                        </div>
                                    </div> 
                                    <br>
                                    <!-------------------------------   USED BY CENPRI01 AND CENPRI02  ------------------------------->
                                    <div id='type_en'>
                                          <h5 class="margin-b0">Price Change:</h5>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <table width="100%" id='myTable' >
                                                <thead>
                                                <tr>
                                                    <td width="25%"><p>Start Hour</p></td>
                                                    <td width="25%"><p>End Hour</p></td>
                                                    <td width="20%"><p>Capacity (MW)</p></td>
                                                    <td width="20%"><p>Price</p></td>
                                                    <td width="10%" align="center"><p><i class="mdi mdi-view-sequential"></i></p></td>
                                                </tr>
                                            </thead>
                                                 <tbody>
                                                <tr>
                                                    <td><input type="number" class="form-control" name="start1" min='0' max='24'></td>
                                                    <td><input type="number" class="form-control" name="end1" min='0' max='24'></td>
                                                    <td><input type="number" class="form-control" name="capacity1" step="any"></td>
                                                    <td><input type="number" class="form-control" name="price1"></td>
                                                    <td align="center">
                                                        <a href="javascript:void(0)" class="btn btn-primary btn-xs"  onclick="addFields('en')"><i class="mdi mdi-plus"></i></a>
                                                        <a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="removeFields('en')"><i class="mdi mdi-window-close"></i></a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>  
            
                                    </div>
                                     <!-------------------------------  END USED BY CENPRI01 AND CENPRI02   -------------------------->
                                   

                                     <!-------------------------------   USED BY CENPRI03   -------------------------->


                                    <div id='type_dr'>
                                        <h5 class="margin-b0">Price Change:</h5>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <table width="100%" id='myTable1_03' >
                                                    <thead>
                                                        <tr>
                                                            <td colspan="4"><b>For EN</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td width="25%"><p>Start Hour</p></td>
                                                            <td width="25%"><p>End Hour</p></td>
                                                            <td width="20%"><p>Capacity (MW)</p></td>
                                                            <td width="20%"><p>Price</p></td>
                                                            <td width="10%" align="center"><p><i class="mdi mdi-view-sequential"></i></p></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><input type="number" class="form-control" name="en_start_en03_1" min='0' max='24'></td>
                                                            <td><input type="number" class="form-control" name="en_end_en03_1" min='0' max='24'></td>
                                                            <td><input type="number" class="form-control" name="en_cap_en03_1" step="any"></td>
                                                            <td><input type="number" class="form-control" name="en_price_en03_1"></td>
                                                            <td align="center">
                                                                <a href="javascript:void(0)" class="btn btn-primary btn-xs"  onclick="addFields('en')"><i class="mdi mdi-plus"></i></a>
                                                                <a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="removeFields('en')"><i class="mdi mdi-window-close"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                               
                                            </div>
                                             <div class="col-lg-6" style="border-left: 1px solid #aeaeae;">
                                                <table width="100%" id='myTable2_set1' >
                                                    <thead>
                                                        <tr>
                                                            <td colspan="4"><b>For DR</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4"><b>SET 1 (00 - 10 HR ONLY)</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td width="29%"><p>Start Hour</p></td>
                                                            <td width="29%"><p>End Hour</p></td>
                                                            <td width="29%"><p>Capacity (MW)</p></td>
                                                            <td width="13%" align="center"><p><i class="mdi mdi-view-sequential"></i></p></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><input type="number" class="form-control" name="dr_start_set1_1" id="dr_start1" min='0' max='24'></td>
                                                            <td><input type="number" class="form-control" name="dr_end_set1_1" min='0' max='24'></td>
                                                            <td><input type="number" class="form-control" name="dr_price_set1_1" step="any" ></td>
                                                            <td align="center">
                                                                <a href="javascript:void(0)" class="btn btn-primary btn-xs"  onclick="addFields('dr_set1')"><i class="mdi mdi-plus"></i></a>
                                                                <a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="removeFields('dr_set1')"><i class="mdi mdi-window-close"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <br>
                                                  <table width="100%" id='myTable2_set2' >
                                                    <thead>
                                                       
                                                        <tr>
                                                            <td colspan="4"><b>SET 2 (20 - 00 HR ONLY)</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td width="29%"><p>Start Hour</p></td>
                                                            <td width="29%"><p>End Hour</p></td>
                                                            <td width="29%"><p>Capacity (MW)</p></td>
                                                            <td width="13%" align="center"><p><i class="mdi mdi-view-sequential"></i></p></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><input type="number" class="form-control" name="dr_start_set2_1"  min='0' max='24'></td>
                                                            <td><input type="number" class="form-control" name="dr_end_set2_1" min='0' max='24'></td>
                                                            <td><input type="number" class="form-control" name="dr_price_set2_1" step="any" ></td>
                                                            <td align="center">
                                                                <a href="javascript:void(0)" class="btn btn-primary btn-xs"  onclick="addFields('dr_set2')"><i class="mdi mdi-plus"></i></a>
                                                                <a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="removeFields('dr_set2')"><i class="mdi mdi-window-close"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div> 
                                    </div> 


                                    <!-------------------------------  END USED BY CENPRI03 -------------------------->

                                      <!-------------------------------   USED BY CENPRI04 INTERVAL 1 (24HOURS)   -------------------------->
                                    <div id='type_dr_interval1'>
                                        <h5 class="margin-b0">Price Change:</h5>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <table width="100%" id='myTable1_interval1' >
                                                    <thead>
                                                        <tr>
                                                            <td colspan="4"><b>For EN</b></td>
                                                        </tr>
                                                        <tr>
                                                             <td width="25%"><p>Start Hour</p></td>
                                                    <td width="25%"><p>End Hour</p></td>
                                                    <td width="20%"><p>Capacity (MW)</p></td>
                                                    <td width="20%"><p>Price</p></td>
                                                    <td width="10%" align="center"><p><i class="mdi mdi-view-sequential"></i></p></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><input type="number" class="form-control" name="en_start_04int1_1" min='0' max='24'></td>
                                                            <td><input type="number" class="form-control" name="en_end_04int1_1" min='0' max='24'></td>
                                                            <td><input type="number" class="form-control" name="en_cap_04int1_1" step="any"></td>
                                                            <td><input type="number" class="form-control" name="en_price_04int1_1"></td>
                                                            <td align="center">
                                                                <a href="javascript:void(0)" class="btn btn-primary btn-xs"  onclick="addFields('en04_int1')"><i class="mdi mdi-plus"></i></a>
                                                                <a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="removeFields('en04_int1')"><i class="mdi mdi-window-close"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                               
                                            </div>
                                             <div class="col-lg-6" style="border-left: 1px solid #aeaeae;">
                                                <table width="100%" id='myTable1_interval1_dr' >
                                                    <thead>
                                                        <tr>
                                                            <td colspan="4"><b>For DR</b></td>
                                                        </tr>
                                                    
                                                        <tr>
                                                            <td width="29%"><p>Start Hour</p></td>
                                                            <td width="29%"><p>End Hour</p></td>
                                                            <td width="29%"><p>Capacity (MW)</p></td>
                                                            <td width="13%" align="center"><p><i class="mdi mdi-view-sequential"></i></p></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><input type="number" class="form-control" name="dr_start_04int1_1" id="dr_start1" min='0' max='24'></td>
                                                            <td><input type="number" class="form-control" name="dr_end_04int1_1" min='0' max='24'></td>
                                                            <td><input type="number" class="form-control" name="dr_price_04int1_1" step="any" ></td>
                                                            <td align="center">
                                                                <a href="javascript:void(0)" class="btn btn-primary btn-xs"  onclick="addFields('dr04_int1')"><i class="mdi mdi-plus"></i></a>
                                                                <a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="removeFields('dr04_int1')"><i class="mdi mdi-window-close"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                            </div>
                                        </div> 
                                    </div> 

                                      <!------------------------------- END USED BY CENPRI04 INTERVAL 1 (24HOURS)  --------------------------->

                                    <!------------------------------- USED BY CENPRI04 INTERVAL 2 (1-10 AND 21-24HOURS) ----------------------->
                                       <div id='type_dr_interval2'>
                                        <h5 class="margin-b0">Price Change:</h5>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <table width="100%" id='myTable1_04_int2' >
                                                    <thead>
                                                        <tr>
                                                            <td colspan="4"><b>For EN</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td width="25%"><p>Start Hour</p></td>
                                                    <td width="25%"><p>End Hour</p></td>
                                                    <td width="20%"><p>Capacity (MW)</p></td>
                                                    <td width="20%"><p>Price</p></td>
                                                    <td width="10%" align="center"><p><i class="mdi mdi-view-sequential"></i></p></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><input type="number" class="form-control" name="en_start_04int2_1" id="en_start1" min='0' max='24'></td>
                                                            <td><input type="number" class="form-control" name="en_end_04int2_1" min='0' max='24'></td>
                                                            <td><input type="number" class="form-control" name="en_cap_04int2_1" step="any"></td>
                                                            <td><input type="number" class="form-control" name="en_price_04int2_1"></td>
                                                            <td align="center">
                                                                <a href="javascript:void(0)" class="btn btn-primary btn-xs"  onclick="addFields('en04_int2')"><i class="mdi mdi-plus"></i></a>
                                                                <a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="removeFields('en04_int2')"><i class="mdi mdi-window-close"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                               
                                            </div>
                                             <div class="col-lg-6" style="border-left: 1px solid #aeaeae;">
                                                <table width="100%" id='myTable1_04int2_set1' >
                                                    <thead>
                                                        <tr>
                                                            <td colspan="4"><b>For DR</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4"><b>INTERVAL 01 - 10 HR</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td width="29%"><p>Start Hour</p></td>
                                                            <td width="29%"><p>End Hour</p></td>
                                                            <td width="29%"><p>Capacity (MW)</p></td>
                                                            <td width="13%" align="center"><p><i class="mdi mdi-view-sequential"></i></p></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><input type="number" class="form-control" name="dr_start_int2s1_1" id="dr_start1" min='0' max='24'></td>
                                                            <td><input type="number" class="form-control" name="dr_end_int2s1_1" min='0' max='24'></td>
                                                            <td><input type="number" class="form-control" name="dr_price_int2s1_1" step="any" ></td>
                                                            <td align="center">
                                                                <a href="javascript:void(0)" class="btn btn-primary btn-xs"  onclick="addFields('dr04_int2s1')"><i class="mdi mdi-plus"></i></a>
                                                                <a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="removeFields('dr04_int2s1')"><i class="mdi mdi-window-close"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <br>
                                                  <table width="100%" id='myTable1_04int2_set2' >
                                                    <thead>
                                                       
                                                        <tr>
                                                            <td colspan="4"><b>INTERVAL 21 - 24 HR</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td width="29%"><p>Start Hour</p></td>
                                                            <td width="29%"><p>End Hour</p></td>
                                                            <td width="29%"><p>Capacity (MW)</p></td>
                                                            <td width="13%" align="center"><p><i class="mdi mdi-view-sequential"></i></p></td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><input type="number" class="form-control" name="dr_start_int2s2_1"  min='0' max='24'></td>
                                                            <td><input type="number" class="form-control" name="dr_end_int2s2_1" min='0' max='24'></td>
                                                            <td><input type="number" class="form-control" name="dr_price_int2s2_1" step="any" ></td>
                                                            <td align="center">
                                                                <a href="javascript:void(0)" class="btn btn-primary btn-xs"  onclick="addFields('dr04_int2s2')"><i class="mdi mdi-plus"></i></a>
                                                                <a href="javascript:void(0)" class="btn btn-danger btn-xs" onclick="removeFields('dr04_int2s2')"><i class="mdi mdi-window-close"></i></a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div> 
                                    </div> 

                                    <!-------------------------- END USED BY CENPRI04 INTERVAL 2 (1-10 AND 21-24HOURS) --------------------------->



                                         <input type='hidden' id='count' name='count'>
                                        <input type='hidden' id='count2' name='count2'>
                                         <input type='hidden' id='count_set1' name='count_set1'>
                                         <input type='hidden' id='count_set2' name='count_set2'>
                                        <input type='hidden' id='url' name='url' value="<?php echo base_url(); ?>">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <br>
                                                <input type="submit" class="btn btn-md btn-info btn-block" value="Generate XML" name="generateXML">
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </form>                     
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script type='text/javascript'>
    function addFields(type){
        if(type=='en'){
       

             var rowCount_a = $('#myTable tbody tr').length;
            var ct_a=rowCount_a+1;
            markup = "<tr><td><input type='number' class='form-control' name='start"+ct_a+"' min='0' max='24'></td><td><input type='number' class='form-control' name='end"+ct_a+"' min='0' max='24'></td><td><input type='number' class='form-control' name='capacity"+ct_a+"' step='any'></td><td><input type='number' class='form-control' name='price"+ct_a+"'></td></tr>";
            tableBody = $("#myTable tbody");
            tableBody.append(markup);
            document.getElementById("count").value = ct_a;


             var rowCount_03 = $('#myTable1_03 tbody tr').length;
            var ct_03=rowCount_03+1;
            markup = "<tr><td><input type='number' class='form-control' name='en_start_en03_"+ct_03+"' min='0' max='24'></td><td><input type='number' class='form-control' name='en_end_en03_"+ct_03+"' min='0' max='24'></td><td><input type='number' class='form-control' name='en_cap_en03_"+ct_03+"' step='any'></td><td><input type='number' class='form-control' name='en_price_en03_"+ct_03+"'></td></tr>";
            tableBody = $("#myTable1_03 tbody");
            tableBody.append(markup);
            document.getElementById("count").value = ct_03;
          
        }

        if(type=='en04_int1'){

            var rowCount_04in1 = $('#myTable1_interval1 tbody tr').length;
            var ct_04=rowCount_04in1+1;
            markup = "<tr><td><input type='number' class='form-control' name='en_start_04int1_"+ct_04+"' min='0' max='24'></td><td><input type='number' class='form-control' name='en_end_04int1_"+ct_04+"' min='0' max='24'></td><td><input type='number' class='form-control' name='en_cap_04int1_"+ct_04+"' step='any'></td><td><input type='number' class='form-control' name='en_price_04int1_"+ct_04+"'></td></tr>";
            tableBody = $("#myTable1_interval1 tbody");
            tableBody.append(markup);
            document.getElementById("count").value = ct_04;

        }

         if(type=='en04_int2'){

            var rowCount_04in2 = $('#myTable1_04_int2 tbody tr').length;
            var ct_04=rowCount_04in2+1;
            markup = "<tr><td><input type='number' class='form-control' name='en_start_04int2_"+ct_04+"' min='0' max='24'></td><td><input type='number' class='form-control' name='en_end_04int2_"+ct_04+"' min='0' max='24'></td><td><input type='number' class='form-control' name='en_cap_04int2_"+ct_04+"' step='any'></td><td><input type='number' class='form-control' name='en_price_04int2_"+ct_04+"'></td></tr>";
            tableBody = $("#myTable1_04_int2 tbody");
            tableBody.append(markup);
            document.getElementById("count").value = ct_04;

        }


      
         if(type=='dr04_int2s1'){
            
            var rowCount_set1 = $('#myTable1_04int2_set1 tbody tr').length;
            var ct1=rowCount_set1+1;
            markup = "<tr><td><input type='number' class='form-control' name='dr_start_int2s1_"+ct1+"' min='0' max='24'></td><td><input type='number' class='form-control' name='dr_end_int2s1_"+ct1+"' min='0' max='24'></td><td><input type='number' class='form-control' name='dr_price_int2s1_"+ct1+"' step='any' ></td></tr>";
            tableBody = $("#myTable1_04int2_set1 tbody");
            tableBody.append(markup);
            document.getElementById("count_set1").value = ct1;
          
        }

            if(type=='dr04_int2s2'){
            
            var rowCount_set1 = $('#myTable1_04int2_set2 tbody tr').length;
            var ct1=rowCount_set1+1;
            markup = "<tr><td><input type='number' class='form-control' name='dr_start_int2s2_"+ct1+"' min='0' max='24'></td><td><input type='number' class='form-control' name='dr_end_int2s2_"+ct1+"' min='0' max='24'></td><td><input type='number' class='form-control' name='dr_price_int2s2_"+ct1+"'  step='any' ></td></tr>";
            tableBody = $("#myTable1_04int2_set2 tbody");
            tableBody.append(markup);
            document.getElementById("count_set2").value = ct1;
          
        }


         if(type=='dr_set1'){
            
            var rowCount_set1 = $('#myTable2_set1 tbody tr').length;
            var ct1=rowCount_set1+1;
            markup = "<tr><td><input type='number' class='form-control' name='dr_start_set1_"+ct1+"' min='0' max='24'></td><td><input type='number' class='form-control' name='dr_end_set1_"+ct1+"' min='0' max='24'></td><td><input type='number' class='form-control' name='dr_price_set1_"+ct1+"' step='any' ></td></tr>";
            tableBody = $("#myTable2_set1 tbody");
            tableBody.append(markup);
            document.getElementById("count_set1").value = ct1;
          
        }

         if(type=='dr_set2'){
            
            var rowCount_set2 = $('#myTable2_set2 tbody tr').length;
            var ct2=rowCount_set2+1;
            markup = "<tr><td><input type='number' class='form-control' name='dr_start_set2_"+ct2+"' min='0' max='24'></td><td><input type='number' class='form-control' name='dr_end_set2_"+ct2+"' min='0' max='24'></td><td><input type='number' class='form-control' name='dr_price_set2_"+ct2+"' step='any'></td></tr>";
            tableBody = $("#myTable2_set2 tbody");
            tableBody.append(markup);
            document.getElementById("count_set2").value = ct2;
          
        }


          if(type=='dr04_int1'){
            
            var rowCount_set2 = $('#myTable1_interval1_dr tbody tr').length;
            var ct2=rowCount_set2+1;
            markup = "<tr><td><input type='number' class='form-control' name='dr_start_04int1_"+ct2+"' min='0' max='24'></td><td><input type='number' class='form-control' name='dr_end_04int1_"+ct2+"' min='0' max='24'></td><td><input type='number' class='form-control' name='dr_price_04int1_"+ct2+"' step='any'></td></tr>";
            tableBody = $("#myTable1_interval1_dr tbody");
            tableBody.append(markup);
            document.getElementById("count2").value = ct2;
          
        }

    }


    function removeFields(type){
        if(type=='en'){

              var rowCount_en = $('#myTable tbody tr').length;
            if(rowCount_en>1){
               $('#myTable tr:last').remove();
            }
            var ct_en=rowCount_en-1;
            document.getElementById("count").value = ct_en;   



            var rowCount_03 = $('#myTable1_03 tbody tr').length;
            if(rowCount_03>1){
               $('#myTable1_03 tr:last').remove();
            }
            var ct_en_03=rowCount_03-1;
            document.getElementById("count").value = ct_en_03;   

        }    
          
        if(type=='dr04_int2s1'){
            var rowCount = $('#myTable1_04int2_set1 tbody tr').length;
            if(rowCount>1){
               $('#myTable1_04int2_set1 tr:last').remove();
            }
            var ct=rowCount-1;
            document.getElementById("count_set1").value = ct;   
        }     

        if(type=='dr04_int2s2'){
            var rowCount = $('#myTable1_04int2_set2 tbody tr').length;
            if(rowCount>1){
               $('#myTable1_04int2_set2 tr:last').remove();
            }
            var ct=rowCount-1;
            document.getElementById("count_set2").value = ct;   
        }     

         if(type=='dr_set1'){
            var rowCount = $('#myTable2_set1 tbody tr').length;
            if(rowCount>1){
               $('#myTable2_set1 tr:last').remove();
            }
            var ct=rowCount-1;
            document.getElementById("count_set1").value = ct;   
        }   

        if(type=='dr_set2'){
            var rowCount = $('#myTable2_set2 tbody tr').length;
            if(rowCount>1){
               $('#myTable2_set2 tr:last').remove();
            }
            var ct=rowCount-1;
            document.getElementById("count_set2").value = ct;   
        }   


         if(type=='en04_int1'){

              var rowCount = $('#myTable1_interval1 tbody tr').length;
            if(rowCount>1){
               $('#myTable1_interval1 tr:last').remove();
            }
            var ct=rowCount-1;
            document.getElementById("count").value = ct;  

         }  


         if(type=='dr04_int1'){

              var rowCount = $('#myTable1_interval1_dr tbody tr').length;
            if(rowCount>1){
               $('#myTable1_interval1_dr tr:last').remove();
            }
            var ct=rowCount-1;
            document.getElementById("count_dr").value = ct;  

         }  

        if(type=='en04_int2'){

              var rowCount = $('#myTable1_04_int2 tbody tr').length;
            if(rowCount>1){
               $('#myTable1_04_int2 tr:last').remove();
            }
            var ct=rowCount-1;
            document.getElementById("count").value = ct;  

         }  
    }

    function genXML(){
        var gen = $('#generator').find(":selected").val();
        var url = document.getElementById('url').value;
      
        if(gen=="06CENPRI_U01"){
            
            document.getElementById("generate_xml").action = url+"masterfile/generate_xml_01";
            document.getElementById("hidden").style.display = 'none';
            document.getElementById("type_en").style.display = 'block';
            document.getElementById("type_dr").style.display = 'none';
            document.getElementById("type_dr_interval1").style.display = 'none';
            document.getElementById("type_dr_interval2").style.display = 'none';
        }
        if(gen=="06CENPRI_U02"){
            document.getElementById("generate_xml").action = url+"masterfile/generate_xml_02";
            document.getElementById("hidden").style.display = 'none';
            document.getElementById("type_en").style.display = 'block';
            document.getElementById("type_dr").style.display = 'none';
            document.getElementById("type_dr_interval1").style.display = 'none';
            document.getElementById("type_dr_interval2").style.display = 'none';
        }
        if(gen=="06CENPRI_U03"){
             document.getElementById("generate_xml").action = url+"masterfile/generate_xml_03";
            document.getElementById("hidden").style.display = 'none';
            document.getElementById("type_en").style.display = 'none';
            document.getElementById("type_dr").style.display = 'block';
            document.getElementById("type_dr_interval1").style.display = 'none';
            document.getElementById("type_dr_interval2").style.display = 'none';
        }
        if(gen=="06CENPRI_U04"){
            $("#interval").attr('required', ''); 
            document.getElementById("hidden").style.display = 'block';
            document.getElementById("type_en").style.display = 'none';
            document.getElementById("type_dr").style.display = 'none';
        }
        if(gen=="06CENPRI_U05"){
            $("#interval").attr('required', ''); 
            document.getElementById("hidden").style.display = 'block';
            document.getElementById("type_en").style.display = 'none';
            document.getElementById("type_dr").style.display = 'none';
        }
    }

    function interval_type(){
        var int = $('#interval').find(":selected").val();
         var url = document.getElementById('url').value;
        var gen = $('#generator').find(":selected").val();
     
        if(gen == '06CENPRI_U04' && int == '1'){
            document.getElementById("generate_xml").action = url+"masterfile/generate_xml_04_1";
            document.getElementById("type_en").style.display = 'none';
            document.getElementById("type_dr").style.display = 'none';
            document.getElementById("type_dr_interval1").style.display = 'block';
            document.getElementById("type_dr_interval2").style.display = 'none';
        }
        if(gen == '06CENPRI_U04' && int == '2'){
            document.getElementById("generate_xml").action = url+"masterfile/generate_xml_04_2";
             document.getElementById("type_en").style.display = 'none';
            document.getElementById("type_dr").style.display = 'none';
            document.getElementById("type_dr_interval1").style.display = 'none';
            document.getElementById("type_dr_interval2").style.display = 'block';
        }

         if(gen == '06CENPRI_U05' && int == '1'){
            document.getElementById("generate_xml").action = url+"masterfile/generate_xml_05_1";
            document.getElementById("type_en").style.display = 'none';
            document.getElementById("type_dr").style.display = 'none';
            document.getElementById("type_dr_interval1").style.display = 'block';
            document.getElementById("type_dr_interval2").style.display = 'none';
        }
        if(gen == '06CENPRI_U05' && int == '2'){
            document.getElementById("generate_xml").action = url+"masterfile/generate_xml_05_2";
             document.getElementById("type_en").style.display = 'none';
            document.getElementById("type_dr").style.display = 'none';
            document.getElementById("type_dr_interval1").style.display = 'none';
            document.getElementById("type_dr_interval2").style.display = 'block';
        }

    }
</script>