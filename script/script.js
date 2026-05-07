var btnLogin = document.getElementById('btnLogin');
var loginOverlay = document.getElementById('login-overlay');
var btnCancel = document.getElementById('btnCancel');

btnLogin.addEventListener("click", showOverlay);

function showOverlay(){
	loginOverlay.style.display = "block";
	loginOverlay.style.backgroundColor = "rgba(0,0,0,0.5)";
	loginOverlay.style.position = "absolute";
	loginOverlay.style.height = "100%";
	loginOverlay.style.width = "100%";
	loginOverlay.style.zIndex = "3";
	document.body.style.overflow = "hidden";
}

loginOverlay.addEventListener("click", hideOverlay);

function hideOverlay(){
	if (event.target === this) {
		loginOverlay.style.display = "none";
		document.body.style.overflow = "visible";
	}
}

btnCancel.addEventListener("click", hideOverlay);
