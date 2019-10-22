var Datepicker = {
	elementInput: null,
	elementDp: null,
	elementCalendar: null,
	date: null,

	open: function(e){
		this.elementInput = e.target;
		
		if(Date.parse(this.elementInput.value)){
			this.date = new Date(this.elementInput.value);
		}
		else{
			this.date = new Date();
		}

		this.elementDp.style.top = this.elementInput.offsetTop + this.elementInput.offsetHeight;
		this.elementDp.style.left = this.elementInput.offsetLeft;
		
		this.render(0);
		this.elementDp.style.display = 'block';
	},

	close: function(){
		this.elementDp.style.display = 'none';
	},
	
	render: function(monthModifier){
		this.elementCalendar.innerHTML = '';

		this.date.setMonth(this.date.getMonth() + monthModifier);
		document.getElementById('dp-current').innerHTML = (this.date.getMonth() + 1) + ' ' + this.date.getFullYear();

		this.date.setDate(1);
		var startingDay = this.date.getDay();
		if(startingDay == 0){
			startingDay = 7;
		}
		var totalDays = new Date(this.date.getFullYear(), this.date.getMonth() +1, 0).getDate();
		
		var countDay = 1;
		var monthStart = false;

		for(var y = 1; y < 7; y++){
			var week = document.createElement('tr');
			
			for(var x = 1; x < 8; x++){
				var day = document.createElement('td');

				if((monthStart || startingDay == x)
				&& countDay <= totalDays){
					day.className = 'dp-day';
					day.innerHTML = countDay;
					(function(countDay){
						day.addEventListener('click', function(){ this.output(countDay, this.date.getMonth(), this.date.getFullYear()); }.bind(this)); 
					}.bind(this))(countDay);
					countDay++;
					monthStart = true;
				}
				
				week.appendChild(day);
			}

			this.elementCalendar.appendChild(week);
		}
	},
	
	output: function output(day, month, year){
		this.elementInput.value = year + '-' + pad((month + 1), 2, 0) + '-' + pad(day, 2, 0);
		this.close();
	},
	
	isClicked: function(e){
		if(!isChildOf(e.target, this.elementDp)
		&& e.target != this.elementInput){
			this.close();
		}
	},
	
	init: function(){
		var calendar = document.createElement('table');
		calendar.id = 'dp';
		calendar.style.display = 'none';
		
		var section = document.createElement('thead');
		var sub = document.createElement('tr');
		var labels = ['<<', '<', '', '>', '>>'];
		var events = [-12, -1, 0, 1, 12];
			
		for(i = 0; i < 5; i++){
			var subElement = document.createElement('th');
			
			if(labels[i] != ''){
				subElement.className = 'dp-select';
				subElement.innerHTML = labels[i];
				subElement.addEventListener('click', function(){ this.render(events[i]); }.bind(this));
			}
			else{
				subElement.id = 'dp-current';
				subElement.colSpan = 3;
			}
			
			sub.appendChild(subElement);
		}
			
		section.appendChild(sub);
				
		var sub = document.createElement('tr');
		var labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
		sub.id = 'dp-days';
		
		for(i = 0; i < labels.length; i++){
			var subElement = document.createElement('th');
			subElement.innerHTML = labels[i];
			sub.appendChild(subElement);
		}
				
		section.appendChild(sub);	
		calendar.appendChild(section);
		var section = document.createElement('tbody');
		calendar.appendChild(section);
		document.body.appendChild(calendar);
		
		this.elementDp = document.getElementById('dp');
		this.elementCalendar = Datepicker.elementDp.getElementsByTagName('tbody')[0];

		var dpInputs = document.getElementsByClassName('inp-date');
		for(i = 0; i < dpInputs.length; i++){
			dpInputs[i].addEventListener('focus', this.open.bind(this));
		}
		
		document.body.addEventListener('click', this.isClicked.bind(this));
	}
};

window.addEventListener('load', function(){
	Datepicker.init();
});