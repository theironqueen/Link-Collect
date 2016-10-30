var Tool = (function(){
	/**
	 * dataTable 插件使用 参数配置信息获取
	 * @param  {[type]} tableName  table的id信息
	 */
	function getDataTableParamHelp(tableName){
		var params = {
			serverSide:true,
			processing:true,
			ordering:false,
			pageLength:10,
			language:{},
			ajax:{},
			columns:[],
			columnDefs:[],
			dom:"<'#"+tableName+"-header'lf>rt<'#"+tableName+"-footer'ip>",
			initComplete:function(){}
		};
		//language初始化
		params.language = {
			"lengthMenu": '<div><span>每页显示</span><select class="input-xsmall">' + '<option value="5">5</option>' + '<option value="10">10</option>' + '<option value="20">20</option>' + '<option value="30">30</option>' + '<option value="40">40</option>' + '<option value="50">50</option>' + '</select><span>条记录</span></div>',
			"processing":"加载中...",
			"zeroRecords": "没有内容",
            "info": "共_TOTAL_ 条_PAGES_页，当前显示第 _START_条到第 _END_条",
            "infoEmpty": "0条记录",
            "infoFiltered": ""
		};
		return params;

	}
	//输入信息 验证 正则表达式
	var INPUT_CHECK_REG = {"username":"^[a-zA-Z][0-9a-zA-Z]+",
						"password":"^[0-9a-zA-Z]+",
						"date":"^([0-9]{3}[1-9]|[0-9]{2}[1-9][0-9]{1}|[0-9]{1}[1-9][0-9]{2}|[1-9][0-9]{3})\\\/(((0[13578]|1[02])\\\/(0[1-9]|[12][0-9]|3[01]))|((0[469]|11)\\\/(0[1-9]|[12][0-9]|30))|(02\\\/(0[1-9]|[1][0-9]|2[0-8])))$",
						"phone":"^1[3|4|5|8][0-9]\\d{8}$",
						"email":"^[\\w!#$%&'*+/=?^_`{|}~-]+(?:\\.[\\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\\w](?:[\\w-]*[\\w])?\\.)+[\\w](?:[\\w-]*[\\w])?$",
						"idCard":"^(\\d{14}|\\d{17})(\\d|[xX])$",
						"bankCard":"^\\d{16,19}$",
						"number":"^[0-9]*$"
					};
	var inputCheckHelp = {
		"number":function(string){
			var result = input_check("number",string);
			return result;
		},
		"void":function(string){
			string = $.trim(string);
			if(string == ""){
				return true;
			}
			return false;
		},
		"date":function(string){
			var result = input_check("date",string);
			return result;
		},
		"dateRange":function (string){
			if(string=="") return true;

			var dates = string.split("-");
			if(dates.length!=2) return false;

			var startDate = dates[0];
			var endDate = dates[1];
			startDate = $.trim(startDate);
			endDate = $.trim(endDate);

			if(startDate==""||!input_check("date",startDate)||endDate==""||!input_check("date",endDate))
				return false;
			var startDateItems = startDate.split("/");
			var endDateItems = endDate.split("/");
			for (var i=0;i<3;i++){
				if(startDateItems[i]>endDateItems[i])return false;
				else if(startDateItems[i]<endDateItems[i])return true;
			}
			return true;
		}
	};
	function input_check(type,data){
		eval("var reg = /"+INPUT_CHECK_REG[type]+"/;");
		if(reg.test(data)){
			return true;
		} else {
			return false;
		}
	}
	//输入框 输入内容反馈样式数据
	var inputFeedbackData = {
		"error":["has-error","glyphicon-remove"],
		"success":["has-success","glyphicon-ok"],
		"warning":["has-warning ","glyphicon-warning-sign"]
	};
	return {
		/**
		 * 获取dataTable 默认参数
		 * @param  {[type]} tableName table的id
		 * @return {[type]}           默认的配置参数
		 */
		getDataTableParam:function(tableName){
			var result = getDataTableParamHelp(tableName);
			return result;
		},
		/**
		 * button 按钮创建所需html信息
		 * @param  {[type]} buttonType    按钮样式类型
		 * @param  {[type]} buttonClass   自定义按钮的class
		 * @param  {[type]} buttonContent 按钮内容信息
		 * @param  {[type]} iconType      使用图标的类型
		 * @param  {[type]} iconColor     图标颜色
		 * @return {[type]}               构建按钮的html内容
		 */
		getTableButtonHtml:function(buttonType,buttonClass,buttonContent,iconType,iconColor){
			var html = "<a class='btn "+buttonType+" "+buttonClass+"' href='#'>"
                		+"<i class='glyphicon "+iconType+" "+iconColor+"'></i>"
                		+" "+buttonContent+"</a>";
            return html;
		},
		/**
		 * 输入内容检查
		 * @param  {[type]} string 输入串
		 * @param  {[type]} type   检查的类型
		 * @return {[type]}        结果是否正确
		 */
		inputCheck:function(string,type){
			var func = inputCheckHelp[type];
			if(typeof(func)=="undefined"){
				console.log("类型："+type+"输入错误，没有此类错误判断");
				return  func;
			}
			var result = func(string);
			return result;
		},
		/**
		 * 输入信息反馈显示
		 * @param  {[type]} $input 输入框元素
		 * @param  {[type]} type   反馈显示类型
		 * @return {[type]}        
		 */
		inputFeedbackShow:function($input,type){
			var $span = $input.parent().find('span.glyphicon');
			var $div_box = $input.parent().parent();
			var key;
			for(key in inputFeedbackData){
				if(key == type){
					$span.addClass(inputFeedbackData[key][1]);
					$div_box.addClass(inputFeedbackData[key][0])
				}else{
					$span.removeClass(inputFeedbackData[key][1]);
					$div_box.removeClass(inputFeedbackData[key][0])
				}
			}
		},
		//ajax 发送模块 基本参数获取
		sendMessage:function(url,data,beforeSend,success,error,complete){
			var ja = {
				url: url,
				type: "post",
				data: data,
				timeout:3000,
				success:success,
				beforeSend:beforeSend,
				error:error,
				complete:complete
			};
			return ja;
		},
		//ajax 发送模块 默认使用的 success error beforeSend complete参数
		sendMessageParam:{
			success:function(modalId,tableId,alertId){
				var result = function(data,textStatus){
					var $modal = $("#"+modalId);
					var msgObj = eval("("+data+")");
					var result = msgObj.result;
					var msg = msgObj.msg;

					if(result=="1"){
						Tool.alertMessage(alertId,"success","成功",msg);
						$modal.modal("hide");
						$("#"+tableId).DataTable().draw();
					}else if(result=="0"){
						Tool.alertMessage(alertId,"error","失败",msg);
					}else{
						alert("服务器传值错误");
					}
				};
				return result;

			},
			beforeSend:function(modalId){
				var result = function(XMLHttpRequest){
					Tool.pageLoader.show(modalId);
				};
				return result;
			},
			error:function(alertId,errorMessage){
				var result = function(XMLHttpRequest, textStatus, errorThrown){
					Tool.alertMessage(alertId,"error","失败",errorMessage);
				};
				return result;
			},
			complete:function(modalId){
				var result = function(XMLHttpRequest, textStatus){
					Tool.pageLoader.close(modalId);
				};
				return result;
			}
		},
		//页面加载模块
		pageLoader:{
			show:function(loader){
				$("#"+loader).find('div.LC_page_loader').removeClass('hide');
			},
			close:function(loader){
				$("#"+loader).find('div.LC_page_loader').addClass('hide');
			}
		},
		alertMessage:function (boxId,type,title,msg){

			var TYPE_DATA = {
				'error':"alert-danger",
				'info':"alert-info",
				'success':"alert-success"
			};
			var $box = $("#"+boxId);
			$msg_box = $("<div class='alert "+TYPE_DATA[type]+" LC_fade_in'></div>");
			$msg_titile = $("<strong>"+title+"</strong>");
			$msg_content = $("<span class='m-l-10'>"+msg+"<span>");
			$msg_close = $("<button type='button' class='close' data-dismiss='alert'>&times;</button>");
			//加入页面
			$box.prepend($msg_box);
			$msg_box.append($msg_titile);
			$msg_box.append($msg_content);
			$msg_box.append($msg_close);

			//延时消失
			$msg_close.delay("3000").queue(function(){
				$(this).click();
			});
		},
		searchSwitchChangeEvent:function(inputId,alertMessage){
			var ev = function(){
				var $this = $(this);
				var select_val = $this.val();
				select_val = parseInt(select_val);
				$("#"+inputId).attr("placeholder",alertMessage[select_val]);
			};
			return ev;
		}

	};
})();