//主导航信息
var navItems = {
	'index':["首页","glyphicon-home"],
	'link':["链接","glyphicon-bookmark"],
	'tab':["标签","glyphicon-tags"],
	'record':["操作记录","glyphicon-edit"]
};

/**
 * 导航激活函数
 * @param  {String} name  激活页面名称
 * @param  {[type]} navId 导航名称
 */
function mainNavActive(name,navId){
	var $mainNav = $("#"+navId);
	var $navItems = $mainNav.find("li");
	var i=1;
	for(key in navItems){
		if(key == name)break;
		i++;
	}
	var $activeItem = $navItems.eq(i);
	$activeItem.addClass('active');
}

/**
 * 内部内容框架 标题信息显示初始化
 * @param  {[type]} name          页面名称
 * @param  {[type]} contentHeadId 页面标题所处元素id
 */
function mainContentActive(name,contentHeadId){
	var $mainContentHeader = $("#"+contentHeadId);
	var key;
	for(key in navItems){
		if(key == name)break;
	}
	$mainContentHeader.find("i").addClass(navItems[key][1]);
	$mainContentHeader.find("span").html(navItems[key][0]);
}