function DataTable(element, data){
	this.element;
	this.row = '';
	
	this.data = [];
	this.dataColumns = [];
	this.dataTypes = [];
	this.dataOrders = [];

	this.construct = function(element, data){
		this.element = element;
	
		var dtCols = this.element.getElementsByClassName('dt-head')[0].getElementsByClassName('dt-cell');
		for(var i = 0; i < dtCols.length; i++){
			this.dataColumns[i] = dtCols[i].dataset.column;
			this.dataTypes[i] = dtCols[i].dataset.type;
			this.dataOrders[i] = 'none';
			
			if(this.dataTypes[i] != 'action'){
				(function(i){
					dtCols[i].addEventListener('click', function(){ this.sortBy(i, this.dataTypes[i], this.dataOrders[i]); }.bind(this));
				}.bind(this))(i);
			}
		}
		
		this.row = element.getElementsByClassName('dt-body')[0].innerHTML;
		this.addData(data);
	};
	
	this.render = function(e){
		body = element.getElementsByClassName('dt-body')[0];
		body.innerHTML = '';
		var rows = '';
		
		for(var i = 0; i < this.data.length; i++){
			var row = this.row;

			for(var k in this.data[i]){
				var find = ':' + k + ':';
				var replace = new RegExp(find, 'g');
				row = row.replace(replace, this.data[i][k]);
			}
			
			rows += row;
		}
		
		this.element.getElementsByClassName('dt-body')[0].innerHTML = rows;
	};
	
	this.addData = function(data){
		for(var i = 0; i < data.length; i++){
			for(var k in data[i]){
				var index = this.dataColumns.indexOf(k);
				
				if(index > -1){
					if(this.dataTypes[index] == 'string-translate'){
						data[i][k] = translations[data[i][k]];
					}
				}
			}

			this.data.push(data[i]);
		}
	};
	
	this.sortBy = function(index, type, order){
		for(var i = 0; i < this.dataOrders.length; i++){
			this.dataOrders[i] = 'none';
		}
		
		if(order == 'asc'){
			this.dataOrders[index] = 'desc';
		}
		else if(order == 'desc'){
			this.dataOrders[index] = 'asc';
		}
		else{
			this.dataOrders[index] = 'asc';
		}
		
		order = this.dataOrders[index];
		key = this.dataColumns[index];

		this.data.sort(function(a,b){
			if(order == 'asc'){
				if(a[key] < b[key]){
					return -1;
				}
				else if(a[key] > b[key]){
					return 1;
				}
				else{
					return 0;
				}
			}
			else{
				if(b[key] < a[key]){
					return -1;
				}
				else if(b[key] > a[key]){
					return 1;
				}
				else{
					return 0;
				}
			}
		});

		this.render();
	}
	
	this.construct(element, data);
	this.render();
}