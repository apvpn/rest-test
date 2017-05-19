<?php 

include_once $_SERVER["DOCUMENT_ROOT"].'/ashish_rest/init.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Signin Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="assets/css/signin.css" rel="stylesheet">
  </head>

  <body>

    <div class="container">

      <form class="form-signin" id="frm-login" method="post">
      <div id="error-msg" class="alert alert-danger" style="display:none;" role="alert"></div>
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" name="username" id="inputEmail" class="form-control" placeholder="Email address" required autofocus />
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required />
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> Remember me
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" id="submit" type="submit">Sign in</button>
      </form>

    </div> <!-- /container -->

  </body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
var SITE_URL = '<?php echo SITE_URL;?>';
    $(document).ready(function(){
      /*  var r=$("form").serialize();
        alert(r);*/
        $(document).on("submit","#frm-login",function(event){
             event.preventDefault();

             var form = $(this);
             var data = {};
             form.serializeArray().map(function(x){data[x.name] = x.value;});
             $.ajax({
                url: SITE_URL +'/api/ajax.php?action=login',
                type: 'POST',
                contentType: "application/json",
                dataType : 'json', // data type
                data : JSON.stringify(data), // post data || get data
                success : function(result) {
                //console.log(result.status);
                    if(typeof result.success != 'undefined' && result.success == "Y")
                    window.location.href = "http://localhost/ashish_rest/dashboard.php";
                },
                error: function(xhr, resp, text) {
                    console.log(xhr, resp, text);
                     $("#error-msg").html(text);
                        $("#error-msg").show();
                }
            });

        });
        
    });
</script>


