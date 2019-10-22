Nav = {
	toggles: {
		'nav1': 'nav1-toggle',
		'nav2': 'nav2-toggle'
	},
	init: function(){
		for(var nav in Nav.toggles){
			var el = byId(Nav.toggles[nav]);
			
			if(el != null){
				Nav.addToggleEvent(el, nav);
			}
		}
	},
	addToggleEvent: function(el, nav){
		el.addEventListener('click', function(){
			Nav.toggle(nav);
		});
	},
	toggle: function(nav){
		Nav.reset(nav);
		var navEl = byId(nav);
		navEl.className = (navEl.className === 'desktop') ? '' : 'desktop'; 
	},
	reset: function(nav){
		var navEl = (nav === 'nav1') ? byId('nav2') : byId('nav1');
		navEl.className = 'desktop';
	}
};

Initializer = {
	init: function(){
		Nav.init();
		this.initGallery();
	},
	
	initGallery: function(){
		if(byId('gallery') !== null){
			Gallery.create('#gallery .gallery-image');
		}
	}
};

window.addEventListener('load', function(){
	Initializer.init();
});