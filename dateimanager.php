<?php


require("includes/common.inc.php");
require("includes/config.inc.php");

define("ROOTVZ","./"); //Konstante; Variable: $rootVZ = "./Ablage/";
$aktuellesVZ = ROOTVZ; //wir gehen einmal davon aus, dass zunächst der Inhalt des Hauptverzeichnisses für die Ablage angezeigt werden soll; das kann sich jedoch später ändern, wenn der User den Inhalt eines anderen Verzeichnisses sehen möchte

$msg = ""; //Ausgabevariable für Erfolgs- und Misserfolgsmeldungen

ta($_POST);

//ta($_FILES);
if(count($_POST)>0) {
	$aktuellesVZ = $_POST["VZUserSelect"];
	/*
	$tmp = explode("/",$aktuellesVZ);
	ta($tmp);
	*/
	
	if(isset($_POST["btnVZNeu"])) {
		$vzname = trim($_POST["VZNeu"]); //entfernt beginnende und endende Leerzeichen
		if(strlen($vzname)>0) {
			if(!file_exists($aktuellesVZ.$vzname)) {
				$ok = mkdir($aktuellesVZ.$vzname,0755,false);
				if(!$ok) {
					$msg.= '<p class="error">Das gewünschte Verzeichnis konnte nicht angelegt werden.</p>';
				}
			}
			else {
				$msg.= '<p class="error">Dieses Verzeichnis existiert bereits.</p>';
			}
		}
		else {
			$msg.= '<p class="error">Das ist kein gültiger Verzeichnisname.</p>';
		}
	}
	
	if(isset($_POST["btnVZDel"])) {
		$ok = loescheVZ($aktuellesVZ);
		if($ok) {
			$aktuellesVZ = ROOTVZ;
		}
		else {
			$msg.= '<p class="error">Leider konnte das gewünschte Verzeichnis nicht gelöscht werden.</p>';
		}
	}
	
	if(isset($_POST["btnVZRename"])) {
		$vzname = trim($_POST["VZRename"]);
		if(strlen($vzname)>0) {
			$tmp = explode("/",$aktuellesVZ);
			//ta($tmp);
			$tmp[count($tmp)-2] = $vzname;
			//ta($tmp);
			$pfad_neu = implode("/",$tmp);
			//ta($pfad_neu);
			$ok = rename($aktuellesVZ,$pfad_neu);
			if($ok) {
				$aktuellesVZ = $pfad_neu;
			}
			else {
				$msg.= '<p class="error">Leider konnte das gewünschte Verzeichnis nicht umbenannt werden.</p>';
			}
		}
	}
}

if(count($_FILES)>0) {
	$f = $_FILES["myUpload"]; //Hilfsvariable
	for($i=0; $i<count($f["name"]); $i++) {
		if($f["error"][$i]==0) {
			$ok = move_uploaded_file($f["tmp_name"][$i],$aktuellesVZ.$f["name"][$i]);
			if(!$ok) {
				$msg.= '<p class="error">Leider konnte die Datei ' . $f["name"][$i] . ' nicht hochgeladen werden.</p>';
			}
		}
	}
}

function leseStruktur(string $root):string {
	$r = "";
	if(file_exists($root)) {
		$r.= '<ul>';
		$inhalt = scandir($root);
		foreach($inhalt as $d) {
			if($d!="." && $d!="..") {
				if(is_dir($root.$d)) {
					$r.= '<li><span data-pfad="' . $root.$d . '/">' . $d . '</span>';
					$r.= leseStruktur($root.$d."/");
					$r.= '</li>';
				}
			}
		}
		$r.= '</ul>';
	}
	
	return $r;
}

