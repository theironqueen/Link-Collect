$(document).ready(function(){
	//mainNavActive("link","main-nav");
	mainContentActive("tab","content-header");

	//table初始化
	var tableParam = tabTableInit();

	$("#tab-table").DataTable(tableParam);

	$("#tab-show-form").modalFormTool('create',{
		'formContents':[
			{'type':'oneLineText', 'label':'标签id:'},
			{'type':'oneLineText', 'label':'标签名:'},
			{'type':'oneLineText', 'label':'创建时间:'},
			{'type':'oneLineText', 'label':'父标签id:'},
			{'type':'oneLineText', 'label':'父标签名:'},
			{'type':'oneLineText', 'label':'包含链接数:'},
			{'type':'oneLineText', 'label':'包含标签数:'}
		]
	});

	$("#tab-edit-form").modalFormTool('create',{
		'hasFeedback':true,
		'formContents':[
			{'type':'oneLineText', 'label':'标签id:'},
			{'type':'oneLineInput', 'label':'标签名:', params:{'tabindex':1}, 'helpMessage':{'error':'标签名不能为空'}},
			{'type':'oneLineText', 'label':'创建时间:'},
			{'type':'oneLineInput', 'label':'父标签id:',  params:{'tabindex':2}, 'helpMessage':{'success':'','error':'父标签id不正确', 'warning':'正在获取标签信息···'}},
			{'type':'oneLineText', 'label':'父标签名:'},
			{'type':'oneLineText', 'label':'包含链接数:'},
			{'type':'oneLineText', 'label':'包含标签数:'}
		],
		'overwriteFunctions':[
			{
				'index':1,
				'verifyFunction':function(inputString){
					var result = Tool.inputCheck(inputString,"void");
					if (!result)
						return 'success';
					else
						return 'error';
				}
			},
			{
				'index':3,
				'verifyFunction':function(inputString){
					var tab_parent_id = $.trim(inputString);
					var data = $("#tab-edit-form").modalFormTool('getValues');
					var tab_id = data[0];
					if(Tool.inputCheck(tab_parent_id,"void")){
						$("#tab-edit-form").modalFormTool('setValues', {4:'无'});
						return 'success';
					}else{
						//ajax搜索
						var success = function(data,textStatus){
							var msgObj = eval("("+data+")");
							var result = msgObj.result;
							var $input = $("#tab-edit form div.form-group").eq(3).find("input");
							var message = msgObj.msg;
							if(result=="1"){
								$("#tab-edit-form").modalFormTool('setValues', {4:message});
								$("#tab-edit-form").modalFormTool('setFeedbackStatus', {3:'success'});
								$("#tab-edit-form").modalFormTool('setVerify', {3:true});

							}else if(result=="0"){
								$("#tab-edit-form").modalFormTool('setValues', {4:''});
								$("#tab-edit-form").modalFormTool('setFeedbackStatus', {3:'error'});
								$("#tab-edit-form").modalFormTool('setVerify', {3:false});
								$("#tab-edit-form").modalFormTool('setHelpMessage', {3:message});

							}else{
								alert("服务器传值错误");
								$("#tab-edit-form").modalFormTool('setVerify', {3:false});
							}
						};
						var beforeSend = function(XMLHttpRequest){};
						var error = function(XMLHttpRequest, textStatus, errorThrown){};
						var complete = function(XMLHttpRequest, textStatus){};

						var url = "../php/index.php?page=3&event=2";
						var Data = {};
						Data.tab_parent_id = tab_parent_id;
						Data.tab_id = tab_id;
						var ajaxParam = Tool.sendMessage(url,Data,beforeSend,success,error,complete);
						$.ajax(ajaxParam);
						return 'warning';
					}
				}
			}
		]
	});
	//标签显示
	$(document).on("click",".tab-show",function(){
		var $this = $(this);
		var $dialog = $("#tab-show");
		//获取内容
		$data = $("#tab-table").DataTable().row($this.parents("tr")).data();
		var values = {
			0:$data.tab_id,
			1:$data.tab_name,
			2:$data.tab_time,
			3:$data.tab_parent_id,
			4:$data.tab_parent_name,
			5:$data.link_number,
			6:$data.tab_number
		};
		//内容写入
		$("#tab-show-form").modalFormTool('setValues', values);
		$dialog.modal("show");
		
		return false;
	});

	//标签编辑弹框
	$(document).on("click",".tab-edit",function(){
		var $this = $(this);
		var $dialog = $("#tab-edit");
		//获取内容
		$data = $("#tab-table").DataTable().row($this.parents("tr")).data();
		var $form = $("#tab-edit").find("form");
		var $form_items = $form.find("div.form-group");
		//内容写入
		var values = {
			0:$data.tab_id,
			1:$data.tab_name,
			2:$data.tab_time,
			3:$data.tab_parent_id,
			4:$data.tab_parent_name,
			5:$data.link_number,
			6:$data.tab_number
		};
		$("#tab-edit-form").modalFormTool('setValues', values);
		$("#tab-edit-form").modalFormTool('setFeedbackStatus', {1:'default',3:'default'});
		var index = $("#tab-table").DataTable().row($this.parents("tr")).index();
		$("#tab-edit div.modal-footer a").eq(1).attr("title",index);
		$dialog.modal("show");

		return false;
	});


	//标签编辑保存点击事件
	$("#tab-edit div.modal-footer a").eq(1).unbind("click").bind("click",function(){

		var values = $("#tab-edit-form").modalFormTool('getValues');
		var tab_name = values[1]
		var tab_parent_id = values[3];
		tab_name = $.trim(tab_name);
		tab_parent_id = $.trim(tab_parent_id);

		var is_right = $("#tab-edit-form").modalFormTool('getVerifyResult');
		
		if(is_right){
			//发送信息
			//发送到服务器的信息
			var editData = {};
			editData.tab_id = values[0];
			editData.tab_name = tab_name;
			editData.tab_parent_id = tab_parent_id;
			editUpload(editData);
		}
		return false;
	});
	//标签编辑信息 上传辅助
	function editUpload(editData){
		var url = "../php/index.php?page=3&event=1";
		var success = Tool.sendMessageParam.success("tab-edit","tab-table","LC_notify_box");
		var beforeSend = Tool.sendMessageParam.beforeSend("tab-edit");
		var error = Tool.sendMessageParam.error("LC_notify_box","链接超时,或未知错误");
		var complete = Tool.sendMessageParam.complete("tab-edit");
		var ajaxParam = Tool.sendMessage(url,editData,beforeSend,success,error,complete);
		$.ajax(ajaxParam);
	}

	//标签删除弹框
	$(document).on("click",".tab-delete",function(){
		// console.log("tab-click");
		var $this = $(this);
		var $dialog = $("#tab-delete");
		//获取内容
		$data = $("#tab-table").DataTable().row($this.parents("tr")).data();
		var index = $("#tab-table").DataTable().row($this.parents("tr")).index();
		$("#tab-delete div.modal-footer a").eq(1).attr("title",index);
		$dialog.modal("show");
		return false;
	});
	//标签删除确认点击事件
	$("#tab-delete div.modal-footer a").eq(1).unbind("click").bind("click",function(){
		var index = $(this).attr("title");
		var $data = $("#tab-table").DataTable().row(index).data();
		//发送信息
		//发送到服务器的信息
		var Data = {};
		Data.tab_id = $data.tab_id;
		deleteUpload(Data);
		return false;
	});
	function deleteUpload(deleteData){
		var url = "../php/index.php?page=3&event=4";
		var success = Tool.sendMessageParam.success("tab-delete","tab-table","LC_notify_box");
		var beforeSend = Tool.sendMessageParam.beforeSend("tab-delete");
		var error = Tool.sendMessageParam.error("LC_notify_box","链接超时,或未知错误");
		var complete = Tool.sendMessageParam.complete("tab-delete");
		var ajaxParam = Tool.sendMessage(url,deleteData,beforeSend,success,error,complete);
		$.ajax(ajaxParam);
	}

	/**
	 * 链接表格初始化参数获取
	 * @return {[type]} 表格初始化所需参数
	 */
	function tabTableInit(){
		var tempParam = Tool.getDataTableParam("tab-table");
		tempParam.ajax = {
			type:"post",
			url:"../php/index.php?page=3&event=0",
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
			{"data":"tab_name"},
			{"data":"tab_time"},
			{"data":"tab_parent_name"},
			{"data":"link_number"},
			{"data":"tab_number"},
			{"data":null},
		];

		tempParam.columnDefs = [
			{
				targets:5,
				render:function(a,b,c,d){
					var html = Tool.getTableButtonHtml("btn-success","tab-show","详情","glyphicon-zoom-in","icon-white");//详情
					html = html + Tool.getTableButtonHtml("btn-info","tab-edit","编辑","glyphicon-edit","icon-white");//编辑
					html = html + Tool.getTableButtonHtml("btn-danger","tab-delete","删除","glyphicon-trash","icon-white");//删除
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
                                +"<option value='2'>子标签</option>"
                            +"</select>"
                        +"</div>"
                    +"</div>"
                    +"<div class='form-group '>"
                        +"<input type='text' class='form-control' id='search-content' >"
                    +"</div>"
                    +"<a class='btn btn-default' href='#''><i class='glyphicon glyphicon-search icon-white'></i></a>"
                +"</div>");
			$("#tab-table_filter").html("").append($search);
			$("#search-type").chosen();
			$("#search_type_chosen").css("width","80px");

			$("#tab-table_filter div.search-box a.btn").unbind("click").bind("click",function(){
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
					$("#tab-table").DataTable().draw();
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
				"请输入标签id","请输入标签名","请输入标签id"
			];
			//switch 提示
			$("#search-type").change(Tool.searchSwitchChangeEvent("search-content",alertMessage));
			$("#search-type").trigger("change");
		}
		return tempParam;
	}
});