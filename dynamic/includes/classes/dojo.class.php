<?php
  class Dojo {
    private $name;
    private $city;
    
    public function __construct($name=null, $city=null){
      if(!is_null($name)) $this->name=$name;
	  if(!is_null($city)) $this->city=$city;
    }
    
    /*
     * Get all dojos from the system
     * @param $format possible values: table, option
     */
    public function getDojos($format="table", $editable=false){
      global $conn;
      
      $sql = "select * from ".DOJOS." order by nev, varos";
      $res=$conn->query($sql) or die($conn->error." on line <b>".__LINE__."</b>");
      
      $output="";
      if($res->num_rows){
        if($format=="table"){
          $output.="<table class='list'>";
          $output.="<tr><th>Név</th><th>Város</th></tr>";
          $i=1;
          while($row=$res->fetch_assoc()){
            $output.="<tr class='";
            if($i%2==1) $output.="odd";
            else $output.="even";
            $output.="'>";
            $output.="<td><input type='hidden' name='dojoId[]' value='".$row["id"]."' />";
            if($editable)
              $output.="<input type='text' name='dojoName[]' value='".$row["nev"]."' />";
            else $output.=$row["nev"];
            $output.="</td>";
            $output.="<td>";
            if($editable)
              $output.="<input type='text' name='dojoCity[]' value='".$row["varos"]."' />";
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
          $output.="</table>";
        }
        else if($format=="option"){
          while($row=$res->fetch_assoc())
            $output.="<option value=".$row["id"].">".$row["nev"]." (".$row["varos"].")</option>";
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
      $ok=true;
      $prefix="<div class='error'>";
      if($name==""){
        $_SESSION["dojoName"]["err_msg"]=$prefix."A dojo neve nem lehet üres!</div>";
        $ok=false;
      }
      if($city==""){
        $_SESSION["dojoCity"]["err_msg"]=$prefix."A város nem lehet üres!</div>";
        $ok=false;
      }
      
      if($ok){
        $sql="INSERT INTO ".DOJOS." (nev, varos)
					SELECT '".$name."', '".$city."' from ".DOJOS.
					" WHERE NOT EXISTS(
						SELECT nev, varos FROM ".DOJOS." WHERE nev='".$name."' and varos='".$city.
					"') LIMIT 1";
        $res=$conn->query($sql) or die($conn->error." on line <b>".__LINE__."</b>");
        if(!$conn->affected_rows>0) $ok=false;
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
          $ok=false;
        }
        if($dojoCity==""){
          $_SESSION["dojoCity"][$i]["err_msg"]=$prefix."A város nem lehet üres!</div>";
          $ok=false;
        }
        if($dojoName!="" && $dojoCity!=""){
          $sql="UPDATE ".DOJOS." set nev='".$_POST["dojoName"][$i]."', varos='".$_POST["dojoCity"][$i]."' where id=".$_POST["dojoId"][$i];
          $res=$conn->query($sql) or die($conn->error." on line <b>".__LINE__."</b>");
        }
      }

      return $ok;
    }
  }
?>