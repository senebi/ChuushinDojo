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
<form action="content/reg_process.php" method="post">
  <table>
    <tr>
      <td><label for="reg_user">Felhasználónév: </label></td>
      <td>
        <input type="text" name="reg_user" value="<?php if(isset($_SESSION["reg_user"])) echo $_SESSION["reg_user"]["val"]; ?>" />
        <div>Legalább 5 karakter, csak ékezetmentes kisbetűt (az elején), pontot (.), alulvonást (_) és számot tartalmazhat.</div>
        <div class="error"><?php if(isset($_SESSION["reg_user"])) echo $_SESSION["reg_user"]["err_msg"]; ?></div>
      </td>
    </tr>
    
    <tr>
      <td><label for="fullName">Teljes név: </label></td>
      <td>
        <input type="text" name="fullName" value="<?php if(isset($_SESSION["fullName"])) echo $_SESSION["fullName"]["val"]; ?>" />
        <div class="error"><?php if(isset($_SESSION["fullName"])) echo $_SESSION["fullName"]["err_msg"]; ?></div>
      </td>
    </tr>
    
    <tr>
      <td><label for="email">E-mail cím: </label></td>
      <td>
        <input type="email" name="email" value="<?php if(isset($_SESSION["email"])) echo $_SESSION["email"]["val"]; ?>" />
        <div>Valódi e-mail címnek kell lennie, amit használsz.</div>
        <div class="error"><?php if(isset($_SESSION["email"])) echo $_SESSION["email"]["err_msg"]; ?></div>
      </td>
    </tr>
    
    <tr>
      <td><label for="birthYear">Születési dátum: </label></td>
      <td>
        <input type="number" name="birthYear" minlength="4" maxlength="4" min="1900" max="<?php echo date("Y"); ?>" placeholder="Év" value="<?php if(isset($_SESSION["birthYear"])) echo $_SESSION["birthYear"]["val"]; ?>" />
        <select name="birthMonth">
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
        <select name="birthDay">
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
        <div class="error"><?php if(isset($_SESSION["birthDate"])) echo $_SESSION["birthDate"]["err_msg"]; ?></div>
      </td>
    </tr>
    
    <tr>
      <td><label for="dojo">Dojo: </label></td>
      <td>
        <select name="dojo">
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
        <div class="error"><?php if(isset($_SESSION["dojo"])) echo $_SESSION["dojo"]["err_msg"]; ?></div>
      </td>
    </tr>
    
    <tr>
      <td><label for="rank">Tagság jellege: </label></td>
      <td>
        <select name="rank">
          <option value="tag">tag</option>
          <option value="edző">edző</option>
        </select>
        <div class="error"><?php if(isset($_SESSION["rank"])) echo $_SESSION["rank"]["err_msg"]; ?></div>
      </td>
    </tr>
    
    <tr>
      <td><label for="reg_pass">Jelszó: </label></td>
      <td>
        <input type="password" name="reg_pass" />
        <div>A jelszónak legalább 8 karakter hosszúnak kell lennie (ebből min. 2 számjegy), nem tartalmazhatja a születési dátumot és a felhasználónevet!</div>
        <div class="error"><?php if(isset($_SESSION["reg_pass"])) echo $_SESSION["reg_pass"]["err_msg"]; ?></div>
      </td>
    </tr>
    
    <tr>
      <td><label for="passAgain">Jelszó újra: </label></td>
      <td>
        <input type="password" name="passAgain" />
        <div class="error"><?php if(isset($_SESSION["passAgain"])) echo $_SESSION["passAgain"]["err_msg"]; ?></div>
      </td>
    </tr>
    
    <!--<tr>
      <td><a href="javascript:history.back()">Vissza</a></td>
      <td><input type="submit" name="submit" value="Regisztráció" /></td>
    </tr>-->
  </table>
  <p>
    <input type="submit" name="submit" value="Regisztráció" />
	<input type="hidden" name="reg_id" value="<?php echo $_GET["pid"]; ?>" />
  </p>
  <p>
	<a href="javascript:history.back()">Vissza</a>
  </p>
</form>
<?php
  clearErrors();
?>