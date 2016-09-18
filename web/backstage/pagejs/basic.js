$(document).ready(function(){
	init();
	$('ul.main-menu li a').each(function () {
		// console.log(String(window.location));
        if ($($(this))[0].href == String(window.location))
            $(this).parent().addClass('active');
    });
    /**
     * 页面初始化
     */
	function init(){
		mainNavInit();
		topNavInit();
		//内容高度根据屏幕大小改变
		var screenHeight = $(document.body).height();
		var boxHeight = screenHeight - 60 - 80 - 20;
		$("#content").css("height",boxHeight+"px");
	}
	/**
	 * 左侧主导航内容初始化
	 */
	function mainNavInit(){
		var $mainNav = $("#main-nav");
		for(key in navItems){
			mainNavInitHelp($mainNav,navItems[key][0],key,navItems[key][1]);
		}
	}
	function mainNavInitHelp($mainNav,navName,navAddress,navIcon){
		var $li = "<li><a class='ajax-link' href='"+MyPageData.getAddressData(navAddress)+"'>"
					+"<i class='glyphicon "+navIcon+"'></i><span> "+navName+"</span></a></li>";
		$mainNav.append($li);
		
	}
	/**
	 * 页面上方导航内容初始化
	 */
	function topNavInit(){
		//logo img 地址
		$("#logo img").attr("src",MyPageData.getImageData("logo","png"));
		//主页地址
		$("#logo").attr("href",MyPageData.getAddressData("index"));
		//返回地址
		$("#page-back a").attr("href",MyPageData.getAddressData("back"));
	}
});