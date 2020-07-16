<?php
  if(!isset($subCount)){
	$subCount=substr_count(dirname($_SERVER["PHP_SELF"]), "/")-2;
	$rootDir=str_repeat("../", $subCount);
	header("location: ".$rootDir."index.php?pid=1");
    exit();
  }
?>
  <footer>
    <p id="copyRightText">Some Copyright text.</p>
  </footer>
  
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
  <script src = "<?php echo $rootDir; ?>includes/js/slideShow.js"> </script>
  <script>
  
    //window.onload = changePic;
  </script>
  </body>
</html>