function leseInhalt(string $root):string {
	$r = "";
	if(file_exists($root)) {
		$inhalt = scandir($root);
		$r.= '
			<table>
				<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Änderungsdatum</th>
						<th scope="col">Typ</th>
						<th scope="col">Größe</th>
					</tr>
				</thead>
				<tbody>
		';
		foreach($inhalt as $d) {
			if($d!="." && $d!="..") {
				$r.= '<tr>';
				switch(true) {
					case is_dir($root.$d):
						$r.= '
							<td>' . $d . '</td>
							<td></td>
							<td>Verzeichnis</td>
							<td></td>
						';
						break;
					case is_file($root.$d):
						$r.= '
							<td>' . $d . '</td>
							<td></td>
							<td>' . mime_content_type($root.$d) . '</td>
							<td>' . filesize($root.$d) . 'B</td>
						';
						break;
					case is_link($root.$d):
						$r.= '
							<td>' . $d . '</td>
							<td></td>
							<td>Verknüpfung</td>
							<td></td>
						';
						break;
				}
			}
		}
		$r.= '
				</tbody>
			</table>
		';
	}
	
	return $r;
}
function loescheVZ(string $root="./"):bool {
	$r = true;
	
	if(file_exists($root)) {
		$inhalt = scandir($root);
		foreach($inhalt as $d) {
			if($d!="." && $d!="..") {
				if(is_dir($root.$d)) {
					$r = $r && loescheVZ($root.$d."/");
				}
				else {
					$r = $r && unlink($root.$d);
					//ta("versuche zu löschen (Datei/Verknüpfung): ".$root.$d);
				}
			}
		}
		
		if($r) {
			$r = $r && rmdir($root);
			//ta("versuche zu löschen (Verzeichnis): ".$root);
		}
	}
	
	return $r;
}
?>
<!doctype html>
<html lang="de">
	<head>
		<title>Dateimanager</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/common.css">
		<link rel="stylesheet" href="css/dateimanager.css">
		<script>
		document.addEventListener("DOMContentLoaded",() => {
			const klickelemente = document.querySelectorAll("#Struktur span"); //sämtliche anklickbaren Elemente
			for(let i=0; i<klickelemente.length; i++) {
				klickelemente[i].addEventListener("click",(ev) => {
					/*
					console.log(ev);
					console.log(ev.originalTarget.attributes["data-pfad"].nodeValue);
					console.log("Ich wurde geklickt");
					*/
					document.querySelector("[name=VZUserSelect]").value = ev.originalTarget.attributes["data-pfad"].nodeValue;
					document.querySelector("#grid").submit();
				});
			}
		});
		</script>
	</head>
	<body>
		<?php echo($msg); ?>
		<form id="grid" method="post" enctype="multipart/form-data">
			<input type="hidden" name="VZUserSelect" value="<?php echo($aktuellesVZ); ?>">
			<div id="Struktur">
				<span data-pfad="<?php echo(ROOTVZ); ?>">Ablageordner</span>
				<?php
				$struktur = leseStruktur(ROOTVZ);
				echo($struktur);
				?>
			</div>
			<div id="Inhalt">
				<?php
				$inhalt = leseInhalt($aktuellesVZ);
				echo($inhalt);
				?>
			</div>
			<fieldset id="Upload">
				<legend>Datei-Upload ins aktuelle Verzeichnis</legend>
				<label>
					Bitte wählen Sie eine oder mehrere Dateien aus:
					<input type="file" name="myUpload[]" multiple>
				</label>
				<input type="submit" value="hochladen">
			</fieldset>
			<fieldset id="VZNeu">
				<legend>Neues Verzeichnis im aktuellen Verzeichnis anlegen</legend>
				<label>
					Verzeichnisname:
					<input type="text" name="VZNeu">
				</label>
				<input type="submit" value="anlegen" name="btnVZNeu">
				</label>
			</fieldset>
			<?php
			if($aktuellesVZ!=ROOTVZ) {
			?>
				<fieldset id="VZDel">
					<input type="submit" value="aktuelles Verzeichnis löschen" name="btnVZDel">
				</fieldset>
				<fieldset id="VZRename">
					<legend>Bestehendes Verzeichnis umbenennen</legend>
					<label>
						Umbenennen in:
						<input type="text" name="VZRename">
					</label>
					<input type="submit" value="umbenennen" name="btnVZRename">
				</fieldset>
			<?php } ?>
		</form>
	</body>
</html>