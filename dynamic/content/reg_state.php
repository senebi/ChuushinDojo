<?php
  if(!isset($_SESSION["reg_state"])){
	header("location: index.php");
	exit;
  }
  $state=$_SESSION["reg_state"];
  unset($_SESSION["reg_state"]);
?>
<h3>
  <?php
  if($state=="success") echo "Sikeres regisztráció!";
  else echo "Hiba történt!";
  ?>
</h3>

<?php
if($state=="success")
  echo "<div class='success'>Csak akkor tudsz bejelentkezni, amikor egy admin vagy a dojo edzője aktiválja a fiókod.</div>";
elseif($state=="fail_notify")
  echo "<p>A regisztráció sikerült, de nem minden illetékesnek sikerült e-mailes értesítőt küldeni. Csak akkor tudsz bejelentkezni, amikor egy admin vagy a dojo edzője aktiválja a fiókod.</p>";
else echo "<div class='error'>A regisztráció sikertelen! Kérlek, próbáld újra később!</div>";
?>
