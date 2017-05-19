<?php 

include_once $_SERVER["DOCUMENT_ROOT"].'/ashish_rest/init.php';

if(empty($_SESSION['logged_in_user']))
    header ("Location: login.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Dashboard Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/dashboard.css" rel="stylesheet">
    <style>
    .page_content{
        display: none;
    }
    </style>
  </head>

  <body>
    
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Profile</a></li>
            <li><a href="#">Help</a></li>
          </ul>
          <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search...">
          </form>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li id="li_dashboard" class="active"><a href="#">Dashboard <span class="sr-only">(current)</span></a></li>
            <li id="li_work"><a  href="#">Works</a></li>
            <li id="li_logout"><a  href="http://localhost/ashish_rest/api/ajax.php?action=logout">Logout</a></li>
            
          </ul>          
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <div id="content_dashboard" class="page_content">  
          <h1 class="page-header">Dashboard</h1>
          <div class="row placeholders">
            <div class="col-xs-6 col-sm-3 placeholder">
              <img src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" width="200" height="200" class="img-responsive" alt="Generic placeholder thumbnail">
              <h4>Label</h4>
              <span class="text-muted">Something else</span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <img src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" width="200" height="200" class="img-responsive" alt="Generic placeholder thumbnail">
              <h4>Label</h4>
              <span class="text-muted">Something else</span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <img src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" width="200" height="200" class="img-responsive" alt="Generic placeholder thumbnail">
              <h4>Label</h4>
              <span class="text-muted">Something else</span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <img src="data:image/gif;base64,R0lGODlhAQABAIAAAHd3dwAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==" width="200" height="200" class="img-responsive" alt="Generic placeholder thumbnail">
              <h4>Label</h4>
              <span class="text-muted">Something else</span>
            </div>
          </div>
          </div>
          <div id="content_work" class="page_content">  
          <h1 class="page-header">Works</h1>          

          <!-- <h2 class="sub-header">Section title</h2> -->
          <div class="table-responsive">
            <table id="tbl_work" class="table table-striped">
              <thead>
                <tr>
                  <th>Work Id</th>
                  <th>Work Name</th>
                  <th>Work Score</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1,001</td>
                  <td>Lorem</td>
                  <td>ipsum</td>
                  <td>dolor</td>
                  <td>sit</td>
                </tr>
              </tbody>
            </table>
          </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery-1.10.2.min.js" type="text/javascript" ></script>
    
    <script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
    
  </body>
</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript">
    var SITE_URL = '<?php echo SITE_URL;?>';
    $(document).ready(function(){
 });
      /*  var r=$("form").serialize();
        alert(r);*/
        $(document).on("click","#li_dashboard",function(event){
            $(".page_content").hide();
            $("#content_dashboard").show();
            $(".nav-sidebar li").removeClass("active");   
             $(this).addClass("active");
        });

        $(document).on("click","#li_work",function(event){
             $(".nav-sidebar li").removeClass("active");   
             $(this).addClass("active");
             $(".page_content").hide();   
             $.ajax({
                url: SITE_URL +'/api/ajax.php?action=get_user_works',
                type: 'POST',
                contentType: "application/json",
                dataType : 'json', // data type                
                success : function(res) {
                //console.log(result.status);
                    if(typeof res.success != 'undefined' && res.success == "Y")
                    {
                        $("#tbl_work tbody").html("");
                        $(res.data).each(function(k,v){
                            console.log(v);
                            var row = "<tr>";
                            row += "<td>"+v.work_id+"</td>";
                            row += "<td>"+v.work_name+"</td>";
                            row += "<td>"+v.work_score+"</td>";
                            row += "</tr>";
                            $("#tbl_work tbody").append(row);
                        });
                        $("#content_work").show();
                    }
                },
                error: function(xhr, resp, text) {
                    console.log(xhr, resp, text);
                     $("#error-msg").html(text);
                        $("#error-msg").show();
                }
            });

        });
        
   
</script>
