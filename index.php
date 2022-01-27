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
				padding: 5px;
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
				margin: 5px;
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
				/*#76B900*/
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
		</style>
	</head>
	<body>
		<?php

			$DATA_FILE = '/var/www/data/survey-data.tsv';

			function value_to_module_code($value)
			{
				return strtolower(explode(' - ', $value, 2)[0]);
			}

			function is_valid_value($value)
			{
				$module_code = value_to_module_code($value);
				if ($module_code == 'm1')
					return true;
				if ($module_code == 'm2')
					return true;
				if ($module_code == 'm3')
					return true;
				if ($module_code == 'm4')
					return true;
				if ($module_code == 'm5')
					return true;
				return false;
			}

			//Determine step in the survey and create list of answers
			$step = 1;
			$answers = array();
			if (isset($_GET))
			{
				if (isset($_GET['aufwand1']) and is_valid_value($_GET['aufwand1']))
				{
					$step = 2;
					array_push($answers, value_to_module_code($_GET['aufwand1']));
				}
				if ($step == 2 and isset($_GET['aufwand2']) and is_valid_value($_GET['aufwand2']))
				{
					$step = 3;
					array_push($answers, value_to_module_code($_GET['aufwand2']));
				}
				if ($step == 3 and isset($_GET['aufwand3']) and is_valid_value($_GET['aufwand3']))
				{
					$step = 4;
					array_push($answers, value_to_module_code($_GET['aufwand3']));
				}
				if ($step == 4 and isset($_GET['aufwand4']) and is_valid_value($_GET['aufwand4']))
				{
					$step = 5;
					array_push($answers, value_to_module_code($_GET['aufwand4']));
				}
				if ($step == 5 and isset($_GET['aufwand5']) and is_valid_value($_GET['aufwand5']))
				{
					$step = 6;
					array_push($answers, value_to_module_code($_GET['aufwand5']));
				}
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
				echo '</pre>';
			}

			//Handle survey steps
			elseif ($step == 6) //If the survey is done
			{

				//Check for validity
				$is_valid = true;
				foreach ($answers as $answer)
					if (!is_valid_value($answer))
					{
						$is_valid = false;
						break;
					}
				if (!$is_valid)
					echo '<p id=err>Deine Stimme konnte leider nicht gespeichert werden: Deine Eingaben sind nicht valide</p>';

				//Safe data
				$f = fopen($DATA_FILE, 'a');
				if ($f == false)
					echo '<p id=err>Deine Stimme konnte leider nicht gespeichert werden: Datei konnte nicht zum schreiben geöffnet werden</p>';
				else
				{
					fprintf($f, "%s\t%s\t%s\t\t\t%s\t\t\t\t%s\t\t\t\t%s\t\t\t\t%s\n", date('c'), $_SERVER['REMOTE_ADDR'], $answers[0], $answers[1], $answers[2], $answers[3], $answers[4]);
					fclose($f);
					echo '<p id=thx>Danke für deine Stimme!</p>';
				}

			}
			else
			{
				echo '<p>Bla bla, Keks. Hier muss noch Text hin der erklärt was das soll.</p>';
				echo "<form method=get target=''>";
				if ($step >= 2)
					echo "<input type=hidden name=aufwand1 value='${_GET['aufwand1']}' />";
				if ($step >= 3)
					echo "<input type=hidden name=aufwand2 value='${_GET['aufwand2']}' />";
				if ($step >= 4)
					echo "<input type=hidden name=aufwand3 value='${_GET['aufwand3']}' />";
				if ($step >= 5)
					echo "<input type=hidden name=aufwand4 value='${_GET['aufwand4']}' />";
				echo
				"
						<table>
							<tr>
				";
				if ($step == 1)
					echo "<th>Welches dieser Module war am Aufwändigsten?</th>";
				if ($step == 2)
					echo "<th>Welches dieser Module war am Zweitaufwändigsten?</th>";
				if ($step == 3)
					echo "<th>Welches dieser Module war am Drittaufwändigsten?</th>";
				if ($step == 4)
					echo "<th>Welches dieser Module war am Viertaufwändigsten?</th>";
				if ($step == 5)
					echo "<th>Welches dieser Module war am wenigsten aufwändig?</th>";
				echo "</tr>";
				if (!in_array('m1', $answers))
					echo
					"
							<tr>
								<td><input class=fullsizeinputs type=submit name='aufwand$step' value='M1 - Embedded' /></td>
							</tr>
					";
				if (!in_array('m2', $answers))
					echo
					"
							<tr>
								<td><input class=fullsizeinputs type=submit name='aufwand$step' value='M2 - Mathe' /></td>
							</tr>
					";
				if (!in_array('m3', $answers))
					echo
					"
							<tr>
								<td><input class=fullsizeinputs type=submit name='aufwand$step' value='M3 - QT' /></td>
							</tr>
					";
				if (!in_array('m4', $answers))
					echo
					"
							<tr>
								<td><input class=fullsizeinputs type=submit name='aufwand$step' value='M4 - Messtechnik' /></td>
							</tr>
					";
				if (!in_array('m5', $answers))
					echo
					"
							<tr>
								<td><input class=fullsizeinputs type=submit name='aufwand$step' value='M5 - Projekt' /></td>
					";
				echo
				"
							</tr>
						</table>
					</form>
				";
			}
		?>
	</body>
</html>
