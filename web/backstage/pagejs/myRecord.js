$(document).ready(function(){
	//mainNavActive("link","main-nav");
	mainContentActive("record","content-header");

	//table初始化
	var tableParam = recordTableInit();
	//console.log(tableParam);
	$("#record-table").DataTable(tableParam);

	$("#record-show-form").modalFormTool('create',{
		'formContents':[
			{'type':'oneLineText', 'label':'操作id:'},
			{'type':'oneLineText', 'label':'操作时间:'},
			{'type':'oneLineText', 'label':'操作类型:'},
			{'type':'oneLineText', 'label':'操作对象:'},
			{'type':'multiLineText', 'label':'操作内容:'}
		]
	});

	//标签显示
	$(document).on("click",".record-show",function(){
		var $this = $(this);
		var $dialog = $("#record-show");
		//获取内容
		$data = $("#record-table").DataTable().row($this.parents("tr")).data();
		var values = {
			0:$data.record_id,
			1:$data.record_time,
			2:MyPageData.getRecordData.getType($data.record_type),
			3:MyPageData.getRecordData.getObject($data.record_object),
			4:$data.record_memo
		}
		//内容写入
		$("#record-show-form").modalFormTool('setValues', values);
		
		$dialog.modal("show");
		return false;
	});

	/**
	 * 链接表格初始化参数获取
	 * @return {[type]} 表格初始化所需参数
	 */
	function recordTableInit(){
		var tempParam = Tool.getDataTableParam("record-table");
		tempParam.ajax = {
			type:"post",
			url:"../php/index.php?page=4&event=0",
			dataSrc:"data",
			data:function(d){
				var obj = {};
				obj.page_num = d.length;
				obj.page = (d.start/d.length)+1;
				obj.draw = d.draw;

				var search_type,search_object,search_content;
				search_type = $("#search-type").val();
				if(typeof(search_type)=="undefined"){
					obj.search_type = -1;
					obj.search_object = -1;
					obj.search_start_date = "";
					obj.search_end_date = "";
				}else{
					search_content = $("#search-content").val();
					search_object = $("#search-object").val();
					search_date = search_content.split("-");
					if(Tool.inputCheck(search_content,"void")){
						obj.search_start_date = "";
						obj.search_end_date = "";
					}else{
						obj.search_start_date = $.trim(search_date[0]);
						obj.search_end_date = $.trim(search_date[1]);
					}

					obj.search_type = search_type;
					obj.search_object = search_object;

					
				}
				console.log(JSON.stringify(obj));
				return obj;
			}
		};

		tempParam.columns = [
			{"data":"record_time"},
			{"data":"record_type",
				"render":function(a,b,c,d){
					var html = MyPageData.getRecordData.getType(c.record_type);
					return html;
				}
			},
			{"data":"record_object",
				"render":function(a,b,c,d){
					var html = MyPageData.getRecordData.getObject(c.record_object);
					return html;
				}
			},
			{"data":"record_memo",
				"render":function(a,b,c,d){
					var memo = c.record_memo;
					if(memo.length>=80){
						memo = memo.substring(0,77)+"...";
					}
					var html = "<div class='record-memo word-wrap'>"+memo+"</div>"
					return html;
				}
			},
			{"data":null},
		];

		tempParam.columnDefs = [
			{
				targets:4,
				render:function(a,b,c,d){
					var html = Tool.getTableButtonHtml("btn-success","record-show","详情","glyphicon-zoom-in","icon-white");//详情
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
                            	+"<option value='-1'>全部</option>"
                                +"<option value='0'>新建</option>"
                                +"<option value='1'>删除</option>"
                                +"<option value='2'>编辑</option>"
                                +"<option value='3'>转移</option>"
                            +"</select>"
                        +"</div>"
                    +"</div>"

                    +"<div class='control-group '>"
                        +"<label class='control-label' for='search-object'>搜索对象:</label>"
                        +"<div class='controls'>"
                            +"<select id='search-object' data-rel='chosen'>"
                            	+"<option value='-1'>全部</option>"
                                +"<option value='0'>链接</option>"
                                +"<option value='1'>标签</option>"
                            +"</select>"
                        +"</div>"
                    +"</div>"

                    +"<div class='form-group '>"
                        +"<input type='text' class='form-control' id='search-content' placeholder='1999/01/02-2000/03/04'>"
                    +"</div>"
                    +"<a class='btn btn-default' href='#''><i class='glyphicon glyphicon-search icon-white'></i></a>"
                +"</div>");
			$("#record-table_filter").html("").append($search);
			$("#search-type").chosen();
			$("#search_type_chosen").css("width","80px");
			$("#search-object").chosen();
			$("#search_object_chosen").css("width","80px");

			$("#record-table_filter div.search-box a.btn").unbind("click").bind("click",function(){
				//错误检测
				var search_content;
				search_content = $("#search-content").val();
				search_content = $.trim(search_content);
				var result = Tool.inputCheck(search_content,"dateRange");

				if(result){
					$("#record-table").DataTable().draw();
				}else{
					$("#search-content").parent().addClass("has-error");
				}

				return false;
			});

			$("#search-content").focus(function(){
				var $this = $(this);
				$this.parent().removeClass("has-error").removeClass("has-success");
			});
		}
		return tempParam;
	}
});