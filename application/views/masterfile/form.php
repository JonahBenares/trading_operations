<style>
#hidden {
    display: none;
}
</style>
<div class="page-wrapper" style="margin: 0px;"> 
    <div class="container-fluid" style="margin-top:50px">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="card card-new">
                    <div class="card-body card-margin" >
                          <form method='POST' name='generate_xml' id='generate_xml' target='_blank'  accept-charset="UTF-8" >
                        <div class="row">
                           
                            <div class="col-12">

                                <h2><span class="mdi mdi-file-document"></span> FORM</h2>
                                  <div class="form-group">    
                                    <p class="margin-b0">Submission Date:</p>
                                    <input type="date" class="form-control" name="submission_date" required>                                                              
                                </div> 
                                <div class="form-group">    
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

                                <div class="form-group" id='hidden'>    
                                    <p class="margin-b0">Intervals:</p>
                                    <select class="form-control" name='interval' id='interval' onchange="interval_type()" required >
                                        <option value='' selected>Select Interval</option>
                                        <option value='1'>1-10 & 21-24</option>
                                        <option value='2'>1-24</option>
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
                                    <p class="margin-b0">Default Price:</p>
                                    <input type="number" class="form-control" name="default_price">
                                </div> 
                                <br>
                                <h5 class="margin-b0">Price Change:</h5>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <table width="100%" id='myTable' >
                                            <thead>
                                            <tr>
                                                <td width="29%"><p>Start Hour</p></td>
                                                <td width="29%"><p>End Hour</p></td>
                                                <td width="29%"><p>Price</p></td>
                                                <td width="13%" align="center"><p><i class="mdi mdi-view-sequential"></i></p></td>
                                            </tr>
                                        </thead>
                                             <tbody>
                                            <tr>
                                                <td><input type="number" class="form-control" name="start1" min='0' max='23'></td>
                                                <td><input type="number" class="form-control" name="end1" min='0' max='23'></td>
                                                <td><input type="number" class="form-control" name="price1"></td>
                                                <td align="center">
                                                    <a href="#" class="btn btn-primary btn-xs"  onclick="addFields()"><i class="mdi mdi-plus"></i></a>
                                                    <a href="#" class="btn btn-danger btn-xs" onclick="removeFields()"><i class="mdi mdi-window-close"></i></a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>  
                                  <input type='hidden' id='count' name='count'>
                                  <input type='hidden' id='url' name='url' value="<?php echo base_url(); ?>">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <br>
                                        <input type="submit" class="btn btn-md btn-info btn-block" value="Generate XML" name="generateXML">
                                    </div>
                                </div>     
                                  </form>                     
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
         <script type='text/javascript'>
        function addFields(){
                var rowCount = $('#myTable tbody tr').length;
                var ct=rowCount+1;
                markup = "<tr><td><input type='number' class='form-control' name='start"+ct+"' min='0' max='23'></td><td><input type='number' class='form-control' name='end"+ct+"' min='0' max='23'></td><td><input type='number' class='form-control' name='price"+ct+"'></td></tr>";
                tableBody = $("table tbody");
                tableBody.append(markup);
                document.getElementById("count").value = ct;
              
            
        }

         function removeFields(){
            var rowCount = $('#myTable tbody tr').length;
           
            if(rowCount>1){
               $('#myTable tr:last').remove();
            }
            var ct=rowCount-1;
            document.getElementById("count").value = ct;
              
        }

        function genXML(){
              var gen = $('#generator').find(":selected").val();
              var url = document.getElementById('url').value;
            alert(gen);
              if(gen=="06CENPRI_U01"){
                alert(url+"masterfile/generate_xml_01");
                document.getElementById("generate_xml").action = url+"masterfile/generate_xml_01";
                document.getElementById("hidden").style.display = 'none';
              }
              if(gen=="06CENPRI_U02"){
                document.getElementById("generate_xml").action = url+"masterfile/generate_xml_02";
                document.getElementById("hidden").style.display = 'none';
              }
              if(gen=="06CENPRI_U03"){
                document.getElementById("hidden").style.display = 'block';
              }
              if(gen=="06CENPRI_U04"){
                document.getElementById("generate_xml").action = url+"masterfile/generate_xml_04";
                document.getElementById("hidden").style.display = 'block';
              }
              if(gen=="06CENPRI_U04"){
                document.getElementById("generate_xml").action = url+"masterfile/generate_xml_05";
                document.getElementById("hidden").style.display = 'block';
              }

        }

        function interval_type(){
            var int = $('#interval').find(":selected").val();
            var gen = $('#generator').find(":selected").val();
            
            if(gen == '06CENPRI_U03'){
                document.getElementById("generate_xml").action = url+"masterfile/generate_xml_03";
            }
            if(gen == '06CENPRI_U04' && int == '1'){
                document.getElementById("generate_xml").action = url+"masterfile/generate_xml_04_1";
            }
            if(gen == '06CENPRI_U04' && int == '2'){
                document.getElementById("generate_xml").action = url+"masterfile/generate_xml_04_2";
            }
        }
    </script>