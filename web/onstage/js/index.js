$(document).ready(function(){
	console.log("hehe");

	//background 变化开始
	Data.imgNumber.set(1);
	Data.imgBoxNumber.set(0);
	background_change();
	//从服务器获取导航数据
	getTabList();
	//进入页面后模拟点击导航第一条
	setTimeout(function(){
		$("#nav-box").find("a").eq(0).click();
	},100);
	//导航点击颜色变化
	$("#nav-box li").click(function(){
		$("#nav-box li.active").removeClass("active");
		$(this).addClass("active");
	});
	$("#nav-box").jqueryAccordionMenu();
	//点击左侧导航条目事件
	$("#nav-box").find("a").click(function(){
		var id = $(this).attr("title");
		Data.curTabId.set(id);
		//获取数据
		navClickHelp(id);
		//console.log("db:"+$(this).attr("title"));
		//return false;
	});
	function navClickHelp(id){
		var url = "../php/index.php?page=0&event=0";
		var data = {};
		data.tab_id = id;
		var success = function(data,textStatus){
			var data = eval("("+data+")");
			var result = data.result;
			var tab_id = data.tab_id;
			//console.log("result:"+result+" getTabId:"+tab_id+" nowTabId:"+Data.curTabId.get());
			if(result=="1"&&tab_id==Data.curTabId.get()){
				createLinkPage(data.msg);
			}
		};
		var error = function(XMLHttpRequest, textStatus, errorThrown){
			alert("服务器请求失败");
		};
		var beforeSend = function(){};
		var complete = function(){};
		var $aj = Tool.sendMessage(url,data,beforeSend,success,error,complete);
		$.ajax($aj);
	}
	//点击单个链接事件
	$("#page-content").on("click",".link",function(){
		var linkId = $(this).attr("title");
		var url = "../php/index.php?page=0&event=4";
		var data = {};
		data.link_id = linkId;
		var success = function(data,textStatus){
		};
		var error = function(XMLHttpRequest, textStatus, errorThrown){
		};
		var beforeSend = function(){};
		var complete = function(){};
		var $aj = Tool.sendMessage(url,data,beforeSend,success,error,complete);
		$.ajax($aj);
	});
	//添加链接按钮点击事件
	$(".add-link").unbind("click").bind("click",function(){
		//console.log(Data.navTabList.getAllParentName(Data.curTabId.get()));
		if(Data.curTabId.get()==0){
			$("#help-modal").modal("show");
		}else{
			var tabName = 
				Data.navTabList.getAllParentName(Data.curTabId.get());
			$("#tab-name").val(tabName);
			$("#link-name-help").html("请输入链接名");
			$("#link-address-help").html("请输入链接地址");
			$("#link-name").val("");
			$("#link-address").val("");
			$("#link-modal").modal("show");
		}
	});
	$("#link-modal div.modal-footer button").eq(1).unbind("click").bind("click",function(){
		var linkName = $("#link-name").val();
		var linkAddress = $("#link-address").val();
		linkName = $.trim(linkName);
		linkAddress = $.trim(linkAddress);
		var tabId = Data.curTabId.get();
		//格式判断
		if(linkName==""||linkAddress=="") return false;

		var data = {};
		var url = "../php/index.php?page=0&event=2";
		data.tab_id = tabId;
		data.link_name = linkName;
		data.link_address = linkAddress;
		var success = function(data,textStatus){
			var $modal = $("#link-modal");
			var msgObj = eval("("+data+")");
			var result = msgObj.result;
			var msg = msgObj.msg;
			if(result=="1"){
				$modal.modal("hide");
				navClickHelp(Data.curTabId.get());
			}else if(result=="0"){
				alert(msg);
			}
		};
		var error = function(){};
		var beforeSend = Tool.sendMessageParam.beforeSend("link-modal");
		var complete = Tool.sendMessageParam.complete("link-modal");
		var $aj = Tool.sendMessage(url,data,beforeSend,success,error,complete);
		$.ajax($aj);
	});
	//链接添加页面 输入框验证事件
	$("#link-modal").on("focus","input#link-name",function(){
		$("#link-name-help").html("请输入链接名");
	});
	$("#link-modal").find("input#link-name").blur(function(){
		var linkName = $(this).val();
		linkName = $.trim(linkName);
		if(linkName == "")
			$("#link-name-help").html("链接名不能为空！");
	});
	$("#link-modal").on("focus","input#link-address",function(){
		$("#link-address-help").html("请输入链接地址");
	});
	$("#link-modal").find("input#link-address").blur(function(){
		var linkAddress = $(this).val();
		linkAddress = $.trim(linkAddress);
		if(linkAddress == "")
			$("#link-address-help").html("链接地址不能为空！");
	});

	//添加标签按钮点击事件
	$(".add-tab").unbind("click").bind("click",function(){
		var tabName = 
			Data.navTabList.getAllParentName(Data.curTabId.get());
		$("#parent-tab-name").val(tabName);
		$("#new-tab-name-help").html("请输入标签名");
		$("#new-tab-name").val("");
		$("#tab-modal").modal("show");
	});
	$("#tab-modal div.modal-footer button").eq(1).unbind("click").bind("click",function(){
		var tabName = $("#new-tab-name").val();
		tabName = $.trim(tabName);
		
		var parTabId = Data.curTabId.get();
		console.log("click hehe"+parTabId);
		//格式判断
		if(tabName=="") return false;

		var data = {};
		var url = "../php/index.php?page=0&event=1";
		data.parent_tab_id = parTabId;
		data.tab_name = tabName;
		
		var success = function(data,textStatus){
			var $modal = $("#tab-modal");
			var msgObj = eval("("+data+")");
			var result = msgObj.result;
			var msg = msgObj.msg;
			if(result=="1"){
				$modal.modal("hide");
				window.location.href = "index.php";
				//navClickHelp(Data.curTabId.get());
			}else if(result=="0"){
				alert(msg);
			}
		};
		var error = function(){};
		var beforeSend = Tool.sendMessageParam.beforeSend("tab-modal");
		var complete = Tool.sendMessageParam.complete("tab-modal");
		var $aj = Tool.sendMessage(url,data,beforeSend,success,error,complete);
		$.ajax($aj);
	});
	//标签添加页面 输入框验证事件
	$("#tab-modal").on("focus","input#new-tab-name",function(){
		$("#new-tab-name-help").html("请输入标签名");
	});
	$("#tab-modal").find("input#new-tab-name").blur(function(){
		var tabName = $(this).val();
		tabName = $.trim(tabName);
		if(tabName == "")
			$("#new-tab-name-help").html("标签名不能为空！");
	});

	function background_change(){
		if(Data.backTimeOut.get()){
			//console.log(Data.backTimeOut.get());
			clearTimeout(Data.backTimeOut.get());
		} 
		var imgNum = Data.imgNumber.get();
		var imgBoxNum = Data.imgBoxNumber.get();
		var imgString = imgNum+".jpg";
		$("#page-background div").eq((imgBoxNum+1)%2).css("display","none");
		$("#page-background div").eq(imgBoxNum).css("display","block");
		$("#page-background div").eq(imgBoxNum).removeClass('background-big').addClass('background-big');
		
		imgNum = Tool.getRandom(1,Data.imgNumber.getMax()+1);
		imgString = imgNum+".jpg";
		$("#page-background div").eq((imgBoxNum+1)%2).css("background-image","url(.\/img\/"+imgString+")");
		$("#page-background").css("background-image","url(.\/img\/"+imgString+")");
		imgBoxNum = (imgBoxNum+1)%2;
		Data.imgNumber.set(imgNum);
		Data.imgBoxNumber.set(imgBoxNum);
		var timeout = setTimeout(background_change,5000);
		Data.backTimeOut.set(timeout);
	}
	function getTabList(){
		//开始获取
		var url = "../php/index.php?page=0&event=3";
		var data = {};
		var success = function(data,textStatus){
			Data.navTabList.set(data);
		};
		var error = function(XMLHttpRequest, textStatus, errorThrown){
			setTimeout(getTabList,10000);
		};
		var beforeSend = function(){};
		var complete = function(){};
		var $aj = Tool.sendMessage(url,data,beforeSend,success,error,complete);
		$.ajax($aj);
	}
	function createLinkPage(data){
		var size = data.length;
		var $content = $("#page-content>div.row");
		//清空
		$content.find(".link-page-box").remove();
		var pageSize = 8;
		var pageNumber = Math.ceil(size/pageSize);
		if(pageNumber==0) pageNumber = 1;
		//console.log("page:"+pageNumber+" size:"+size);
		for (var i=1;i<=pageNumber;i++) {
			var $page = $(Tool.createLinkPage(i));
			$content.append($page);
			var $ul = $page.find("ul.link-list");
			if(size==0){
				var $li = $("<li>该标签下没有链接</li>");
				$ul.append($li);
			}else{
				for(var j=(i-1)*pageSize;j<i*pageSize&&j<size;j++){
					var $li = $(Tool.createLink(data[j].link_id,data[j].link_name,data[j].link_address));
					$ul.append($li);
				}
			}
			if(i<=3){
				//console.log("class:"+Data.getPageClass(i));
				$page.addClass(Data.getPageClass(i));
			}
		}
	}
	//<li>该标签下没有链接</li>
});