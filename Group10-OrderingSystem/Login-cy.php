<!DOCTYPE html>
<html lang="cy">
<head>
  <title>Mewngofnodi tudalen glanio</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="style.css">
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
  <script src='http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js'></script>
</head>
<body>
  <div class="wrapper">
    <form class="form-signin">
      <img id="profile-img" class="profile-img-card" src="images/Bangor_Logo_B1.png" />
      <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
        <input id="email" type="text" class="form-control" name="email" placeholder="e-bost" >
      </div>
      <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
        <input id="password" type="password" class="form-control" name="password" placeholder="cyfrinair">
      </div>
      <br>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Mewngofnodi</button>
      <a href="Login.php">
        <img class="flag" src="images/ukFlag.png" alt="welsh flag" id="ukFlag"> 
      </a>   
    </form>
    
  </div>
</body>
</html>