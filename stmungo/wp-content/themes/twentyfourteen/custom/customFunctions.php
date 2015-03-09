<?php

function getMedicinesCost( $prescriptions ) {
	$medicines = explode(',', $prescriptions);
	$costs = new UpdateDatabaseOptions('pharmacy');
	$totalCost = 0;
	foreach($medicines as $medicine) {
		$cost = $costs->selectValue(array('cost'), array('medicine_name' => $medicine));
		$totalCost += $cost[0]['cost'];
	}
	return $totalCost;
}

function calculateNumberOfDaysInRoom($admitDate, $dischargeDate) {
	$admitDate = new DateTime($admitDate);
	$dischargeDate = new DateTime($dischargeDate);
	$interval = $admitDate->diff($dischargeDate);
	return $interval->format('%R%a');
}

function getRoomRent($admitDate, $dischargeDate, $rent) {
	$numberOfDays = calculateNumberOfDaysInRoom($admitDate, $dischargeDate);
	return ($numberOfDays * $rent);
}

function getTotalBillAmount($admitDate, $dischargeDate, $rent, $prescriptions) {
	$roomRent = 0;
	if($admitDate != '' && $dischargeDate != '' && $rent != '') {
		$roomRent = getRoomRent($admitDate, $dischargeDate, $rent);
	}
	$medsCost = 0;
	if($prescriptions != '')
		$medsCost = getMedicinesCost($prescriptions);
	return $roomRent + $medsCost;
}