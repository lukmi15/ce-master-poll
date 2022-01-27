<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>HTW CE Master Umfrage</title>
		<style>
			table, tr, td, th
			{
				border: 1px solid #76B900;
				border-collapse: collapse;
				padding: 20px;
				align: center;
			}
			.fullsizeinputs
			{
				width: 100%;
				background-color: black;
				color: white;
				border: none;
				margin: 0px;
				cursor: pointer;
				font-weight: bold;
				font-size: large;
				padding: 20px;
				margin: 0px;
			}
			.slidecontainer
			{
				min-width: 400px;
			}
			table
			{
				margin-left: auto;
				margin-right: auto;
				font-size: large;
			}
			p
			{
				text-align: center;
			}
			html, body
			{
				height: 100%;
				background-color: black;
				color: white;
			}
			#thx
			{
				background-color: darkgreen;
				border-radius: 4px;
				border: 1px solid black;
			}
			#err
			{
				background-color: maroon;
				border-radius: 4px;
				border: 1px solid black;
			}
			a:link, a:visited, a:active
			{
				color: aqua;
				text-decoration: none;
			}
			a:hover
			{
				color: lightblue;
			}
			textarea
			{
				height: 100%;
				width: 100%;
				min-height: 100px;
				background-color: #333;
				border: 1px solid black;
				color: #76B900;
			}
			.visualseparator
			{
				height: 5px;
				padding: 0px;
				margin: 0px;
			}
		</style>
		<script>
			function updateLabel(id)
			{
				var value = document.getElementById(id).value;
				if (value == 0)
					document.getElementById(id + 'label').innerHTML = 'Nicht belegt';
				else
					document.getElementById(id + 'label').innerHTML = value + '/10 aufwändig';
			}
		</script>
	</head>
	<body>
		<?php

			$DATA_FILE = '/var/www/data/survey-data.tsv';
			$MAX_TEXTFIELD_STRLEN = 8192;

			function is_valid_value($value)
			{
				return (int)$value >= 0 and (int)$value <= 10;
			}

			//Evaluation mode
			if (isset($_GET['eval']))
			{
				echo '<pre>';
				$contents = file_get_contents($DATA_FILE);
				if ($contents === false)
					echo 'Failed to read survey file, sorry';
				else
					echo $contents;
				echo '</pre></body></html>';
				die();
			}

			//If the survey is done
			elseif
			(
				isset($_POST['m1']) and
				isset($_POST['m2']) and
				isset($_POST['m3']) and
				isset($_POST['m4']) and
				isset($_POST['m5']) and
				isset($_POST['m8']) and
				isset($_POST['m9']) and
				isset($_POST['m10']) and
				isset($_POST['m11']) and
				isset($_POST['m12']) and
				isset($_POST['m13']) and
				isset($_POST['m14']) and
				isset($_POST['m15']) and
				isset($_POST['m16']) and
				isset($_POST['m17']) and
				isset($_POST['remarks'])
			)
			{

				//Check for validity
				if
				(
					!is_valid_value($_POST['m1']) or
					!is_valid_value($_POST['m2']) or
					!is_valid_value($_POST['m3']) or
					!is_valid_value($_POST['m4']) or
					!is_valid_value($_POST['m5']) or
					!is_valid_value($_POST['m8']) or
					!is_valid_value($_POST['m9']) or
					!is_valid_value($_POST['m10']) or
					!is_valid_value($_POST['m11']) or
					!is_valid_value($_POST['m12']) or
					!is_valid_value($_POST['m13']) or
					!is_valid_value($_POST['m14']) or
					!is_valid_value($_POST['m15']) or
					!is_valid_value($_POST['m16']) or
					!is_valid_value($_POST['m17']) or
					(
						isset($_POST['remarks']) and
						strlen($_POST['remarks']) > $MAX_TEXTFIELD_STRLEN
					)
				)
					echo '<p id=err>Deine Stimme konnte leider nicht gespeichert werden: Deine Eingaben sind nicht valide</p>';

				//Safe data
				else
				{
					$f = fopen($DATA_FILE, 'a');
					if ($f == false)
						echo '<p id=err>Deine Stimme konnte leider nicht gespeichert werden: Datei konnte nicht zum schreiben geöffnet werden</p>';
					else
					{
						fprintf
						(
							$f,
							"%s\t%s\t%s\t\t%s\t\t%s\t\t%s\t\t%s\t\t%s\t\t%s\t\t%s\t\t%s\t\t%s\t\t%s\t\t%s\t\t%s\t\t%s\t\t%s\t\t%s\n",
							date('c'),
							$_SERVER['REMOTE_ADDR'],
							(int)$_POST['m1'],
							(int)$_POST['m2'],
							(int)$_POST['m3'],
							(int)$_POST['m4'],
							(int)$_POST['m5'],
							(int)$_POST['m8'],
							(int)$_POST['m9'],
							(int)$_POST['m10'],
							(int)$_POST['m11'],
							(int)$_POST['m12'],
							(int)$_POST['m13'],
							(int)$_POST['m14'],
							(int)$_POST['m15'],
							(int)$_POST['m16'],
							(int)$_POST['m17'],
							base64_encode($_POST['remarks'])
						);
						fclose($f);
						echo '<p id=thx>Danke für deine Stimme!</p>';
					}
				}

			}
		?>
		<p>Umfrage zur Arbeitsbelastung im Master pro Modul</p>
		<form method=post target=''>
			<table>
				<tr><th colspan=2>Welches dieser Module war wie aufwändig?</th></tr>
				<tr>
					<td>M1 Programmierung Eingebetteter Systeme</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m1 id=m1 oninput="updateLabel('m1')"/>
						<label id=m1label for=m1>Nicht belegt</label>
					</td>
				</tr>
				<tr>
					<td>M2 Angewandte Mathematik</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m2 id=m2 oninput="updateLabel('m2')"/>
						<label id=m2label for=m2>Nicht belegt</label>
					</td>
				</tr>
				<tr>
					<td>M3 Ausgewählte Kapitel der Softwareentwicklung</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m3 id=m3 oninput="updateLabel('m3')"/>
						<label id=m3label for=m3>Nicht belegt</label>
					</td>
				</tr>
				<tr>
					<td>M4 Messtechnik</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m4 id=m4 oninput="updateLabel('m4')"/>
						<label id=m4label for=m4>Nicht belegt</label>
					</td>
				</tr>
				<tr>
					<td>M5 Projektentwicklung</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m5 id=m5 oninput="updateLabel('m5')"/>
						<label id=m5label for=m5>Nicht belegt</label>
					</td>
				</tr>
				<tr class=visualseparator><td class=visualseparator colspan=2></td></tr>
				<tr>
					<td>M8 Bild- und Videoverarbeitung</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m8 id=m8 oninput="updateLabel('m8')"/>
						<label id=m8label for=m8>Nicht belegt</label>
					</td>
				</tr>
				<tr>
					<td>M9 VLSI-Anwendungen</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m9 id=m9 oninput="updateLabel('m9')"/>
						<label id=m9label for=m9>Nicht belegt</label>
					</td>
				</tr>
				<tr>
					<td>M10 Regelungstechnik</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m10 id=m10 oninput="updateLabel('m10')"/>
						<label id=m10label for=m10>Nicht belegt</label>
					</td>
				</tr>
				<tr>
					<td>M11 Modellbildung- und analyse</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m11 id=m11 oninput="updateLabel('m11')"/>
						<label id=m11label for=m11>Nicht belegt</label>
					</td>
				</tr>
				<tr>
					<td>M12 CE Projekt 1</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m12 id=m12 oninput="updateLabel('m12')"/>
						<label id=m12label for=m12>Nicht belegt</label>
					</td>
				</tr>
				<tr class=visualseparator><td class=visualseparator colspan=2></td></tr>
				<tr>
					<td>M13 Verteilte Systeme</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m13 id=m13 oninput="updateLabel('m13')"/>
						<label id=m13label for=m13>Nicht belegt</label>
					</td>
				</tr>
				<tr>
					<td>M14 Verifikation und Validierung</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m14 id=m14 oninput="updateLabel('m14')"/>
						<label id=m14label for=m14>Nicht belegt</label>
					</td>
				</tr>
				<tr>
					<td>M15 Digitale Signalverarbeitung</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m15 id=m15 oninput="updateLabel('m15')"/>
						<label id=m15label for=m15>Nicht belegt</label>
					</td>
				</tr>
				<tr>
					<td>M16 Drahtlose Kommunikation</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m16 id=m16 oninput="updateLabel('m16')"/>
						<label id=m16label for=m16>Nicht belegt</label>
					</td>
				</tr>
				<tr>
					<td>M17 CE Projekt 2</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m17 id=m17 oninput="updateLabel('m17')"/>
						<label id=m17label for=m17>Nicht belegt</label>
					</td>
				</tr>
				<tr class=visualseparator><td class=visualseparator colspan=2></td></tr>
				<tr>
					<td colspan=2>
						Möchtest du noch was loswerden? (<?php echo $MAX_TEXTFIELD_STRLEN ?> Zeichen erlaubt)<br />
						<textarea maxlength="<?php echo $MAX_TEXTFIELD_STRLEN; ?>" name=remarks></textarea>
					</td>
				<tr>
				<tr><td style=padding:0px colspan=2><input class=fullsizeinputs type=submit value=Absenden /></td></tr>
			</table>
		</form>
		<p style=font-size:small><a href='https://github.com/lukmi15/ce-master-poll'>Check out the source code on GitHub!</a></p>
	</body>
</html>
