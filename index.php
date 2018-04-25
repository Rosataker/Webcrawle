<?php 
session_start();
require 'Webcrawler.class.php';


	$search_str = ($_POST['search_str']) ? $_POST['search_str'] : $_GET['search_str'];
	$OriginalHtml = $WebcrawlerSearch->Search($search_str);	

	$Dat['search_str']=$search_str;
	$Dat['OriginalHtml']=$OriginalHtml;
	$ShowData = $WebcrawlerProcess->Process($Dat);

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    

    <title>Webcrawler</title>	
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">
  </head>

  <body>
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Webcrawler</a>
      <form class="w-100" method="POST" action="">
      	<input class="form-control form-control-dark w-100" name="search_str" value="<?=$search_str?>" type="text" placeholder="Search" aria-label="Search">
      </form>
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
         
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
    
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link active" href="#">
                  <span data-feather="home"></span>
                  瀏覽歷程
                </a>
              </li>
<?php 

if($_SESSION["List"]){
	foreach ($_SESSION["List"] as $key => $value) {
               ?>
              <li class="nav-item">
                <a class="nav-link" href="?search_str=<?=$key?>">
                     <?=$_SESSION["List"][$key]["Company"]?>
                </a>
              </li>
<?php
	}
}
?>
            </ul>
          </div>
        </nav>
<?php 

if($search_str)
{

	if(is_array($ShowData)){
?>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">


          <h2><?=$ShowData['Company']?> -- <?=$ShowData['Datatime']?></h2>
          <div class="table-responsive">
            <table class="table table-striped table-sm">
              <thead>
                <tr>
<?php 
				foreach ($ShowData['Title'] as $key => $value) 
				{
                	  print "<th>{$value}</th>";
				}
?>
                </tr>
              </thead>
              <tbody>
                <tr>
<?php 
				foreach ($ShowData['Value'] as $key => $value) 
				{
                	  print "<td>{$value}</td>";
				}
?>
                </tr>
              
              </tbody>
            </table>
          </div>
        </main>
<?php
	}else{
?>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
          <h2>查無此股號</h2>
        </main>
<?php		
	}
}
?>
      </div>
    </div>

  </body>
</html>
