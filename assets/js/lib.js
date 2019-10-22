function byId(id){ return document.getElementById(id); }

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

Lightbox = {
	id: {			
		overlay: 'lb-overlay',
		outter: 'lb-outter',
		inner: 'lb-inner',
		box: 'lb-box',
		close: 'lb-close',
		img: 'lb-image'
	},
	
	createWindow: function(){
		if(byId(this.getId('overlay')) === null){
			var $this = this;

			var overlay = document.createElement('div');
			overlay.id = this.getId('overlay');
			overlay.style.display = 'none';

			var inner = '<div id="' + this.getId('overlay') + '">' +
					'<div id="' + this.getId('outter') + '">' +
						'<div id="' + this.getId('inner') + '">' +
							'<div id="' + this.getId('box') + '"></div>' +
						'</div>' +
					'</div>' +
					'<span id="' + this.getId('close') + '">X</span>' +
				'</div>';
			overlay.innerHTML = inner;

			document.body.appendChild(overlay);
			
			overlay.addEventListener('click', function(ev){
				$this.onClick(ev);
			});
			window.addEventListener("keydown", function(ev){
				var escapeKeys = [27, 8];
				
				if(escapeKeys.indexOf(ev.keyCode) !== 1){
					$this.close();
				}
			});
		}
	},
	
	onClick: function(ev){			
		switch(ev.target.id){
			case this.getId('overlay'):
			case this.getId('inner'):
			case this.getId('close'):
				this.close();
				break
		}
	},
	
	open: function(options){
		this.createWindow();
		this.empty();

		var overlay = byId(this.getId('overlay'));
		var box = byId(this.getId('box'));

		(typeof options.content === 'string') ? box.innerHTML = options.content : box.appendChild(options.content);

		overlay.style.display = 'block';
	},

	empty: function(){
		byId(this.getId('box')).innerHTML = '';
	},

	hide: function(){ byId(this.getId('overlay')).style.display = 'none'; },

	close: function(ev){
		this.hide();
		this.empty();
	},

	getId: function(k){ return this.id[k]; }
};

Gallery = {
	create: function(linkSelector){
		var $this = this;
		var links = document.querySelectorAll(linkSelector);

		for(var i = 0; i < links.length; i++){
			links[i].addEventListener('click', function(){
				$this.open(this);
			});
		}
	},
	
	open: function(link){
		Lightbox.open({
			content: this.createImageFromLink(link)
		});
	},
	
	createImageFromLink: function(link){
		var img = document.createElement('img');
		var data = this.getLinkData(link);
		img.id = Lightbox.getId('img');
		img.src = data.src;

		if(typeof data.alt !== 'undefined'){
			img.alt = data.alt;
		}
				
		return img;
	},
	
	getLinkData: function(link){
		data = {};
		linkImage = link.getElementsByTagName('img');
		
		if(typeof link.dataset.src !== 'undefined'){
			data.src = link.dataset.src;
		}
		
		if(typeof link.dataset.alt !== 'undefined'){
			data.alt = link.dataset.alt;
		}
		
		if(linkImage.length != 0){
			linkImage = linkImage[0];
			
			if(typeof data.src === 'undefined'){
				data.src = linkImage.src;
			}
			
			if(typeof data.alt === 'undefined' && typeof linkImage.alt !== 'undefined'){
				data.alt = linkImage.alt;
			}
		}
		else if(link.tagName === 'img'){
			if(typeof data.src === 'undefined'){
				data.src = link.src;
			}
			
			if(typeof data.alt === 'undefined' && typeof link.alt !== 'undefined'){
				data.alt = link.alt;
			}
		}
		
		return data;
	}
};