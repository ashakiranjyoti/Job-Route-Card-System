<?php 
// session_start();
include('conn.php');

if (isset($_GET['id'])) {
	$id1 = $_GET['id'];
} else {
	$id1 = 'default_id';
}

?>

<section class="projects no-padding-top" style="margin-top:60px;;padding:0px;background-color:bue;width:100%;" >
	<div class="container-fluid" id="auto_div" style="width:100%;background-color:re;padding:0px;padding-left:2px;padding-right:2px;border-radius:10px;padding:3px;">
		<div  id="scroll_x" style="background-color:ed;width:100%;overflow-x: scroll;overflow-y: scroll;height:470px;padding:3px;">
			<!-- Fixed height container for table body scrolling -->
			<div style="max-height:470px; overflow-y: auto;">
			<table  id="customers" cellpadding="4" bgcolor="lightgray" style="border-spacing: 0px;top-border:1px;min-width:900px;width:100%;height:auto;" bordercolor="" >
				<thead>
				<tr align="center" style="height:40px;background-color:#428BCA !important">
					<td width="35" rowspan=2 ><font size="1" color="white"><span id="avenir_b_14_g" style="color:white;;font-size:12px;" >&nbsp;&nbsp;S No.</b></font></th>
					<td width="110" rowspan=2 ><font size="1" color="white"><span id="avenir_b_14_g" style="color:white;font-size:12px;" >STN</b></font></th>
					<td width="" rowspan=2 ><font size="1" color="white"><span id="avenir_b_14_g" style="color:white;font-size:12px;" >Date & Time</b></font></th>
					<td width="" rowspan=2 ><font size="1" color="white"><span id="avenir_b_14_g" style="color:white;font-size:12px;" >PT (kg/cm<sup>2</sup>)</b></font></th>
					
				</tr>
				
						
					</thead>
					<?php
					$counter = 0;
					$trid = '';
					$col_1 = '';
					$col_5 = '';
					$col_9 = '';
					$col_2 = '';
					$col_4 = '';
					$col_6 = '';
					$col_8 = '';
					$col_10 = '';
					$col_21 = '';
					$col_22 = '';
					$col_23 = '';
					$col_24 = '';
					$col_25 = '';
					$col_26 = '';
					$col_27 = '';
					$col_28 = '';
					$col_29 = '';
					$col_30 = '';
					$col_31 = '';
					$col_32 = '';
					$col_33 = '';
					$col_34 = '';
					$col_35 = '';
					$col_36 = '';
					$col_37 = '';
					$col_38 = '';
					$col_39 = '';
					$col_40 = '';
					$col_41 = '';
					$col_42 = '';
					$col_43 = '';
					$New_Normal = '';

					// // Duration calculation based on type
					// if ($type == '2m') $DURATION = 2 * 60;
					// else if ($type == '5m') $DURATION = 5 * 60;
					// else if ($type == '15m') $DURATION = 15 * 60;
					// else if ($type == '1h') $DURATION = 1 * 60 * 60;
					// else if ($type == '6h') $DURATION = 6 * 60 * 60;
					// else if ($type == '12h') $DURATION = 12 * 60 * 60;
					// else if ($type == '24h') $DURATION = 24 * 60 * 60;
					// else if ($type == 'all') $DURATION = 1;

					$first = '';
					$counter = 0;

					// Append time to date for full timestamp range
					$s_date = isset($_SESSION['eTimeStampStart']) ? $_SESSION['eTimeStampStart'] : date('Y-m-d');
$e_date = isset($_SESSION['eTimeStampEnd']) ? $_SESSION['eTimeStampEnd'] : date('Y-m-d');

// Ensure start date is max 7 days before end date
$max_start_date = date('Y-m-d', strtotime('-7 days', strtotime($e_date)));
if ($s_date < $max_start_date) {
    $s_date = $max_start_date;
}

$today = date('Y-m-d');
// Append time for full timestamp range
$s_date = $today . ' 00:00:00';
$e_date = $today . ' 23:59:59';

