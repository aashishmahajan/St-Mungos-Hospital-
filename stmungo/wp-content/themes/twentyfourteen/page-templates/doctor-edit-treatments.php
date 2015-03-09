<?php
/**
 * Template Name: Doctor Edit Treatment Details
 */
get_header();
$message = '';
if(!isset($_GET['patient_id']) || !isset($_GET['patient_id'])) {
	header('Location:' . get_permalink(get_page_by_title('View all appointments')));
}
else {
	/* Only if a doctor is logged in, the following content will be displayed */
	if(isset($_SESSION['userid']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] == 3) {
		$patient_id = isset($_GET['patient_id']) ? $_GET['patient_id'] : '';
		$apt_date = isset($_GET['apt_date']) ? $_GET['apt_date'] : '';
		$patient_type = new UpdateDatabaseOptions('in_patients');
		$patient_type1 = $patient_type->selectValue(array('userid'), array('userid' => $patient_id,'discharge_date'=>'9999-12-01'));
		/* extract 	bill no for the patient*/
		global $wpdb;
		$tablename = $wpdb->prefix;
		$query = 'SELECT T.bill_no,TR.diagnosis,TR.prescribed_med FROM ' . $tablename. 'treats T,' .$tablename. 'treatments TR
	WHERE T.doctor_id = "' . $_SESSION['userid']. '" AND T.patient_id = "' . $patient_id . '"
	AND T.bill_no=TR.bill_no AND TR.apt_date="' .$apt_date .'"';
		$billno = $wpdb->get_results($query, ARRAY_A);
		$pres_med_p=$billno[0]['prescribed_med'];
		$diag_p=$billno[0]['diagnosis'];
		/*Update treatments*/
		$treatments = new UpdateDatabaseOptions('treatments');
		if(isset($_POST['psubmit'])) {
			$error = 0;
			$diagnosis = mysql_real_escape_string($_POST['diagnosis']);
			$pres_med = mysql_real_escape_string($_POST['pres_med']);

			if($diagnosis !='' && $pres_med!=''){
				if(!$treatments->updateRow(
				array('diagnosis' => $diagnosis,
						'prescribed_med' => $pres_med),
				array('apt_date' => $apt_date,
						'bill_no'=>$billno[0]['bill_no']),
				array('%s', '%s'),
				array('%s','%d')))
				$error++;
			}
			else {
				echo "'Diagnosis', 'Prescribed Medicines' cannot be left blank";
			}

			$patient_status = mysql_real_escape_string($_POST['PatientStatus']);
			if($patient_status==1 && !($patient_type1))
			{
				global $wpdb;
				$tablename = $wpdb->prefix;
				$query1 = 'SELECT R.roomno FROM ' . $tablename. 'rooms R
	WHERE R.typeid=1 AND R.roomno!=310 AND R.roomno NOT IN ( SELECT I.roomno FROM ' .$tablename. 'in_patients I)';
				$roomno = $wpdb->get_results($query1, ARRAY_A);
				$chosenRoom = $roomno[0]['roomno'];

				$treats = new UpdateDatabaseOptions('in_patients');
				$treatment = $treats->insertRow(array('userid' => $patient_id,
									'admit_date' => $apt_date,
									'discharge_date'=> '9999-12-01',
									'roomno' => $chosenRoom), array('%s', '%s', '%s','%d'));

				$nurse = new UpdateDatabaseOptions('nurses');
				$nurseAssigned = $nurse->selectValue(array('userid'), array('available' => 1));
				$chosenNurse = array_rand($nurseAssigned);

				if(!$nurse->updateRow(
				array('available' => 0,
						'roomno' => $chosenRoom),
				array('userid' => $nurseAssigned[$chosenNurse]['userid']),
				array('%d', '%d'),
				array('%s')))
				$error++;
			}

			if($error != 0) {
				$message = 'Sorry! Please try again.';
			}
			else {
				$message = 'Changes successfully saved.';
			}


		}

		?>
<div class="leftnav">
<?php get_sidebar('doctor');?>
</div>
<div class="main_content">
<?php if($message != '') {?>
	<div class="infobar">
	<?php echo $message;?>
	</div>
	<?php }?>
	<h2>Edit Treatment Details</h2>
	<form name="editTreatmentDetails" action="" method="post">
		<p>
			Patient
			ID:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<strong><?php echo $patient_id;?> </strong>
		</p>
		<p>
			Appointment
			Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<strong><?php echo $apt_date;?> </strong>
		</p>
		<p>
			<label for="diagnosis">Diagnosis</label> <input type="text"
				name="diagnosis" id="diagnosis" value="<?php echo $diag_p;?>" />
		</p>
		<p>
			<label for="pres_med">Prescribed Medicines</label> <input type="text"
				name="pres_med" id="pres_med" value="<?php echo $pres_med_p;?>" />
		</p>
		<p>
			<label for="Patient_Status">Patient Status</label>
			<?php
			$inselected = '';
			$outselected = '';
			$checked = 'checked="checked"';
			if(!$patient_type1) $outselected = $checked;
			else if($patient_type1) $inselected = $checked;
			?>
			<input type="radio" name="PatientStatus" value="0" id="Out-Patient"
			<?php echo $outselected; ?> /> Out-Patient <input type="radio"
				name="PatientStatus" value="1" id="In-Patient"
				<?php echo $inselected; ?> /> In-Patient
		</p>

		<p>
		<?php
		if ($patient_type1)
		{
			$discharge_date = mysql_real_escape_string($_POST['discharge_date']);
			if($discharge_date==1)
			{
				global $wpdb;
				$tablename = $wpdb->prefix;
				$query2 = 'SELECT R.roomno FROM ' . $tablename. 'in_patients R
	WHERE R.userid="' .$patient_id.'"'; 
				$roomno = $wpdb->get_results($query2, ARRAY_A);
				$chosenRoom = $roomno[0]['roomno'];
				$query3 = 'SELECT N.userid FROM ' . $tablename. 'nurses N
	WHERE N.roomno="' .$chosenRoom.'"'; 
				$NurseSel = $wpdb->get_results($query3, ARRAY_A);
				$FreeNurse = new UpdateDatabaseOptions('nurses');
				if(!$FreeNurse->updateRow(
				array('available' => 1,
						'roomno' => 310),
				array('userid' => $NurseSel[0]['userid']),
				array('%d', '%d'),
				array('%s')))
				$error++;
				//$get_date[0][current_date]
				$query4 = 'SELECT current_date';
				$get_date = $wpdb->get_results($query4, ARRAY_A);
				$In_Patients=new UpdateDatabaseOptions('in_patients');
				if(!$In_Patients->updateRow(
				array('discharge_date'=>"2014-12-01"),
				array('userid'=>$patient_id),
				array('%s'),
				array('%s')))
				$error++;
					
			}
			?>
			<label for="discharge_date">Discharge</label>
			<?php
			$yesselected = '';
			$noselected = '';
			$checked = 'checked="checked"';
			if($patient_type1) $yesselected = $checked;
			else if(!$patient_type1) $noselected = $checked;
			?>
			<input type="radio" name="discharge_date" value="1" id="YES"
			<?php echo $yesselected; ?> /> Yes<input type="radio"
				name="discharge_date" 'value="0" id="NO" <?php echo $noselected; ?> />
			No
			<?php
		}
		?>
		</p>

		<p>
			<input type="submit" name="psubmit" id="psubmit" value="" class="btm submit_btm" onclick="return editTreatmentValidate();"/>
		</p>
	</form>
</div>
		<?php } else {
			header("Location:" . site_url());
		}
		get_footer();
}