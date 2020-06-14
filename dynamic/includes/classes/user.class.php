<?php
  class User{
    private $id;
    private $permissions;
    /* Defining permissions:
     * first char: Is visible to guests? (0/1)
     * second char: Is visible to members? (0/1)
     * third char: Is visible to trainers? (0/1)
     * last char: Is visible to admins? (0/1)
     * Example of guest user permission mask: 1___
     */
    
    public function __construct(){
      //set default values
      global $conn;
      $this->id = 0;
      $this->permissions = "1___";
      $permTable=array("tag" => "_1__", "edző" => "__1_", "admin" => "___1");
      
      if(isset($_SESSION["userid"], $_SESSION["userperm"])){
        //user already logged in
        $this->id = $_SESSION["userid"];
        $this->permissions = $_SESSION["userperm"];
      }
      else{
        //user is trying to log in, if the credentials match, let him/her in
        if(isset($_POST["user"]) and isset($_POST["pass"])){
          $user = sanitize($_POST["user"]);
          $pass = sanitize($_POST["pass"]);
          if($user!="" && $pass!=""){
            $sql = "select * from ".USERS." where felhasznalonev='$user'";
            $res = $conn->query($sql) or die($conn->error." on line <b>".__LINE__."</b>");
            
            //if the user exists
            if($res->num_rows){
              $data=$res->fetch_assoc();
              $salt=$data["so"];
              $dbPass=$data["jelszo"];
              
              //if the user profile is active
              if($data["aktiv"]=="1"){
                //if the passwords match
                if(crypt($pass,$salt)==$dbPass){
                  $this->id = $data["id"];
                  $this->permissions = $permTable[$data["jog"]];
                  $_SESSION["userid"] = $this->id;
                  $_SESSION["userperm"] = $this->permissions;
                  setMsg("<div class='success'>Sikeres bejelentkezés.</div>");
                }
                else setMsg("<div class='error'>Helytelen felhasználónév vagy jelszó!</div>");
              }
              else setMsg("<div class='error'>A felhasználó (még) inaktív! Adminisztrátori jóváhagyás szükséges.</div>");
            }
            else setMsg("<div class='error'>Helytelen felhasználónév vagy jelszó!</div>");
          }
          else setMsg("<div class='error'>A bejelentkezési adatok kitöltése kötelező!</div>");
        }
      }
    }
  
    public function isLoggedIn(){
      if ($this->id == 0)
        return false;
      else
        return true;
    }
    
    public function getUserId(){
      return $this->id;
    }
    
    public function getPermissions(){
      return $this->permissions;
    }
    
    public function getName(){
      global $conn;
      
      if($this->id == 0){
        $name = "";
      }
      else{
        $sql = "select nev from ".USERS." where id=".$this->id;
        $res = $conn->query($sql) or die($conn->error." on line <b>".__LINE__."</b>");
        if($res->num_rows){
          $name=$res->fetch_assoc()["nev"];
        }
        else $name="Nincs név.";
      }
      return $name;
    }
    
    public function getData(){
      global $conn;
      
      if($this->id == 0){
        return false;
      }
      else{
        $sql = "select u.*, d.nev as dojoNev, d.varos from ".USERS." as u inner join ".MEMBERSHIPS." as m on ".
        "u.id=m.tag_id inner join ".DOJOS." as d on ".
        "m.dojo_id=d.id where u.id=".$this->id;
        $res = $conn->query($sql) or die($conn->error." on line <b>".__LINE__."</b>");
        if($res->num_rows){
          $data=$res->fetch_assoc();
        }
        else return false;
      }
      return $data;
    }
    
    public function validateRegister($data){
      global $conn;
      $modData=$data;
      $success=true;
      //exclude reg_id and submit values from validation
      unset($modData["reg_id"]);
      unset($modData["submit"]);
      
      //loop through the given data
      foreach($modData as $attr_name => $val){
        $msg="";
        $val=sanitize($val);
        //defining error cases
        if($val==""){
          $success=false;
          $msg="A mező kitöltése kötelező!";
        }
        else{
          if($attr_name=="reg_user"){
            //in case of wrong format or length
            if(!preg_match("/^[a-z]+[0-9]*(\.|_)*[a-z0-9]*(\.|_)*[a-z0-9]*$/", $val) || strlen($val)<5){
              $success=false;
              $msg="A felhasználónév formátuma nem megfelelő!";
            }
            else{
              $res=$conn->query("select * from ".USERS." where felhasznalonev='$val'") or die($conn->error." on line <b>".__LINE__."</b>");
              //if the chosen user name is taken
              if($res->num_rows){
                $success=false;
                $msg="Ez a felhasználónév már létezik! Válassz másikat!";
              }
            }
          }
          else if($attr_name=="fullName"){
            //check if full name contains a space
            //if not, it's in wrong format
            if(strpos($val," ")===false){
              $success=false;
              $msg="Hiányzó vezetéknév vagy keresztnév!";
            }
            else{
              $tmp=explode(" ",mb_strtolower($val));
              $tmp[0][0]=mb_strtoupper($tmp[0][0]);
              $tmp[1][0]=mb_strtoupper($tmp[1][0]);
              $modData[$attr_name]=implode(" ",$tmp);
              $val=implode(" ",$tmp);
            }
          }
          else if($attr_name=="email"){
            //check e-mail format
            if(!filter_var($val, FILTER_VALIDATE_EMAIL)){
              $success=false;
              $msg="Az e-mail cím formátuma nem megfelelő!";
            }
          }
          else if($attr_name=="birthYear"){
            //check birth year format and value (range between 1900 and the current year)
            if(strlen($val)!=4 || !is_numeric($val) || $val>date("Y") || $val<1900){
              $success=false;
              $msg="error";
            }
          }
          else if($attr_name=="birthMonth"){
            //check if the birth month was selected
            if($val==0){
              $success=false;
              $msg="error";
            }
          }
          else if($attr_name=="birthDay"){
            //check if the birth day was selected
            if($val==0){
              $success=false;
              $msg="error";
            }
          }
          else if($attr_name=="reg_pass"){
            //check for the password containing birth date
            $dotFormat=strpos($val, $modData["birthYear"].".".$modData["birthMonth"].".".$modData["birthDay"])!==false;
            $slashFormat=strpos($val, $modData["birthYear"]."/".$modData["birthMonth"]."/".$modData["birthDay"])!==false;
            $dashFormat=strpos($val, $modData["birthYear"]."-".$modData["birthMonth"]."-".$modData["birthDay"])!==false;
            $noFormat=strpos($val, $modData["birthYear"].$modData["birthMonth"].$modData["birthDay"])!==false;
            
            //in case of wrong format
            if(!preg_match("/^.*(?=.*\d).*(?=.*\d).{8,}$/", $val)){
              $success=false;
              $msg="Rossz a jelszó hossza vagy formátuma!";
            }
            else if($dotFormat || $slashFormat || $dashFormat || $noFormat){
              //if password contains the birth date in any of the formats mentioned above
              $success=false;
              $msg="A jelszó nem tartalmazhatja a születési dátumot!";
            }
            else if(strpos($val, $modData["reg_user"])!==false){
              //if password contains the user name
              $success=false;
              $msg="A jelszó nem tartalmazhatja a felhasználónevet!";
            }
          }
          else if($attr_name=="passAgain"){
            //check if the two passwords match
            if($val!=$modData["reg_pass"]){
              $success=false;
              $msg="A jelszavak nem egyeznek!";
            }
          }
        }
        $_SESSION[$attr_name]=array("val" => $val, "err_msg" => "<div class='error'>".$msg."</div>");
      }
      
      $birthDate=strtotime($modData["birthYear"]."-".$modData["birthMonth"]."-".$modData["birthDay"]);
      $birthParts=!($_SESSION["birthYear"]["err_msg"]=="error" || $_SESSION["birthMonth"]["err_msg"]=="error" || $_SESSION["birthDay"]["err_msg"]=="error" || $birthDate>strtotime(date("Y-m-d")));
      $msg="";
      //if error happened to any of the birth date parts or the date is greater than the current date
      if(!$birthParts)
        $msg="A születési dátum formátuma vagy értéke nem megfelelő!";
      $_SESSION["birthDate"]=array("val" => $modData["birthYear"]."-".$modData["birthMonth"]."-".$modData["birthDay"], "err_msg" => $msg);
      
      $modData["birthDate"]=$modData["birthYear"]."-".$modData["birthMonth"]."-".$modData["birthDay"];
      unset($modData["birthYear"]);
      unset($modData["birthMonth"]);
      unset($modData["birthDay"]);
    
      if($success){
        foreach($modData as $attr => $val){
          if(isset($_SESSION[$attr])) unset($_SESSION[$attr]);
        }
        return $modData;
      }
      else return false;
    }
    
    public function register($data){
      global $conn;
      $user=$data["reg_user"];
      $salt=time();
      $pass=crypt($data["reg_pass"],$salt);
      $name=$data["fullName"];
      $email=$data["email"];
      $birthDate=$data["birthDate"];
      $dojoId=$data["dojo"];

      $sql = "insert into ".USERS." (felhasznalonev, jelszo, so, nev, email, szuletesi_datum, reg_datum)
      values ('$user','$pass',$salt,'$name','$email','$birthDate','".date("Y-m-d")."')";
      $res = $conn->query($sql) or die($conn->error." on line <b>".__LINE__."</b>");
      $userId=$conn->insert_id;
      
      $membershipSql="insert into ".MEMBERSHIPS." (dojo_id, tag_id) values (".$dojoId.",".$userId.")";
      $res2 = $conn->query($membershipSql) or die($conn->error." on line <b>".__LINE__."</b>");
      if($res && $res2)
        $_SESSION["reg_success"]=true;
    }
    
    public function notifyByEmail($data){
      global $conn;
      $ok=true;
      
      //get every admins and trainers who are connected to the same dojo as the new user
      $sql = "select * from ".USERS." inner join ".MEMBERSHIPS." on
      ".USERS.".id=".MEMBERSHIPS.".tag_id where jog='admin' or jog='edző' and dojo_id=".$data["dojo"];
      $res = $conn->query($sql) or die($conn->error." on line <b>".__LINE__."</b>");
      
      if($res->num_rows){
        $subject="Új regisztrációs igény a Dojo kezelőben";
        $headers="From: dojokezelo <sample@dojokezelo.hu>\r\n";
        $headers.="Reply-To: sample@dojokezelo.hu\r\n";
        $headers.="Content-type: text/html; charset=utf-8\r\n";
        
        while($row=$res->fetch_assoc()){
          $to=$row["email"];
          $msg="<p>Kedves <b>".$row["felhasznalonev"]."</b>!</p>";
          $msg.="<p>".$data["fullName"]." (".$data["reg_user"].") szeretne regisztrálni a Dojo kezelőbe.<br />
          Kérlek, jelentkezz be és hagyd jóvá a \"Tagok kezelése\" menüpontban, ha hiteles a profilja.</p>";
          $msg.="<p><a href='localhost/chuushindojo/dynamic/index.php?pid=3'>Dojo kezelő honlap</a></p>";
          $msg.="<p>Üdvözlettel:<br />Dojo kezelő</p>";
          
          if(!mail($to, "=?utf-8?B?".base64_encode($subject)."?=", $msg, $headers)){
            $ok=false;
            setMsg("<div class='error'>Legalább 1 illetékes (e-mailes) értesítése sikertelen a regisztrációs szándékról!</div>");
          }
        }
      }
      else{
        setMsg("<div class='error'>Nincs felhasználó, aki aktiválhatná a fiókod!</div>");
        $ok=false;
      }
      
      return $ok;
    }
    
    public function getMembers($active=false){
      global $conn;
      $sql="select * from ".USERS." where aktiv=";
      if($active) $sql.="1";
      else $sql.="0";
      
      $output="";
      $res=$conn->query($sql) or die($conn->error." on line <b>".__LINE__."</b>");
      if($res->num_rows){
        $action="content/";
        $action.=($active) ? "edit_members.php" : "activate_members.php";
        $output.='<form method="post" action="'.$action.'">';
        while($row=$res->fetch_assoc()){
          $output.="<div>";
          $output.="<input type='checkbox' checked='checked' name='";
          if(!$active) $output.="activateUser[]";
          else $output.="editUser[]";
          $output.="' value='".$row["id"]."' />&nbsp;";
          $output.="<input type='hidden' name='names[]' value='".$row["nev"]."' />";
          $output.=$row["nev"]." (".$row["felhasznalonev"]."), regisztrált: ".$row["reg_datum"].", rang:&nbsp;";
          if(!$active){
            $output.="<select name='rank[]'>";
            $output.="<option value='tag'";
            $output.=($row["jog"]=="tag") ? " selected='selected'" : "";
            $output.=">tag</option>";
            $output.="<option value='edző'";
            $output.=($row["jog"]=="edző") ? " selected='selected'" : "";
            $output.=">edző</option>";
            $output.="</select>";
          }
          else $output.=$row["jog"];
          $output.="</div>";
        }
        $submitName=(!$active) ? "activate-submit" : "edit-submit";
        $submitVal=(!$active) ? "Kijelöltek aktiválása" : "Kijelöltek szerkesztése";
        //$disabled=(!$active) ? " disabled='disabled'" : ""; //-----to be enabled in JS only (after page load)!-----
        $disabled="";
        $output.="<p><input type='submit' name='".$submitName."' value='".$submitVal."'".$disabled." /></p>";
        $output.="</form>";
      }
      else $output=false;
      return $output;
    }
    
    public function validate($type=null, $val){
      if(is_null($type)) $type="password";
      if($type=="password"){
        if(!preg_match("/^.*(?=.*\d).*(?=.*\d).{8,}$/", $val)){
          return false;
        }
      }
      return true;
    }
    
    public function hasAccess($pid){
      global $conn;
      
      if(is_null($pid))
        return false;
      
      $perm=$this->getPermissions();
      $sql = "select * from ".PAGES." where id=$pid and jogok like '".$perm."'";
      $res=$conn->query($sql) or die($conn->error." on line <b>".__LINE__."</b>");

      if($res->num_rows)
        return true;
      else
        return false;
    }
    
    public function logout(){
      unset($_SESSION["userid"]);
      unset($_SESSION["userperm"]);
      $this->id = 0;
      $this->permissions = "1___";
      setMsg("<div class='success'>Sikeres kijelentkezés.</div>");
    }
  }
?>