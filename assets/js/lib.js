function byId(id){
	return document.getElementById(id);
}

function addLiveEvent(trigger, selector, cb){
    document.addEventListener(trigger, function(e){
        var els = document.querySelectorAll(selector);

        if(els){
            el = isChildOf(e.target, els);

            if(el){
                cb.call(datepicker);
            }
        }
    });
}

function isChildOf(element, parent){
	var parentElement = false;

	do{
		if(element == parent){
			parentElement = parent;
			break;
		}
		else if(typeof parent.length !== 'undefined'){
			for (i = 0; i <	parent.length; i++) {
				if(parent[i] == element){
					parentElement = parent[i];
				}
			}
		}
		
		element = element.parentNode;
	}while(element.parentNode);

	return parentElement;
}

function pad(value, width, pad, type){
	type = type || 'left';
	pad = pad || '0';
	
	value = String(value);
	pad = String(pad);
	
	if(value.length < width){
		pad = Array(width - value.length + 1).join(pad);
		
		if(type == 'left'){
			value = pad + value;
		}
		else{
			value = value + pad;
		}
	}

	return value;
}