// echo "Adjusted Start Date: " . $s_date;
// echo "Adjusted End Date: " . $e_date;

					// Fetch data in descending order
					// $result = odbc_exec($conn, "SELECT * FROM LOGS WHERE STN = '$id1' AND eTimeStamp BETWEEN '$s_date' AND '$e_date' ORDER BY eTimeStamp DESC");
                    $query = "SELECT * FROM LOGS WHERE STN = '$id1' AND eTimeStamp BETWEEN '$s_date' AND '$e_date' ORDER BY eTimeStamp DESC";
                    $result = odbc_exec($MConn, $query);
					while (odbc_fetch_row($result)) {
						$col_1 = odbc_result($result, "eTimeStamp");
						$second = $col_1;

						// Initialize $first for the first iteration
						if ($first == '') {
							$first = $second;
						} else {
							// Calculate time difference between $first and $second
							$total = strtotime($first) - strtotime($second);

							// Skip rows that don't meet the duration criteria
							// if ($total < $DURATION && $type != 'all') {
							// 	continue;
							// }
						}

						// Update $first to the current timestamp
						$first = $second;

						// Increment counter and display data
						$counter++;

						// $second = $col_1;
						// $counter++;
						$col_stn = odbc_result($result, "STN");
						$col_2 = odbc_result($result, "eTimeStamp");

						$col_4 = odbc_result($result, "P77");
						$col_5 = odbc_result($result, "P78");
						$col_6 = odbc_result($result, "P79");

						$co1_8 = odbc_result($result, "P80");
						$col_9 = odbc_result($result, "P81");
						$col_10 = odbc_result($result, "P82");

						$col_12 = odbc_result($result, "P83");
						$col_13 = odbc_result($result, "P84");
						$col_14 = odbc_result($result, "P85");

						$col_15 = odbc_result($result, "P65");
						$col_16 = odbc_result($result, "P66");
						$col_17 = odbc_result($result, "P68");

						$col_18 = odbc_result($result, "P95");
						$col_19 = odbc_result($result, "P96");
						$col_20 = odbc_result($result, "P19");

						$col_21 = odbc_result($result, "P86");
						$col_22 = odbc_result($result, "P87");
						$col_23 = odbc_result($result, "P88");

						$col_24 = odbc_result($result, "P92");
						$col_25 = odbc_result($result, "P90");
						$col_26 = odbc_result($result, "P91");
						$col_27 = odbc_result($result, "P89");

						$col_28 = odbc_result($result, "P17");
						$col_29 = odbc_result($result, "P18");
						$col_30 = odbc_result($result, "P19");
						$col_31 = odbc_result($result, "P1");
						$col_32 = odbc_result($result, "P2");
						$col_33 = odbc_result($result, "P3");

						$col_34 = odbc_result($result, "P5");
						$col_35 = odbc_result($result, "P6");
						$col_36 = odbc_result($result, "P7");
						$col_37 = odbc_result($result, "P9");
						$col_38 = odbc_result($result, "P10");
						$col_39 = odbc_result($result, "P11");

						$col_40 = odbc_result($result, "P20");
						$col_41 = odbc_result($result, "P72");
						$col_42 = odbc_result($result, "P23");
						$col_43 = odbc_result($result, "P54");


						if ($counter % 2 == 0) $bg = '#F4F6F7';
						else $bg = "white";

						$final_column = '';
						$main_column = '';
						$bypass_column = '';
						$outlet_column = '';
						$panel_door = '';
						$doser_pump = '';

						if ($col_29 == '1') {
							$final_column = '<span style="color: #bf9a06;">Fault</span> ';
						} elseif ($col_28 == '1') {
							$final_column = '<span style="color: green;" >Run</span>';
						} else {
							$final_column = '<span style="color: red;">Stop</span>';
						}


						// $col_30 = ($col_30 == '1') ? 'Normal' : 'Reverse';

						if ($col_30 == 1) {
							$New_Normal = 'Normal';
						} else if ($col_40 == 1) {
							$New_Normal = 'Reverse';
						} else {
							$New_Normal = '----';
						}



						if ($col_31 == '1') {
							$main_column = '<span style="color: green;">OPEN</span>';
						} elseif ($col_32 == '1') {
							$main_column = '<span style="color: red;">CLOSE</span>';
						} elseif ($col_33 == '1') {
							$main_column = '<span style="color: #bf9a06;">TRIP</span>';
						} else {
							$main_column = '<span style="color: gray;">----</span>';
						}


						if ($col_34 == '1') {
							$bypass_column = '<span style="color: green;">OPEN</span>';
						} elseif ($col_35 == '1') {
							$bypass_column = '<span style="color: red;">CLOSE</span>';
						} elseif ($col_36 == '1') {
							$bypass_column = '<span style="color: #bf9a06;">TRIP</span>';
						} else {
							$bypass_column = '<span style="color: gray;">----</span>';
						}


						if ($col_37 == '1') {
							$outlet_column = '<span style="color: green;">OPEN</span>';
						} elseif ($col_38 == '1') {
							$outlet_column = '<span style="color: red;">CLOSE</span>';
						} elseif ($col_39 == '1') {
							$outlet_column = '<span style="color: #bf9a06;">TRIP</span>';
						} else {
							$outlet_column = '<span style="color: gray;">----</span>';
						}




						$col_1 = substr($col_1, 0, 19);
						$col_4 = number_format($col_4, 1);
						$col_5 = number_format($col_5, 1);
						$col_6 = number_format($col_6, 1);

						$co1_8 = number_format($co1_8, 1);
						$col_9 = number_format($col_9, 1);
						$col_10 = number_format($col_10, 1);

						$col_12 = number_format($col_12, 1);
						$col_13 = number_format($col_13, 1);
						$col_14 = number_format($col_14, 1);

						$col_21 = number_format($col_21, 1);
						$col_22 = number_format($col_22, 1);
						$col_23 = number_format($col_23, 1);

						$col_24 = number_format($col_24, 1);
						$col_25 = number_format($col_25, 1);
						$col_26 = number_format($col_26, 1);

						$col_27 = number_format($col_27, 1);
						$col_15 = number_format($col_15, 1);
						$col_16 = number_format($col_16, 1);

						$col_17 = number_format($col_17, 1);
						$col_18 = number_format($col_18, 1);
						$col_19 = number_format($col_19, 1);
						$col_41 = number_format($col_41, 1);
						$col_42 = number_format($col_42, 1);
						$col_43 = number_format($col_43, 1);

if($col_42 == 1) {
	$panel_door = '<span style="color: green;">CLOSE</span>';
} else {
	$panel_door = '<span style="color: RED;">OPEN</span>';
}

if($col_43 == 1) {
	$doser_pump= '<span style="color: green;">RUN</span>';
} else {
	$doser_pump = '<span style="color: red;">STOP</span>';
}

						$lvl_c = 'black';
						$pmp_lvl = '-----';
						$pmp_lvl = 'Low';
						$lvl_c = 'red';
						if ($col_18 == "1" || $col_18 == "0") {
							$pmp_lvl = 'Low';
							$lvl_c = 'red';
						}
						if ($col_19 == "1") {
							$pmp_lvl = 'Middle';
							$lvl_c = 'blue';
						}
						if ($col_20 == "1") {
							$pmp_lvl = 'Upper';
							$lvl_c = 'green';
						}

					?>
						<tr align="center" style="height:40px;background-color:<?php echo $bg; ?>;border-bottom:1px solid #2C3E50 ">
							<td><span id="avenir_b_14_g" style="font-size:12px"><?php echo $counter; ?>.</span>
							<td><span id="avenir_b_14_g" style="font-size:12px"><?php echo $col_stn; ?></span>
							<td style="text-align: center;font-size:12px">
								<span id="avenir_b_14_g" style="font-size:12px">
									<?php
									$dateTime = $col_2;
									$dateParts = explode(' ', $dateTime);
									$date = $dateParts[0];
									$time = $dateParts[1];

									$formattedTime = date("H:i", strtotime($time));

									echo $date . "<br>" . $formattedTime;
									?>
								</span>
							</td>
							<td><span id="avenir_b_14_g" style="color: black !important; font-size:12px"><?php echo $col_16; ?></span>

						</tr>

					<?php
					}
					if ($counter == 0) {
					?>
						<tr align="center" style="height:40px;background-color:white;border-bottom:1px solid #2C3E50 ">
							<td colspan="36"><span id="avenir_b_20_g" style="color:red">Opps!! No Record Found</span>
						</tr>
					<?php
					}
					?>
				</table>
			</div>
		</div>

</section>