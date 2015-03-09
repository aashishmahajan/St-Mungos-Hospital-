/*
 * jQuery FormValidator
 */

var MyValidator = (function($) {
	var validationObj = {};

	required = function(key, val) {
		var obj = $("#" + key);
		if (obj.val() == '') {
			obj.addClass('error');
			validationObj[key] = "Please enter " + val.msg;
		} else {
			obj.removeClass('error');
			delete validationObj[key];
		}
	};

	selectRequired = function(key, val) {
		var obj = $("#" + key);
		if (obj.val() == '') {
			obj.addClass('error');
			validationObj[key] = val.msg;
		} else {
			obj.removeClass('error');
			delete validationObj[key];
		}
	};

	var rangeOfCharacter = function(key, value) {
		var fieldLength = $("#" + key).val().length;
		if (!validationObj.hasOwnProperty(key)) {
			if (fieldLength > value.maxlength) {
				$("#" + key).addClass('error');
				validationObj[key] = value.msg + " should not be greater than "
						+ value.maxlength;
			} else {
				$("#" + key).removeClass('error');
				delete validationObj[key];
			}
		}
	};

	var checkEmail = function(key, value) {
		var pattern = /\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
		if (!validationObj.hasOwnProperty(key)) {
			if (!pattern.test($("#" + key).val())) {
				$("#" + key).addClass('error');
				validationObj[key] = value.msg + " is invalid";
			} else {
				$("#" + key).removeClass('error');
				delete validationObj[key];
			}
		}
	};

	var confirmEmail = function(key, value) {
		if ($("#" + key).val() != $("#" + value.confirm_email).val()) {
			$("#" + key).addClass('error');
			validationObj[key] = value.msg + " is invalid";
		} else {
			$("#" + key.id).removeClass('error');
			delete validationObj[key];
		}
	};

	var checkMinLength = function(key, value) {
		var fieldLength = $("#" + key).val().length;
		if (!validationObj.hasOwnProperty(key)) {
			if (fieldLength < value.minlength) {
				$("#" + key).addClass('error');
				validationObj[key] = value.msg + " should be greater than "
						+ value.minlength;
			} else {
				$("#" + key).removeClass('error');
				delete validationObj[key];
			}
		}
	};

	var isMatchedPattern = function(key, value) {
		if (!validationObj.hasOwnProperty(key)) {
			if (!value.pattern.test($("#" + key).val())) {
				$("#" + key).addClass('error');
				validationObj[key] = value.msg + " is invalid";
			} else {
				$("#" + key).removeClass('error');
				delete validationObj[key];
			}
		}
	};
	isChecked = function(key, val) {
		var obj = $('input[name="' + key + '"]');
		if (!obj.is(":checked")) {
			// obj.addClass('error');
			validationObj[key] = "Select " + val.msg;
		} else {
			// obj.removeClass('error');
			delete validationObj[key];
		}

	};

	init = function(settings) {
		validationObj = {};
		var flag = 0;
		$.each(settings, function(i, v) {
			if (v.hasOwnProperty('required') == true && v.required == true)
				required(i, v);
			if (v.hasOwnProperty('selectRequired') == true
					&& v.selectRequired == true)
				selectRequired(i, v);
			if (v.hasOwnProperty('maxlength') == true)
				rangeOfCharacter(i, v);
			if (v.hasOwnProperty('email') == true && v.email == true)
				checkEmail(i, v);
			if (v.hasOwnProperty('confirm_email') == true)
				confirmEmail(i, v);
			if (v.hasOwnProperty('pattern') == true)
				isMatchedPattern(i, v);
			if (v.hasOwnProperty('minlength') == true)
				checkMinLength(i, v);
			if (v.hasOwnProperty('isChecked') == true)
				isChecked(i, v);
		});
		$("form p").find('p.error').remove();
		$("form table tbody tr td").find('p.error').remove();
		$.each(validationObj, function(key, value) {
			if (!flag)
				flag = 1;
			$("#" + key).parent().append("<p class='error'>" + value + "</p>");
		});
		return !flag;
	};
	return {
		init : init
	};

})(jQuery);

function loginValidate() {
	var obj = {
		uname : {
			required : true,
			msg : "username"
		},
		pwd : {
			required : true,
			msg : "password"
		}
	};
	return MyValidator.init(obj);
}

function makeAppointmentValidate() {
	var obj = {
		datepicker : {
			required : true,
			msg : "date"
		}
	};
	return MyValidator.init(obj);
}

function editTreatmentValidate() {
	var obj = {
		diagnosis : {
			required : true,
			msg : "diagnosis"
		},
		pres_med : {
			required : true,
			msg : "prescribed medicines"
		}
	};
	return MyValidator.init(obj);
}

