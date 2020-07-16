<?php
  class Dojo {
    private $name;
    private $city;
	private static $activeDojoCount;
    
    public function __construct($name=null, $city=null){
	  global $conn;
      if(!is_null($name)) $this->name=$name;
	  if(!is_null($city)) $this->city=$city;
	  self::$activeDojoCount=0;
	  $sql="SELECT count(distinct dojo_id) as dojo_db
		FROM ".MEMBERSHIPS." inner join ".USERS." ON ".
		MEMBERSHIPS.".tag_id=".USERS.".id where aktiv=1";
	  $res=$conn->query($sql) or die($conn->error." on line <b>".__LINE__."</b>");
	  self::$activeDojoCount=$res->fetch_assoc()["dojo_db"];
    }
	
	public function getActiveDojoCount(){
	  return self::$activeDojoCount;
	}
    
    /*
     * Get all dojos from the system
     * @param string $format possible values: table, option
     * @param boolean $editable
     */
    public function getDojos($format="table", $editable=false){
      global $conn;
      
      $sql = "select * from ".DOJOS." order by nev, varos";
      $res=$conn->query($sql) or die($conn->error." on line <b>".__LINE__."</b>");
      
      $output="";
      if($res->num_rows){
        if($format=="table"){
		  $output.='<div class="table-responsive-lg">';
          $output.="<table class='table table-striped table-dark'>";
          $output.="<tr><th scope='col' class='p-1'>Név</th><th scope='col' class='p-1'>Város</th></tr>";
          $i=1;
          while($row=$res->fetch_assoc()){
            $output.="<tr class='";
            if($i%2==1) $output.="odd";
            else $output.="even";
            $output.="'>";
            $output.="<td><input type='hidden' name='dojoId[]' value='".$row["id"]."' />";
            if($editable)
              $output.="<input type='text' class='form-control form-control-sm' name='dojoName[]' value='".$row["nev"]."' />";
            else $output.=$row["nev"];
            $output.="</td>";
            $output.="<td>";
            if($editable)
              $output.="<input type='text' class='form-control form-control-sm' name='dojoCity[]' value='".$row["varos"]."' />";
            else $output.=$row["varos"];
            $output.="</td>";
            $output.="</tr>";
            
            //Check for errors
            if(isset($_SESSION["dojoName"][$i-1]["err_msg"]) || isset($_SESSION["dojoCity"][$i-1]["err_msg"])){
              $output.="<tr class='";
              if($i%2==1) $output.="odd";
              else $output.="even";
              $output.="'><td>";
              if(isset($_SESSION["dojoName"][$i-1]["err_msg"]))
                $output.=$_SESSION["dojoName"][$i-1]["err_msg"];
              $output.="</td>";
              $output.="<td>";
              if(isset($_SESSION["dojoCity"][$i-1]["err_msg"]))
                $output.=$_SESSION["dojoCity"][$i-1]["err_msg"];
              $output.="</td></tr>";
            }
            $i++;
          }
          $output.="</table></div>";
        }
        else if($format=="option"){
          while($row=$res->fetch_assoc())
            $output.="<option value=".$row["id"].">".$row["nev"]." (".$row["varos"].")</option>";
        }
		else if($format=="radio"){
		  $i=1;
		  while($row=$res->fetch_assoc()){
			$output.='<div class="custom-control custom-radio">
			  <input type="radio" id="dojoId'.$i.'" name="dojoId" class="custom-control-input" value="'.$row["id"].'">
			  <label class="custom-control-label" for="dojoId'.$i.'">'.$row["nev"].' ('.$row["varos"].')</label>
			</div>';
			$i++;
		  }
		}
        else $output.="Ismeretlen formátum!";
      }
      else{
        if($format=="table")
          $output.="Nincs tárolt dojo a rendszerben.";
      }
      
      return $output;
    }
    
    /*
     * Add a dojo
     */
    public function addDojo($name, $city){
      global $conn;
	  
	  /*return value: $ok
	   3: everything is fine
	   2: any of the mandatory fields are empty
	   1: the given dojo already exists
	  */
      $ok=3;
      $prefix="<div class='error'>";
      if($name==""){
        $_SESSION["dojoName"]["err_msg"]=$prefix."A dojo neve nem lehet üres!</div>";
        $ok=2;
      }
      if($city==""){
        $_SESSION["dojoCity"]["err_msg"]=$prefix."A város nem lehet üres!</div>";
        $ok=2;
      }
      
      if($ok==3){
        $sql="INSERT INTO ".DOJOS." (nev, varos)
					SELECT '".$name."', '".$city."' from ".DOJOS.
					" WHERE NOT EXISTS(
						SELECT nev, varos FROM ".DOJOS." WHERE nev='".$name."' and varos='".$city.
					"') LIMIT 1";
        $res=$conn->query($sql) or die($conn->error." on line <b>".__LINE__."</b>");
        if(!$conn->affected_rows>0) $ok=1;
      }
	  
	  return $ok;
    }
    
    /*
     * Modify a list of dojos
     */
    public function modDojos(){
      global $conn;
      $ok=true;
      $prefix="<div class='error'>";
      
      $dojoCount = count($_POST["dojoId"]);
      for($i=0;$i<$dojoCount;$i++){
        $dojoName=sanitize($_POST["dojoName"][$i]);
        $dojoCity=sanitize($_POST["dojoCity"][$i]);
        
        if($dojoName==""){
          $_SESSION["dojoName"][$i]["err_msg"]=$prefix."A dojo neve nem lehet üres!</div>";
		  //echo "A dojo neve üres, \$_SESSION[\"dojoName\"][".$i."][\"err_msg\"] helyen hiba lesz.";
          $ok=false;
        }
        if($dojoCity==""){
          $_SESSION["dojoCity"][$i]["err_msg"]=$prefix."A város nem lehet üres!</div>";
		  //echo "A város üres, \$_SESSION[\"dojoCity\"][".$i."][\"err_msg\"] helyen hiba lesz.";
          $ok=false;
        }
        if($ok){
		  //echo "Ebben a sorban minden ki van töltve.";
          $sql="UPDATE ".DOJOS." set nev='".$dojoName."', varos='".$dojoCity."' where id=".$_POST["dojoId"][$i];
          $res=$conn->query($sql) or die($conn->error." on line <b>".__LINE__."</b>");
        }
      }

      return $ok;
    }
  }
?>