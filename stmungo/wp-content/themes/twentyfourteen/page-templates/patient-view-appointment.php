<?php
/**
 * Template Name: Patient View Appointment
 */
get_header();
/* Only if a patient is logged in, the following content will be displayed */
if(isset($_SESSION['userid']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] == 2) {
	global $wpdb;
	$message = '';
	$tablename = $wpdb->prefix;
	$query = 'SELECT A.apt_date, T.diagnosis, T.prescribed_med, D.name FROM ' . $tablename. 'appointments A, ' . $tablename. 'treatments T, ' . $tablename. 'hpusers D, ' . $tablename. 'treats T2 WHERE A.patient_id = "' . $_SESSION['userid']. '" AND A.patient_id = T2.patient_id AND T2.doctor_id = D.userid AND T2.bill_no = T.bill_no;';
	$patientAppointments = $wpdb->get_results($query, ARRAY_A); ?>
<div class="leftnav">
<?php get_sidebar('patient');?>
</div>
<div class="main_content">
	<h2>Your appointments</h2>
	<?php if(count($patientAppointments) != 0) { ?>
	<table>
		<thead>
			<tr class="header_row">
				<th>Appointment Date</th>
				<th>Diagnosis</th>
				<th>Prescribed Medicines</th>
				<th>Admission Details</th>
				<th>Reference Doctor</th>
			</tr>
			<?php
			$inPatient = new UpdateDatabaseOptions("in_patients");
			$rooms = new UpdateDatabaseOptions("rooms");
			$roomType = new UpdateDatabaseOptions("roomtype");
			$nurses = new UpdateDatabaseOptions("nurses");
			$admissionDetails = $inPatient->selectValue(array('roomno'), array('userid' => $_SESSION['userid'], 'admit_date' => $appointment['apt_date']));
			foreach ($patientAppointments as $appointment) {
				if(isset($_POST[$appointment['apt_date']])) {
					$roomsOfSelectedType = $rooms->selectValue(array('roomno'), array('typeid' => $_POST[$appointment['apt_date']]));
					$unavailableRoomsOfSelectedType = $nurses->selectValue(array('roomno'), array(''));
					$availableRoomsOfSelectedType = array_intersect($roomsOfSelectedType, $unavailableRoomsOfSelectedType);
					if(count($availableRoomsOfSelectedType) != 0) {
						$allottedRoom = array_rand($availableRoomsOfSelectedType);
						if($inPatient->updateRow(array('roomno' => $availableRoomsOfSelectedType[$allottedRoom]['roomno']), array('userid' => $_SESSION['userid'], 'admit_date' => $appointment['apt_date']), array('%d'), array('%s', '%s')))
						$message = 'Room updated';
						else $message = 'Room could not be updated. Please try again.';
						//if($nurses->updateRow(array('roomno' => $availableRoomsOfSelectedType[$allottedRoom]['roomno']), array('roomno' => ''), array('%d'), array('%d')))
					}
					else $message = "Sorry! Rooms of this type are full. Please choose another type";
				}
				?>
			<tr>
				<td><?php echo $appointment['apt_date'];?></td>
				<td><?php if($appointment['diagnosis'] != '') echo $appointment['diagnosis']; else echo 'Diagnosis not available yet.';?>
				</td>
				<td><?php if($appointment['prescribed_med'] != '') echo $appointment['prescribed_med']; else echo 'Prescriptions not available yet.';?>
				</td>
				<td><?php 
					
				
				if(count($admissionDetails) != 0) {
					echo 'In-patient: ' . $admissionDetails[0]['roomno'];
					$admittedRoomType = $rooms->selectValue(array('typeid'), array('roomno' => $admissionDetails[0]['roomno']));
					if($admittedRoomType[0]['typeid'] == '1') { ?>
					<p>You are currently admitted to a General room by default. You can
						choose to change the type of room:</p>
					<form name="roomtype<?php echo $appointment['apt_date'];?>"
						method="post" action="">
						<select style="width: 100px;"
							onchange="changeRoomType(this.value, this.name)"
							name="<?php echo $appointment['apt_date'];?>">
							<?php $types = $roomType->selectValue(array('typeid', 'rtype'), array(''));
							foreach($types as $type) { ?>
							<option value="<?php echo $type['typeid'];?>">
							<?php echo $type['rtype']?>
							</option>
							<?php  } ?>
						</select>
					</form> <?php }
				}
				else echo 'Out-Patient';
				?>
				</td>
				<td><?php echo $appointment['name'];?></td>
			</tr>
			<?php }?>
		</thead>
	</table>
	<?php } else { $message = 'You have no appointments.'; }?>
	<?php if($message != '') {?>
	<div class="infobar">
	<?php echo $message;?>
	</div>
	<?php }?>
</div>
	<?php }
	else {
		header("Location:" . site_url());
	}
	get_footer();
