var Data = (function(){
	var imageNumber,imageBoxNumber;
	var timeOut;
	var tabId;
	var tabList = null;
	var pageClass = ["col-sm-pull-6 col-md-pull-4 col-lg-pull-3",
					"col-md-pull-4 col-lg-pull-3",
					"col-lg-pull-3"];
	var totalImage = 63;
	function getTabListName(list,id){
		var result = "";
		for(var i=0;i<list.length;i++){
			if(list[i].tabId!=id){
				if(list[i].subTab==null) continue;
				var temp = getTabListName(list[i].subTab,id);
				if(temp == "")	continue;
				result = list[i].tabName+"/"+temp;
				return result;
			}else{
				result = list[i].tabName;
				return result;
			}
		}
		return result;
	}
	return {
		imgNumber:{
			get:function(){
				return imageNumber;
			},
			set:function(number){
				imageNumber = number;
			},
			getMax:function(){
				return totalImage;
			}
		},
		imgBoxNumber:{
			get:function(){
				return imageBoxNumber;
			},
			set:function(number){
				imageBoxNumber = number;
			}
		},
		backTimeOut:{
			get:function(){
				return timeOut;
			},
			set:function(time){
				timeOut = time;
			}
		},
		curTabId:{
			get:function(){
				return tabId;
			},
			set:function(id){
				tabId = id;
			}
		},
		navTabList:{
			get:function(){
				return tabList;
			},
			set:function(list){
				var temp = eval("("+list+")");
				tabList = temp;
			},
			getAllParentName:function (id){
				var result = "";
				//console.log("id:"+id);
				if(id==0) result = "无";
				else result = getTabListName(tabList,id);
				return result;
			}
		},
		getPageClass:function(page){
			return pageClass[page-1];
		}
	};
})();