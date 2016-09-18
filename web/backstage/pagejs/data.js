/**
 * 个人链接管理--图片及地址管理
 * author 游玩的兔子
 * 对外接口
 * 获取页面地址数据
 * getAddressData( name)
 * 获取图片地址数据
 * getImageData( name,type)
 */
var MyPageData = (function(){

	var ADDRESS = {
		'back':"../onstage/index.php",
		'index':"index.html",
		'link':"myLink.html",
		'tab':"myTab.html",
		'record':"myRecord.html"
	};
	var IMAGE = {
		'jpg':{
			'logo':"./img/logo.jpg"
		},
		'png':{
			'logo':"./img/logo.png"
		},
		'gif':{

		}
	};
	var RECORD_TYPE = {
		"0":"新建",
		"1":"删除",
		"2":"编辑",
		"3":"转移"
	};
	var RECORD_OBJECT = {
		"0":"链接",
		"1":"标签"
	};
	var INDEX_TAB_DATA;
	var tabParentIdFlag;
	return {
		getAddressData: function(name){
			var result = "";
			result = ADDRESS[name];
			if(typeof(result)=="undefined"){
				console.log("不存在名字为"+name+"的地址");
				result = "";
			}
			return result;
		},
		getImageData:function(name,type){
			var result = "";
			result = IMAGE[type];
			if(typeof(result)=="undefined"){
				console.log("不存在类型为"+type+"的图片");
				result = "";
				return result;
			}
			result = IMAGE[type][name];
			if(typeof(result)=="undefined"){
				console.log("不存在名称为"+name+"且类型为"+type+"的图片");
				result = "";
			}
			return result;
		},
		getRecordData:{
			"getType":function(type){
				return RECORD_TYPE[type];
			},
			"getObject":function(object){
				return RECORD_OBJECT[object];
			}
		},
		setIndexTabData:function(data){
			INDEX_TAB_DATA = data;
		},
		getIndexTabData:function(){
			return INDEX_TAB_DATA;
		},
		parentIdFlag:{
			set:function(v){
				tabParentIdFlag = v;
			},
			get:function(){
				return tabParentIdFlag;
			}
		}

	};
})();