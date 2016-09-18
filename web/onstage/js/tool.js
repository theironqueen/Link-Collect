var Tool = (function(){


	return {
		//取值范围 [min,max)为整数
		getRandom:function (min,max){
			var result = Math.random()*(max-min);
			result = Math.floor(result) + min;
			return result;
		},
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
		createLinkPage:function(pageNumber){
			var html;
			html = 
			"<div class='col-md-4 col-lg-3 col-sm-6 link-page-box'>"
              +"<div class='panel panel-default mypage-panel'>"
                +"<div class='panel-heading'>"
                  +"<h3 class='panel-title'></h3>"
                +"</div>"
                +"<div class='panel-body'>"
                  +"<ul class='link-list'>"
                  +"</ul>"
                +"</div>"
                +"<div class='panel-page'><span>"+pageNumber+"</span></div>"
              +"</div>"
            +"</div>";

            return html;
		},
		createLink:function(linkId,linkName,linkAddress){
			var html;
			html = "<li><a class='link' title='"+linkId+"' href='"+linkAddress+"' target='_blank'>"
                    +"<div><p>"+linkName+"</p></div></a></li>";
            return html;
		},//ajax 发送模块 默认使用的 success error beforeSend complete参数
		sendMessageParam:{
			beforeSend:function(modalId){
				var result = function(XMLHttpRequest){
					Tool.pageLoader.show(modalId);
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
	};
})();