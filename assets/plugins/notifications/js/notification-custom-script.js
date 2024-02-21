  /* Default Notifications */
  function default_noti(txt) {
	Lobibox.notify('default', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		position: 'top right',
		msg: txt
	});
}

function info_noti(txt) {
	Lobibox.notify('info', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		position: 'top right',
		icon: 'bx bx-info-circle',
		msg: txt
	});
}
function info_noti(txt, img) {
	Lobibox.notify('info', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		position: 'top right',
		img: img, 
		msg: txt
	});
}

function warning_noti(txt) {
	Lobibox.notify('warning', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		position: 'top right',
		icon: 'bx bx-error',
		msg: txt
	});
}

function error_noti(txt) {
	Lobibox.notify('error', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		position: 'top right',
		icon: 'bx bx-x-circle',
		msg: txt
	});
}

function success_noti(txt) {
	Lobibox.notify('success', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		position: 'top right',
		icon: 'bx bx-check-circle',
		msg: txt
	});
}
/* Rounded corners Notifications */
function round_default_noti(txt) {
	Lobibox.notify('default', {
		pauseDelayOnHover: true,
		size: 'mini',
		rounded: true,
		delayIndicator: false,
		continueDelayOnInactiveTab: false,
		position: 'top right',
		msg: txt
	});
}

function round_info_noti(txt) {
	Lobibox.notify('info', {
		pauseDelayOnHover: true,
		size: 'mini',
		rounded: true,
		icon: 'bx bx-info-circle',
		delayIndicator: false,
		continueDelayOnInactiveTab: false,
		position: 'top right',
		msg: txt
	});
}

function round_warning_noti(txt) {
	Lobibox.notify('warning', {
		pauseDelayOnHover: true,
		size: 'mini',
		rounded: true,
		delayIndicator: false,
		icon: 'bx bx-error',
		continueDelayOnInactiveTab: false,
		position: 'top right',
		msg: txt
	});
}

function round_error_noti(txt) {
	Lobibox.notify('error', {
		pauseDelayOnHover: true,
		size: 'mini',
		rounded: true,
		delayIndicator: false,
		icon: 'bx bx-x-circle',
		continueDelayOnInactiveTab: false,
		position: 'top right',
		msg: txt
	});
}

function round_success_noti(txt) {
	Lobibox.notify('success', {
		pauseDelayOnHover: true,
		size: 'mini',
		rounded: true,
		icon: 'bx bx-check-circle',
		delayIndicator: false,
		continueDelayOnInactiveTab: false,
		position: 'top right',
		msg: txt
	});
}
/* Notifications With Images*/
function img_default_noti(txt, img) {
	Lobibox.notify('default', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		position: 'top right',
		img: img, //path to image
		msg: txt
	});
}

function img_info_noti(txt, img) {
	Lobibox.notify('info', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		icon: 'bx bx-info-circle',
		position: 'top right',
		img: img, //path to image
		msg: txt
	});
}

function img_warning_noti(txt, img) {
	Lobibox.notify('warning', {
		pauseDelayOnHover: true,
		icon: 'bx bx-error',
		continueDelayOnInactiveTab: false,
		position: 'top right',
		img: img, //path to image
		msg: txt
	});
}

function img_error_noti(txt, img) {
	Lobibox.notify('error', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		icon: 'bx bx-x-circle',
		position: 'top right',
		img: img, //path to image
		msg: txt
	});
}

function img_success_noti(txt, img) {
	Lobibox.notify('success', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		position: 'top right',
		icon: 'bx bx-check-circle',
		img: img, //path to image
		msg: txt
	});
}
/* Notifications With Images*/
function pos1_default_noti(txt) {
	Lobibox.notify('default', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		position: 'center top',
		size: 'mini',
		msg: txt
	});
}

function pos2_info_noti(txt) {
	Lobibox.notify('info', {
		pauseDelayOnHover: true,
		icon: 'bx bx-info-circle',
		continueDelayOnInactiveTab: false,
		position: 'top left',
		size: 'mini',
		msg: txt
	});
}

function pos3_warning_noti(txt) {
	Lobibox.notify('warning', {
		pauseDelayOnHover: true,
		icon: 'bx bx-error',
		continueDelayOnInactiveTab: false,
		position: 'top right',
		size: 'mini',
		msg: txt
	});
}

function pos4_error_noti(txt) {
	Lobibox.notify('error', {
		pauseDelayOnHover: true,
		icon: 'bx bx-x-circle',
		size: 'mini',
		continueDelayOnInactiveTab: false,
		position: 'bottom left',
		msg: txt
	});
}

function pos5_success_noti(txt) {
	Lobibox.notify('success', {
		pauseDelayOnHover: true,
		size: 'mini',
		icon: 'bx bx-check-circle',
		continueDelayOnInactiveTab: false,
		position: 'bottom right',
		msg: txt
	});
}
/* Animated Notifications*/
function anim1_notitxt() {
	Lobibox.notify('default', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		position: 'center top',
		showClass: 'fadeInDown',
		hideClass: 'fadeOutDown',
		width: 600,
		msg: txt
	});
}

function anim2_noti(txt) {
	Lobibox.notify('info', {
		pauseDelayOnHover: true,
		icon: 'bx bx-info-circle',
		continueDelayOnInactiveTab: false,
		position: 'center top',
		showClass: 'bounceIn',
		hideClass: 'bounceOut',
		width: 600,
		msg: txt
	});
}

function anim3_noti(txt) {
	Lobibox.notify('warning', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		icon: 'bx bx-error',
		position: 'center top',
		showClass: 'zoomIn',
		hideClass: 'zoomOut',
		width: 600,
		msg: txt
	});
}

function anim4_noti(txt) {
	Lobibox.notify('error', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		icon: '',
		position: 'center top',
		showClass: 'lightSpeedIn',
		hideClass: 'lightSpeedOut',
		icon: 'bx bx-x-circle',
		width: 600,
		msg: txt
	});
}

function anim5_noti(txt) {
	Lobibox.notify('success', {
		pauseDelayOnHover: true,
		continueDelayOnInactiveTab: false,
		position: 'center top',
		showClass: 'rollIn',
		hideClass: 'rollOut',
		icon: 'bx bx-check-circle',
		width: 600,
		msg: txt
	});
}