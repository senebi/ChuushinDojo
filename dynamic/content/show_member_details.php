<?php
  if(!isset($_POST["editUser"]) && !isset($_GET["users"])){
	header("location: index.php?pid=5");
	exit;
  }

  $users=(isset($_POST["editUser"]) ? $_POST["editUser"] : explode(",",$_GET["users"]));
  $userCount=count($users);
  
  $user=new User();
  $dataFields=array("Név" => "nev",
				 "Nick" => "felhasznalonev",
				 "E-mail" => "email",
				 "Születési dátum" => "szuletesi_datum",
				 "Beiratkozási dátum" => "beiratkozas_datum",
				 "Regisztrációs dátum" => "reg_datum",
				 "Övfokozat" => "ovfokozat",
				 "Rang" => "jog");
  
  echo "<p>Szerkeszthető felhasználók:</p>";
  $action=$contentDir."/edit_members.php";
  ?>
  <form method="post" action="<?php echo $action; ?>">
	<div class="table-responsive-lg">
	  <table class="table table-striped table-dark">
	  <thead>
		<tr>
		  <th scope="col" class="p-1">Név</th>
		  <th scope="col" class="p-1">E-mail</th>
		  <th scope="col" class="p-1">Születési dátum</th>
		  <th scope="col" class="p-1">Beiratkozási dátum</th>
		  <th scope="col" class="p-1">Regisztrációs dátum</th>
		  <th scope="col" class="p-1">Övfokozat</th>
		  <th scope="col" class="p-1">Rang</th>
		</tr>
	  </thead>
	  <tbody>
	  <?php
	  for($i=0; $i<$userCount; $i++){
		?>
		<tr>
		<?php
		$id=sanitize($users[$i]);
		$data=getSpecificData($id);
		if(!($user->getRank()!="admin" && $data["jog"]=="admin")){
		  ?>
		  <td class="p-1">
			<input type="hidden" name="editUser[]" value="<?php echo $id; ?>" />
			<?php echo $data["nev"]." (".$data["felhasznalonev"].")"; ?>
		  </td>
		  <td class="p-1"><?php echo $data["email"]; ?></td>
		  <td class="p-1"><?php echo $data["szuletesi_datum"]; ?></td>
		  <td class="p-1"><input type="date" value="<?php echo $data["beiratkozas_datum"]; ?>" name="startDate[]" /></td>
		  <td class="p-1"><?php echo $data["reg_datum"]; ?></td>
		  <td class="p-1">
			<select name="beltDegree[]">
			<option value="non">nincs</option>
			<?php
			for($j=6; $j>0; $j--){
			  $degree=$j." kyu";
			  echo "<option value='".$degree."'";
			  if($data["ovfokozat"]==$degree) echo " selected";
			  echo ">".$degree."</option>";
			}
			
			for($j=1; $j<=10; $j++){
			  $degree=$j." dan";
			  echo "<option value='".$degree."'";
			  if($data["ovfokozat"]==$degree) echo " selected";
			  echo ">".$degree."</option>";
			}
			?>
			</select>
		  </td>
		  <td class="p-1">
			<?php
			if($user->getRank()=="admin"){
			?>
			<button type="button" name="downgrade[]" class="downgrade btn btn-primary  btn-sm rounded-circle"><i class="fa fa-minus fa-1" aria-hidden="true"></i></button>
			<span class="editableRank">
			<?php echo " ".$data["jog"]." "; ?>
			</span>
			<button type="button" name="upgrade[]" class="upgrade btn btn-primary  btn-sm rounded-circle"><i class="fa fa-plus fa-1" aria-hidden="true"></i></button>
			<input type="hidden" name="rank[]" class="hiddenRank" value="<?php echo $data["jog"]; ?>" />
			<?php
			}
			else echo $data["jog"];
			?>
		  </td>
		<?php
		}
		?>
		</tr>
		<?php
	  }
	?>
		
	  </tbody>
	  </table>
	</div>
	<div class="form-group row justify-content-center">
	  <button type="submit" name="editSelected-submit" class="btn btn-primary">Mentés</button>
	</div>
  </form>
  <a href="javascript:history.back()">Vissza</a>