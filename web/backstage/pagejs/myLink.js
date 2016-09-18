$(document).ready(function(){
	//mainNavActive("link","main-nav");
	mainContentActive("link","content-header");

	//table初始化
	var tableParam = linkTableInit();
	//console.log(tableParam);
	$("#link-table").DataTable(tableParam);


	//元素绑定
	//链接显示弹框
	$(document).on("click",".link-show",function(){
		// console.log("link-click");
		var $this = $(this);
		var $dialog = $("#link-show");
		//获取内容
		$data = $("#link-table").DataTable().row($this.parents("tr")).data();
		var $form = $("#link-show").find("form");
		var $form_items = $form.find("div.form-group");
		//内容写入
		$form_items.eq(0).find('div.form-group-content').html($data.link_id);
		$form_items.eq(1).find('div.form-group-content').html($data.link_name);
		$form_items.eq(2).find('div.form-group-content').html($data.link_address);
		$form_items.eq(3).find('div.form-group-content').html($data.tab_id);
		$form_items.eq(4).find('div.form-group-content').html($data.tab_name);
		$form_items.eq(5).find('div.form-group-content').html($data.link_time);
		$form_items.eq(6).find('div.form-group-content').html($data.link_click);
		$dialog.modal("show");
		//Tool.alertMessage("LC_notify_box","error","error","this is a error");
		return false;

	});
	//链接编辑弹框
	$(document).on("click",".link-edit",function(){
		// console.log("link-click");
		var $this = $(this);
		var $dialog = $("#link-edit");
		//获取内容
		$data = $("#link-table").DataTable().row($this.parents("tr")).data();
		var $form = $("#link-edit").find("form");
		var $form_items = $form.find("div.form-group");
		//内容写入
		$form_items.eq(0).find('div.form-group-content').html($data.link_id);
		$form_items.eq(1).find('input').val($data.link_name);
		$form_items.eq(2).find('input').val($data.link_address);
		$form_items.eq(3).find('div.form-group-content').html($data.tab_id);
		$form_items.eq(4).find('div.form-group-content').html($data.tab_name);
		$form_items.eq(5).find('div.form-group-content').html($data.link_time);
		$form_items.eq(6).find('div.form-group-content').html($data.link_click);

		var index = $("#link-table").DataTable().row($this.parents("tr")).index();
		$("#link-edit div.modal-footer a").eq(1).attr("title",index);
		$dialog.modal("show");
		return false;
	});
	$("#link-edit").on("focus","input.form-control",function(){
		Tool.inputFeedbackShow($(this),"");
	});
	//链接名判断
	$("#link-edit").find("form").find("div.form-group").eq(1).find("input").blur(function(event) {
		var link_name = $(this).val();
		link_name = $.trim(link_name);
		if(Tool.inputCheck(link_name,"void")){
			Tool.inputFeedbackShow($(this),"error");
		}
	});
	//链接地址判断
	$("#link-edit form div.form-group").eq(2).find("input").blur(function(event) {
		var link_address = $(this).val();
		link_address = $.trim(link_address);
		if(Tool.inputCheck(link_address,"void")){
			Tool.inputFeedbackShow($(this),"error");
		}
	});

	//链接编辑保存点击事件
	$("#link-edit div.modal-footer a").eq(1).unbind("click").bind("click",function(){
		console.log("save");
		var $dialog = $("#link-edit");
		var $form = $("#link-edit").find("form");
		var $form_items = $form.find("div.form-group");

		var index = $(this).attr("title");
		var $data = $("#link-table").DataTable().row(index).data();

		var link_name = $form_items.eq(1).find('input').val();
		var link_address = $form_items.eq(2).find('input').val();
		link_name = $.trim(link_name);
		link_address = $.trim(link_address);
		var is_right = true;
		//判断 链接名和地址不为空
		if(Tool.inputCheck(link_name,"void")){
			is_right = false;
		}
		if(Tool.inputCheck(link_address,"void")){
			is_right = false;
		}
		if(is_right){
			//发送信息
			//发送到服务器的信息
			var editData = {};
			editData.link_id = $data.link_id;
			editData.link_name = link_name;
			editData.link_address = link_address;
			editUpload(editData);
		}
		return false;
	});
	//链接编辑信息 上传辅助
	function editUpload(editData){
		var url = "../php/index.php?page=2&event=1";
		var success = Tool.sendMessageParam.success("link-edit","link-table","LC_notify_box");
		var beforeSend = Tool.sendMessageParam.beforeSend("link-edit");
		var error = Tool.sendMessageParam.error("LC_notify_box","链接超时,或未知错误");
		var complete = Tool.sendMessageParam.complete("link-edit");

		var ajaxParam = Tool.sendMessage(url,editData,beforeSend,success,error,complete);

		$.ajax(ajaxParam);
		
	}

	//链接删除弹框
	$(document).on("click",".link-delete",function(){
		// console.log("link-click");
		var $this = $(this);
		var $dialog = $("#link-delete");
		//获取内容
		$data = $("#link-table").DataTable().row($this.parents("tr")).data();
		var index = $("#link-table").DataTable().row($this.parents("tr")).index();
		$("#link-delete div.modal-footer a").eq(1).attr("title",index);
		$dialog.modal("show");
		return false;
	});
	//链接删除确认点击事件
	$("#link-delete div.modal-footer a").eq(1).unbind("click").bind("click",function(){
		var index = $(this).attr("title");
		var $data = $("#link-table").DataTable().row(index).data();
		//发送信息
		//发送到服务器的信息
		var Data = {};
		Data.link_id = $data.link_id;
		deleteUpload(Data);
		return false;
	});
	function deleteUpload(deleteData){
		var url = "../php/index.php?page=2&event=2";
		var success = Tool.sendMessageParam.success("link-delete","link-table","LC_notify_box");
		var beforeSend = Tool.sendMessageParam.beforeSend("link-delete");
		var error = Tool.sendMessageParam.error("LC_notify_box","链接超时,或未知错误");
		var complete = Tool.sendMessageParam.complete("link-delete");
		var ajaxParam = Tool.sendMessage(url,deleteData,beforeSend,success,error,complete);
		$.ajax(ajaxParam);
	}

	/**
	 * 链接表格初始化参数获取
	 * @return {[type]} 表格初始化所需参数
	 */
	function linkTableInit(){
		var tempParam = Tool.getDataTableParam("link-table");
		tempParam.ajax = {
			type:"post",
			url:"../php/index.php?page=2&event=0",
			dataSrc:"data",
			data:function(d){
				var obj = {};
				obj.page_num = d.length;
				obj.page = (d.start/d.length)+1;
				obj.draw = d.draw;

				var search_type,search_content;
				search_type = $("#search-type").val();
				if(typeof(search_type)=="undefined"){
					obj.search_type = 0;
					obj.search_content = "";
				}else{
					search_content = $("#search-content").val();
					if(Tool.inputCheck(search_content,"void")){
						search_content = "";
					}
					obj.search_type = search_type;
					obj.search_content = search_content;
				}
				console.log(JSON.stringify(obj));
				return obj;
			}
		};

		tempParam.columns = [
			{"data":"link_name"},
			{"data":"link_time"},
			{"data":"link_address",
				"render":function(a,b,c,d){
					var address = c.link_address;
					if(address.length>=80){
						address = address.substring(0,77)+"...";
					}
					var html = "<div class='link-address word-wrap'>"+address+"</div>"
					return html;
				}
			},
			{"data":"tab_name"},
			{"data":"link_click"},
			{"data":null},
		];

		tempParam.columnDefs = [
			{
				targets:5,
				render:function(a,b,c,d){
					var html = Tool.getTableButtonHtml("btn-success","link-show","详情","glyphicon-zoom-in","icon-white");//详情
					html = html + Tool.getTableButtonHtml("btn-info","link-edit","编辑","glyphicon-edit","icon-white");//编辑
					html = html + Tool.getTableButtonHtml("btn-danger","link-delete","删除","glyphicon-trash","icon-white");//删除
					return html;
				}
			}
		];
		tempParam.initComplete = function(setting,json){
			var $search = $("<div class='search-box'>"
                    +"<div class='control-group '>"
                        +"<label class='control-label' for='search-type'>搜索类型:</label>"
                        +"<div class='controls'>"
                            +"<select id='search-type' data-rel='chosen'>"
                                +"<option value='0'>标签id</option>"
                                +"<option value='1'>标签名</option>"
                                +"<option value='2'>链接id</option>"
                                +"<option value='3'>链接名</option>"
                            +"</select>"
                        +"</div>"
                    +"</div>"
                    +"<div class='form-group '>"
                        +"<input type='text' class='form-control' id='search-content'>"
                    +"</div>"
                    +"<a class='btn btn-default' href='#''><i class='glyphicon glyphicon-search icon-white'></i></a>"
                +"</div>");
			$("#link-table_filter").html("").append($search);
			$("#search-type").chosen();
			$("#search_type_chosen").css("width","80px");

			$("#link-table_filter div.search-box a.btn").unbind("click").bind("click",function(){
				//错误检测
				//获取输入数据
				var search_type,search_content;
				search_type = $("#search-type").val();
				search_content = $("#search-content").val();
				var result = true;
				if(search_type==0||search_type==2){
					result = Tool.inputCheck(search_content,"number");
				}

				if(result){
					$("#link-table").DataTable().draw();
				}else{
					$("#search-content").parent().addClass("has-error");
				}
				// console.log(result);
				return false;
			});
			//input获取焦点
			$("#search-content").focus(function(){
				var $this = $(this);
				$this.parent().removeClass("has-error").removeClass("has-success");
			});

			var alertMessage = [
				"请输入标签id","请输入标签名","请输入链接id","请输入链接名"
			];
			//switch 提示
			$("#search-type").change(Tool.searchSwitchChangeEvent("search-content",alertMessage));
			$("#search-type").trigger("change");
		}
		return tempParam;
	}
});