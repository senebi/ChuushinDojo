<?php
  if(!isset($subCount)){
	$subCount=substr_count(dirname($_SERVER["PHP_SELF"]), "/")-2;
	$rootDir=str_repeat("../", $subCount);
	header("location: ".$rootDir."index.php?pid=1");
    exit();
  }
  $months=["január", "február", "március", "április", "május", "június", "július", "augusztus", "szeptember", "október", "november", "december"];
?>
<p>
  Amennyiben tagja egy - a rendszer által kezelt - dojónak, kérlek, regisztrálj az alábbi űrlapon!
</p>
<p>
  Minden mezőt kötelező kitölteni!
</p>
<!-- Hiba listát megcsinálni BS stílusban! -->
<div class="container">
  <div class="row"><!-- <div class="row justify-content-md-center"> -->
  <div class="col-md-3"></div>
  <div class="col-12 col-md-6">
<form action="content/reg_process.php" method="post">
  <?php
	$defClasses="form-control form-control-sm";
  ?>
  <div class="form-group">
    <label for="reg_user">Felhasználónév: </label>
	<?php
	  $classes=$defClasses;
	  if(isset($_GET["fail"])){
		if(isset($_SESSION["reg_user"]["err_msg"])) $classes.=" is-invalid";
		else $classes.=" is-valid";
	  }
	  else{
		clearErrors();
		clearRegData();
	  }
	?>
    <input type="text" name="reg_user" class="<?php echo $classes; ?>" id="reg_user" aria-describedby="userHelp" value="<?php if(isset($_SESSION["reg_user"])) echo $_SESSION["reg_user"]["val"]; ?>" />
    <small id="userHelp" class="form-text text-muted">Legalább 5 karakter, csak ékezetmentes betűt (az elején kötelező), pontot (.), alulvonást (_) és számot tartalmazhat.</small>
	<?php
	  if(isset($_SESSION["reg_user"]["err_msg"])){
		?>
		<div class="invalid-feedback">
		<?php
		echo $_SESSION["reg_user"]["err_msg"];
		?>
		</div>
		<?php
	  }
	?>
  </div>
  <?php
	$classes=$defClasses;
	if(isset($_GET["fail"])){
	  if(isset($_SESSION["fullName"]["err_msg"])) $classes.=" is-invalid";
	  else $classes.=" is-valid";
	}
  ?>
  <div class="form-group">
    <label for="fullName">Teljes név: </label>
    <input type="text" name="fullName" class="<?php echo $classes; ?>" id="fullName" value="<?php if(isset($_SESSION["fullName"])) echo $_SESSION["fullName"]["val"]; ?>" />
	<?php
	  if(isset($_SESSION["fullName"]["err_msg"])){
		?>
		<div class="invalid-feedback">
		<?php
		echo $_SESSION["fullName"]["err_msg"];
		?>
		</div>
		<?php
	  }
	?>
  </div>
  <?php
	$classes=$defClasses;
	if(isset($_GET["fail"])){
	  if(isset($_SESSION["email"]["err_msg"])) $classes.=" is-invalid";
	  else $classes.=" is-valid";
	}
  ?>
  <div class="form-group">
    <label for="email">E-mail cím: </label>
    <input type="email" name="email" class="<?php echo $classes; ?>" id="email" aria-describedby="emailHelp" value="<?php if(isset($_SESSION["email"])) echo $_SESSION["email"]["val"]; ?>" />
    <small id="emailHelp" class="form-text text-muted">Valódi e-mail címnek kell lennie, amit használsz.</small>
	<?php
	  if(isset($_SESSION["email"]["err_msg"])){
		?>
		<div class="invalid-feedback">
		<?php
		echo $_SESSION["email"]["err_msg"];
		?>
		</div>
		<?php
	  }
	?>
  </div>
  <?php
	$classes=$defClasses;
	if(isset($_GET["fail"])){
	  if(isset($_SESSION["birthYear"]["err_msg"])) $classes.=" is-invalid";
	  else $classes.=" is-valid";
	}
  ?>
  <div class="form-group">
    <label for="birthYear">Születési dátum: </label>
	<div class="form-row">
	  <div class="col">
		<input type="number" name="birthYear" class="<?php echo $classes; ?>" id="birthYear" minlength="4" maxlength="4" min="1900" max="<?php echo date("Y"); ?>" placeholder="Év" value="<?php if(isset($_SESSION["birthYear"])) echo $_SESSION["birthYear"]["val"]; ?>" />
	  </div>
	  <div class="col">
		<?php
		  $classes=$defClasses;
		  if(isset($_GET["fail"])){
			if(isset($_SESSION["birthMonth"]["err_msg"])) $classes.=" is-invalid";
			else $classes.=" is-valid";
		  }
		?>
		<select name="birthMonth" id="birthMonth" class="<?php echo $classes; ?>">
          <option value=0>Hónap</option>
          <?php
          foreach($months as $i => $val){
            echo "<option value=".($i+1);
            if(isset($_SESSION["birthMonth"])){
              if($_SESSION["birthMonth"]["val"]==($i+1)) echo " selected";
            }
            echo ">".$val."</option>";
          }
          ?>
        </select>
	  </div>
	  <div class="col">
		<?php
		  $classes=$defClasses;
		  if(isset($_GET["fail"])){
			if(isset($_SESSION["birthDay"]["err_msg"])) $classes.=" is-invalid";
			else $classes.=" is-valid";
		  }
		?>
		<select name="birthDay" id="birthDay" class="<?php echo $classes; ?>">
          <option value=0>Nap</otion>
          <?php
          for($i=1; $i<=31; $i++){
            echo "<option value=".$i;
            if(isset($_SESSION["birthDay"])){
              if($_SESSION["birthDay"]["val"]==$i) echo " selected";
            }
            echo ">".$i."</option>";
          }
          ?>
        </select>
	  </div>
	</div>
  <?php
	if(isset($_SESSION["birthDate"]["err_msg"])){
	  ?>
	  <div class="invalid-feedback">
	  <?php
	  echo $_SESSION["birthDate"]["err_msg"];
	  ?>
	  </div>
	  <?php
	}
  ?>
  </div>
  <?php
	$classes=$defClasses;
	if(isset($_GET["fail"])){
	  if(isset($_SESSION["dojo"]["err_msg"])) $classes.=" is-invalid";
	  else $classes.=" is-valid";
	}
  ?>
  <div class="form-group">
    <label for="dojo">Dojo: </label>
    <select name="dojo" id="dojo" class="<?php echo $classes; ?>">
	  <?php
		$dojo=new Dojo();
		$list=$dojo->getDojos("option");
		if($list!="") echo $list;
		else{
	  ?>
		  <option value=1>Chuushin dojo (Cegléd)</option>
	  <?php
		}
	  ?>
	</select>
	<?php
	  if(isset($_SESSION["dojo"]["err_msg"])){
		?>
		<div class="invalid-feedback">
		<?php
		echo $_SESSION["dojo"]["err_msg"];
		?>
		</div>
		<?php
	  }
	?>
  </div>
  <?php
	$classes=$defClasses;
	if(isset($_GET["fail"])){
	  if(isset($_SESSION["rank"]["err_msg"])) $classes.=" is-invalid";
	  else $classes.=" is-valid";
	}
  ?>
  <div class="form-group">
    <label for="rank">Tagság jellege: </label>
    <select name="rank" id="rank" class="<?php echo $classes; ?>">
	  <option value="tag"<?php if(isset($_SESSION["rank"]["val"]) && $_SESSION["rank"]["val"]=="tag") echo " selected"; ?>>tag</option>
      <option value="edző"<?php if(isset($_SESSION["rank"]["val"]) && $_SESSION["rank"]["val"]=="edző") echo " selected"; ?>>edző</option>
	</select>
	<?php
	  if(isset($_SESSION["rank"]["err_msg"])){
		?>
		<div class="invalid-feedback">
		<?php
		echo $_SESSION["rank"]["err_msg"];
		?>
		</div>
		<?php
	  }
	?>
  </div>
  <?php
	$classes=$defClasses;
	if(isset($_GET["fail"])){
	  if(isset($_SESSION["reg_pass"]["err_msg"])) $classes.=" is-invalid";
	  else $classes.=" is-valid";
	}
  ?>
  <div class="form-group">
    <label for="reg_pass">Jelszó: </label>
    <input type="password" name="reg_pass" class="<?php echo $classes; ?>" id="reg_pass" aria-describedby="passHelp" />
    <small id="passHelp" class="form-text text-muted">A jelszónak legalább 8 karakter hosszúnak kell lennie (ebből min. 2 számjegy), nem tartalmazhatja a születési dátumot és a felhasználónevet!</small>
	<?php
	  if(isset($_SESSION["reg_pass"]["err_msg"])){
		?>
		<div class="invalid-feedback">
		<?php
		echo $_SESSION["reg_pass"]["err_msg"];
		?>
		</div>
		<?php
	  }
	?>
  </div>
  <?php
	$classes=$defClasses;
	if(isset($_GET["fail"])){
	  if(isset($_SESSION["passAgain"]["err_msg"])) $classes.=" is-invalid";
	  else $classes.=" is-valid";
	}
  ?>
  <div class="form-group">
    <label for="passAgain">Jelszó újra: </label>
    <input type="password" name="passAgain" class="<?php echo $classes; ?>" id="passAgain" />
	<?php
	  if(isset($_SESSION["passAgain"]["err_msg"])){
		?>
		<div class="invalid-feedback">
		<?php
		echo $_SESSION["passAgain"]["err_msg"];
		?>
		</div>
		<?php
	  }
	?>
  </div>
  <div class="form-group justify-content-center">
	<button type="submit" name="submit" class="btn btn-primary">Regisztráció</button>
  </div>
  <input type="hidden" name="reg_id" value="<?php echo $_GET["pid"]; ?>" />

  <p>
	<a href="javascript:history.back()">Vissza</a>
  </p>
</form>
  </div>
  <div class="col-md-3"></div>
</div>
</div>
<?php
  clearErrors();
?>