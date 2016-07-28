function _(el)
{
	return document.getElementById(el);
}

function vertScrollPopup()
{
	var appear = _('profile');
	if((window.pageYOffset ) > document.body.offsetHeight){
		appear.style.left = 0;
		appear.style.webkitTransition = "left 0.7s ease-in-out 0s";
        appear.style.transition = "left 0.7s ease-in-out 0s"; 
	}else {
		appear.style.webkitTransition = "left 0.7s ease-in-out 0s";
		appear.style.transition = "left 0.7s ease-in-out 0s";
		appear.style.left = "-20%";
	}
}

window.onscroll = vertScrollPopup;