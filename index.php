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

			elseif (isset($_POST['m1']) and isset($_POST['m2']) and isset($_POST['m3']) and isset($_POST['m4']) and isset($_POST['m5']) and isset($_POST['remarks'])) //If the survey is done
			{

				//Check for validity
				if (!is_valid_value($_POST['m1']) or !is_valid_value($_POST['m2']) or !is_valid_value($_POST['m3']) or !is_valid_value($_POST['m4']) or !is_valid_value($_POST['m5']) or (isset($_POST['remarks']) and strlen($_POST['remarks']) > $MAX_TEXTFIELD_STRLEN))
					echo '<p id=err>Deine Stimme konnte leider nicht gespeichert werden: Deine Eingaben sind nicht valide</p>';

				//Safe data
				else
				{
					$f = fopen($DATA_FILE, 'a');
					if ($f == false)
						echo '<p id=err>Deine Stimme konnte leider nicht gespeichert werden: Datei konnte nicht zum schreiben geöffnet werden</p>';
					else
					{
						fprintf($f, "%s\t%s\t%s\t\t%s\t\t%s\t\t%s\t\t%s\t\t%s\n", date('c'), $_SERVER['REMOTE_ADDR'], (int)$_POST['m1'], (int)$_POST['m2'], (int)$_POST['m3'], (int)$_POST['m4'], (int)$_POST['m5'], base64_encode($_POST['remarks']));
						fclose($f);
						echo '<p id=thx>Danke für deine Stimme!</p>';
					}
				}

			}
		?>
		<p>Bla bla, Keks. Hier muss noch Text hin der erklärt was das soll.</p>
		<form method=post target=''>
			<table>
				<tr><th colspan=2>Welches dieser Module war wie aufwändig?</th></tr>
				<tr>
					<td>M1 - Embedded</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m1 id=m1 oninput="updateLabel('m1')"/>
						<label id=m1label for=m1>Nicht belegt</label>
					</td>
				</tr>
				<tr>
					<td>M2 - Mathe</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m2 id=m2 oninput="updateLabel('m2')"/>
						<label id=m2label for=m2>Nicht belegt</label>
					</td>
				</tr>
				<tr>
					<td>M3 - Qt</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m3 id=m3 oninput="updateLabel('m3')"/>
						<label id=m3label for=m3>Nicht belegt</label>
					</td>
				</tr>
				<tr>
					<td>M4 - Messtechnik</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m4 id=m4 oninput="updateLabel('m4')"/>
						<label id=m4label for=m4>Nicht belegt</label>
					</td>
				</tr>
				<tr>
					<td>M5 - Projekt</td>
					<td class=slidecontainer>
						<input type="range" min="0" max="10" value="0" class="slider" name=m5 id=m5 oninput="updateLabel('m5')"/>
						<label id=m5label for=m5>Nicht belegt</label>
					</td>
				</tr>
				</tr>
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
