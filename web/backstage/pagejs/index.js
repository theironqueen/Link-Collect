$(document).ready(function(){
	var tabData;
	init();
	//console.log($("#rank-box-1").find("ul.link-rank");
	//初始化
	function init(){
		var success = function(data,textStatus){
			var msgObj = eval("("+data+")");
			var result = msgObj.result;
			var msg = msgObj.msg;

			if(result=="1"){
				Tool.alertMessage("LC_notify_box","success","成功","顶层标签获取成功");
				totalInitHelp(msg);
			}else if(result=="0"){
				Tool.alertMessage("LC_notify_box","error","失败",msg);
			}else{
				alert("服务器传值错误");
			}
		};
		var beforeSend = Tool.sendMessageParam.beforeSend("content");
		var error = Tool.sendMessageParam.error("LC_notify_box","服务器连接错误");
		var complete = Tool.sendMessageParam.complete("content");

		var url = "../php/index.php?page=1&event=0";
		var data = {};
		var ajaxParam = Tool.sendMessage(url,data,beforeSend,success,error,complete);
		$.ajax(ajaxParam);
	}

	function totalInitHelp(data){
		//console.log(data.length);
		MyPageData.setIndexTabData(data);
		var rowHtml = singleRowCreate();
		var $content = $("#content");
		console.log($content);
		var $row;
		$content.append($row);
		//开始数据循环
		for(var i=0;i<data.length;i++){
			if(i%3==0){
				$row = $(rowHtml);
				$content.append($row);
			}
			var boxHtml = singleBoxCreate(data[i].tab_id,data[i].tab_name);
			var $box = $(boxHtml);
			$row.append($box);
			//开始ajax操作
			//singleInitHelp(data[i].tab_id,data[i].tab_name);
		}
		setTimeout(singleInit,100);
	}
	function singleInit(){
		var data = MyPageData.getIndexTabData();
		for(var i=0;i<data.length;i++){
			//开始ajax操作
			singleInitHelp(data[i].tab_id,data[i].tab_name);
		}
	}
	function singleInitHelp(tabId,tabName){
		var success = function(data,textStatus){
			var msgObj = eval("("+data+")");
			var result = msgObj.result;
			var msg = msgObj.msg;

			if(result=="1"){
				Tool.alertMessage("LC_notify_box","success","成功",tabName+"标签数据获取成功");
				//
				var $ul = $("#rank-box-"+tabId).find("ul.link-rank");
				if(msg.length==0){
					$li = $("<li>该标签下无链接</li>");
					$ul.append($li);
				}
				//console.log($ul.html());
				for(var i=0;i<msg.length;i++){
					var html = singleRankCreate(msg[i]);
					var $li = $(html);
					$ul.append($li);
				}

			}else if(result=="0"){
				Tool.alertMessage("LC_notify_box","error","失败",msg);
			}else{
				alert("服务器传值错误");
			}
		};
		var beforeSend = Tool.sendMessageParam.beforeSend("rank-box-"+tabId);
		var error = Tool.sendMessageParam.error("LC_notify_box","服务器连接错误");
		var complete = Tool.sendMessageParam.complete("rank-box-"+tabId);

		var url = "../php/index.php?page=1&event=1";
		var data = {};
		data.tab_id = tabId;
		var ajaxParam = Tool.sendMessage(url,data,beforeSend,success,error,complete);
		$.ajax(ajaxParam);
	}

	function singleRowCreate(){
		var html = "<div class='row'></div>"
		return html;
	}
	function singleBoxCreate(boxId,boxName){
		var html = "";
		html = html 
		+ "<div class='box col-md-4' id='rank-box-"+boxId+"'>"
	        +"<div class='box-inner'>"
	            +"<div class='box-header well' data-original-title=''>"
	                +"<h2><i class='glyphicon'></i> <span>"+boxName+"</span></h2>"
	            +"</div>"
	            +"<div class='box-content'>"
	            	+"<div class='LC_page_loader hide'>"
                    	+"<div class='LC_spinner'></div>"
                	+"</div>"
	                +"<ul class='link-rank'>"
	                	+"<li>"
	                        +"<span class='rank-number'>No.</span>"
	                        +"<span class='link-title'>链接名</span>"
	                        +"<span class='click-number'>click</span>"
	                    +"</li>";

	    html = html + "</ul>"
	            +"</div>"
	        +"</div>"
	    +"</div>";	                
		return html;
	}
	function singleRankCreate(data){
		var html =  "<li>"
	                    +"<span class='rank-number'>"+data.rank_number+"</span>"
	                    +"<a href='"+data.link_address+"' title='"+data.link_name+"' target='_blank'>"+data.link_name+"</a>"
	                    +"<span class='click-number'>"+data.link_click+"</span>"
	                + "</li>";
	    return html;
	}
});