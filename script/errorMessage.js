function showNotify(msg, mode){
				
	var notify = document.getElementById('notify');
         
    notify.innerHTML = msg;
    
    switch(mode){
        case 1: notify.style.background = '#b30000';    // Red shade for erros.
            break;

        case 2: notify.style.background = '#009900';    // Green shade for success.
            break;

        case 3: notify.style.background = '#006bb3';    // Blue shade for information.
            break;
    }

    if(notify.style.visibility == 'hidden'){
        notify.style.visibility = 'visible';
        notify.style.opacity = '1';
    }

    setTimeout(function(){
        if (notify.style.visibility == 'visible') {
            notify.style.visibility = 'hidden';
            notify.style.opacity = '0';                           
        }
    }, 3000);
}

function setDefault(img){
    img.src = "uploads/dp/blank.jpg";
}