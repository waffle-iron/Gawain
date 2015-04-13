function jierarchy(str_DepFilePath) {
	
	// The file containing the dependencies of all the available libraries
	this.depSource = {};
	
	// The array of JavaScript paths
	this.JsPaths = [];
	
	// The array of JavaScript paths
	this.CssPaths = [];
	

	
	// Get the JSON dependencies file
	var obj_XHR = new XMLHttpRequest();
	obj_XHR.overrideMimeType('application/json');
	
	obj_XHR.open('GET', str_DepFilePath, false);
	obj_XHR.send();
	this.depSource = JSON.parse(obj_XHR.responseText);
	
}




jierarchy.prototype.load = function(arr_LibraryNames) {
	var self = this;
	
	var str_LibName;
	var int_LibLength = arr_LibraryNames.length;
	var int_LibCounter;
	var obj_CurrentNode;
	
	var int_PathCounter;
	
	for (int_LibCounter = 0; int_LibCounter < int_LibLength; int_LibCounter++) {
		str_LibName = arr_LibraryNames[int_LibCounter];
		obj_CurrentNode = this.depSource[str_LibName];
		
		
		// Recurse function
		if (obj_CurrentNode.dependencies.length > 0) {
			self.load(obj_CurrentNode.dependencies);
		}
		
		
		if (obj_CurrentNode.path.js.length > 0) {
			for (int_PathCounter = 0; int_PathCounter < obj_CurrentNode.path.js.length; int_PathCounter++) {
				if (obj_CurrentNode.path.js[int_PathCounter] != '') {
					this.JsPaths.push(obj_CurrentNode.path.js[int_PathCounter]);
				}
			}
		}
		
		if (obj_CurrentNode.path.css.length > 0) {
			for (int_PathCounter = 0; int_PathCounter < obj_CurrentNode.path.css.length; int_PathCounter++) {
				if (obj_CurrentNode.path.css[int_PathCounter] != '') {
					this.CssPaths.push(obj_CurrentNode.path.css[int_PathCounter]);
				}
			}
		}
		
	}
	
	this.JsPaths = this.JsPaths.getUnique();
	this.CssPaths = this.CssPaths.getUnique();
}



// Adding getUnique function to array prototype to get unique values
Array.prototype.getUnique = function(){
	   var u = {}, a = [];
	   for(var i = 0, l = this.length; i < l; ++i){
	      if(u.hasOwnProperty(this[i])) {
	         continue;
	      }
	      a.push(this[i]);
	      u[this[i]] = 1;
	   }
	   return a;
	}