function registerValidate() {
	var obj = {
		runame : {
			required : true,
			msg : "username",
			minlength : 6,
			maxlength : 10
		},
		rpnpwd : {
			required : true,
			msg : "password",
			minlength : 6,
			maxlength : 50
		},
		rpcnpwd : {
			required : true,
			msg : "password"
		},
		rpname : {
			required : true,
			msg : "name",
			pattern : /^[a-zA-Z]+[ ]*[a-zA-Z]*$/
		},
		rcontact : {
			required : true,
			msg : "contact number",
			pattern : /^[0-9]{10}$/
		},
		raddress : {
			required : true,
			msg : "address"
		},
		remail : {
			required : true,
			msg : "email",
			email : true
		},
		rage : {
			required : true,
			msg : "age",
			maxlength : 3,
			pattern : /^[0-9]+$/
		}
	};
	if (MyValidator.init(obj)) {
		if (jQuery("#rpcnpwd").val() != jQuery("#rpnpwd").val()) {
			jQuery("#rpcnpwd").parent().append(
					"<p class='error'>Passwords don't match</p>");
			return false;
		} else {
			jQuery("#rpcnpwd").parent().find("p.error").remove();
			return true;
		}
	} else {
		return false;
	}
}

function patientProfileValidate() {
	var obj = {
		pname : {
			required : true,
			msg : "username"
		},
		contact : {
			required : true,
			msg : "contact number",
			pattern : /^[0-9]{5,10}$/
		},
		address : {
			required : true,
			msg : "password"
		},
		email : {
			required : true,
			msg : "email",
			email : true
		},
		age : {
			required : true,
			msg : "age",
			maxlength : 3,
			pattern : /^[0-9]+$/
		}
	};
	if (MyValidator.init(obj)) {
		if (jQuery("#pnpwd").val() != '' || jQuery("#pcnpwd").val() != '') {
			var obj2 = {
				pnpwd : {
					required : true,
					msg : "new password",
					minlength : 6,
					maxlength : 50
				},
				pcnpwd : {
					required : true,
					msg : "to confirm password"
				}
			};
			if (MyValidator.init(obj2)) {
				if (jQuery("#pnpwd").val() != jQuery("#pcnpwd").val()) {
					jQuery("#pcnpwd").parent().append(
							"<p class='error'>Passwords don't match</p>");
					return false;
				} else {
					jQuery("#pcnpwd").parent().find("p.error").remove();
					return true;
				}
			} else {
				return false;
			}
		}
	} else {
		return false;
	}
}

function doctorProfileValidate() {
	var obj = {
		dname : {
			required : true,
			msg : "username"
		},
		contact : {
			required : true,
			msg : "contact number",
			pattern : /^[0-9]{5,10}$/
		},
		address : {
			required : true,
			msg : "password"
		},
		email : {
			required : true,
			msg : "email",
			email : true
		},
		qualification : {
			required : true,
			msg : "qualification"
		},
		experience : {
			required : true,
			msg : "experience"
		}
	};
	if (MyValidator.init(obj)) {
		if (jQuery("#dnpwd").val() != '' || jQuery("#dcnpwd").val() != '') {
			var obj2 = {
				dnpwd : {
					required : true,
					msg : "new password",
					minlength : 6,
					maxlength : 50
				},
				dcnpwd : {
					required : true,
					msg : "to confirm password"
				}
			};
			if (MyValidator.init(obj2)) {
				if (jQuery("#dnpwd").val() != jQuery("#dcnpwd").val()) {
					jQuery("#dcnpwd").parent().append(
							"<p class='error'>Passwords don't match</p>");
					return false;
				} else {
					jQuery("#dcnpwd").parent().find("p.error").remove();
					return true;
				}
			} else {
				return false;
			}
		}
	} else {
		return false;
	}
}

function nurseProfileValidate() {
	var obj = {
		nname : {
			required : true,
			msg : "username"
		},
		contact : {
			required : true,
			msg : "contact number",
			pattern : /^[0-9]{5,10}$/
		},
		address : {
			required : true,
			msg : "password"
		},
		email : {
			required : true,
			msg : "email",
			email : true
		}
	};
	if (MyValidator.init(obj)) {
		if (jQuery("#npwd").val() != '' || jQuery("#ncnpwd").val() != '') {
			var obj2 = {
				npwd : {
					required : true,
					msg : "new password",
					minlength : 6,
					maxlength : 50
				},
				ncnpwd : {
					required : true,
					msg : "to confirm password"
				}
			};
			if (MyValidator.init(obj2)) {
				if (jQuery("#npwd").val() != jQuery("#ncnpwd").val()) {
					jQuery("#ncnpwd").parent().append(
							"<p class='error'>Passwords don't match</p>");
					return false;
				} else {
					jQuery("#ncnpwd").parent().find("p.error").remove();
					return true;
				}
			} else {
				return false;
			}
		}
	} else {
		return false;
	}
}

jQuery(document).ready(function() {
	jQuery("a#pay").click(function() {
		jQuery(this).attr('id', 'paid');
		jQuery(this).parent().html('Paid');
	});

	jQuery("div.leftnav a").each(function() {
		if (this.href == window.location.href) {
			jQuery(this).parents("div.menu").removeClass('two');
			jQuery(this).parents("div.menu").addClass('one');
		}
	});
});

function changeRoomType(roomType, formName) {
	jQuery("form[name='roomtype" + formName + "']").submit();
}