<?php
/**
 * Template Name: Patient Make Appointment
 */
get_header();
/* Only if a patient is logged in, the following content will be displayed */
if(isset($_SESSION['userid']) && isset($_SESSION['usertype']) && $_SESSION['usertype'] == 2) {
	if(isset($_POST['makeappt'])) {
		/* Fetch the user entered values for department and date once the form is submiited */
		$department = $_POST['department'];
		$date = $_POST['apptDate'];

		/* Checking if the number of appointments for the selected department and date does not
		 * exceed 10. If it does, then the user is prompted to choose another date.
		 * If it does not exceed 10, then a new appointment is confirmed for the patient.
		 */
		$appointments = new UpdateDatabaseOptions('appointments');
		$numOfAppointments = $appointments->selectValue(array('COUNT(*)'), array('apt_date' => $date, 'dept_id' => $department));
		$message = '';
		foreach($numOfAppointments as $appointment) {
			if($appointment['COUNT(*)'] == 10) {
				$message = 'Sorry! The appointments for this date are not available. Please choose another date.';
			}
			else {
				$newAppointment = $appointments->insertRow(array('apt_date' => $date,
													'patient_id' => $_SESSION['userid'],
													'dept_id' => $department),
				array('%s', '%s', '%d'));
				/* Generate a new bill number for the newly created appointment */
				$bill = new UpdateDatabaseOptions('treatments');
				do {
					$billNumber = rand(1000, 999999);
					$checkBillNumber = $bill->selectValue(array('COUNT(*)'), array('bill_no' => $billNumber));
				} while($checkBillNumber[0]['COUNT(*)'] != 0);
				$generateBill = $bill->insertRow(array('bill_no' => $billNumber, 'apt_date' => $date), array('%d', '%s'));


				/* Pick a doctor to assign to this patient */
				$doctors = new UpdateDatabaseOptions('belongs_to');
				$departmentDoctors = $doctors->selectValue(array('userid'), array('dept_id' => $department));
				$chosenDoctor = array_rand($departmentDoctors);

				/* Assign the chosen doctor to the patient for the appoinment */
				$treats = new UpdateDatabaseOptions('treats');
				$treatment = $treats->insertRow(array('patient_id' => $_SESSION['userid'],
												'doctor_id' => $departmentDoctors[$chosenDoctor]['userid'],
												'bill_no' => $billNumber), array('%s', '%s', '%d'));
				if($newAppointment !== FALSE && $generateBill !== FALSE && $treatment !== FALSE) {
					$message = "Appointment confirmed!";
				}
				else {
					$message = "Sorry! Please try again.";
				}
			}
		}
	}
	global $post;
	$pageID = $post->ID;
	$departments = new UpdateDatabaseOptions('departments');
	$allDepartments = $departments->selectValue(array('*'), ''); ?>
<div class="leftnav"><?php get_sidebar('patient');?></div>
<div class="main_content">
<?php if($message != '') {?>
	<div class="infobar">
	<?php echo $message;?>
	</div>
	<?php }?>
	<h2>Make an Appointment</h2>
	<form name="makeAppt" action="" method="post">
		<label for="department">Select Department</label> <select
			name="department" id="department">
			<?php foreach($allDepartments as $department) {?>
			<option value="<?php echo $department['dept_id'];?>">
			<?php echo $department['dept_name'];?>
			</option>
			<?php }?>
		</select>
		<p>
			<label for="apptDate">Date <span class="red">*</span></label><input type="text" id="datepicker"
				name="apptDate" />
		</p>
		<p>
			<input type="submit" value="" id="makeappt" name="makeappt"
				class="btm submit_btm" onclick="return makeAppointmentValidate();"/>
		</p>
	</form>
</div>
			<?php }
			else {
				header("Location:" . site_url());
			}
			get_footer();