<?php
/*****************************************
**		 	Flyff Earthquake v4.0		**
**				 index.php				**
**		   Created by Treachery.		**
**		   Ripped from gPotato.			**
*****************************************/
require_once("inc/functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title><?php echo $shop_title; ?></title>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<script src="js/jquery-1.3.2.min.js" type="text/javascript"></script>
	<script type="text/javascript">
        var selected_main_category = null;

        $(document).ready(function(){
            DisplayRightItemAsync("popular", 1);
        });

        function DisplayRightItemAsync(nItemID, nSendGiftFlag) //nSendGiftFlag -> 1: Just Buy , 2: Gift
        {
            $.ajax({
                async: true, //false : synchronize, true : asynchronize
                type: "GET",
                url: "GetItemInfo.php?sendgiftflag="+ nSendGiftFlag+"&itemid="+nItemID,
                cache: false,
                data: {} ,
                beforeSend: function(){
                    $("#action").fadeOut();
                },
                complete: function(){
                    $("#right").fadeIn(100);
                },
                error: function(xmlHttp, textStatus, errorThrown){
                    $("#action").html(xmlHttp.responseText);
                    $("#action").fadeOut();
                },
                success: function(result){
                    $("#right").html(result);
                }
            });
        }
		
        function DisplayPageContentAsync(szUrl)
        {
            $.ajax({
                async: true, //false : synchronize, true : asynchronize
                type: "GET",
                url: szUrl,
                cache: false,
                data: {
						},
                beforeSend: function(){
                    $("#action").fadeOut();
                    //$("#list").fadeOut(100);
                },
                complete: function(){
                    //$("#list").fadeIn(100);
                },
                error: function(xmlHttp, textStatus, errorThrown){
                    $("#action").html(xmlHttp.responseText);
                    $("#action").fadeIn();
                },
                success: function(result){
                    $("#list").html(result);
                }
            });
            
        }
        
        var gnPurchaseItemID;
        var gnPurchaseSendGiftFlag;

        function OpenConfirm(nItemID, nSendGiftFlag)
        {
            if(nSendGiftFlag != 1) //for gift
            {
                if($(":input[name=friend]").val() == "")
                {
                    $(":input[name=friend]").focus();
                    return;
                }
            }

            $("#msg_hide_layer").css({opacity: 0.7,filter: 'alpha(opacity=70)'}).fadeIn()
            $("#msg_layer").slideDown();
            $(".msg_layer_sub").hide();
            $("#purchase_msg").slideDown();
            gnPurchaseItemID = nItemID;
            gnPurchaseSendGiftFlag = nSendGiftFlag;
        }

		function CloseWindow(rtn){
            if (rtn == true)
            {
                $("#purchase_msg").slideUp();
                ItemBuy(gnPurchaseItemID, gnPurchaseSendGiftFlag);
            }
            else 
            {
                $("#msg_hide_layer").fadeOut();
                $("#msg_layer").slideUp();
                $("#success_msg").slideUp();
                $("#fail_msg").slideUp();
            }
		}


        function ItemBuy(nItemID, nSendGiftFlag)  //nSendGiftFlag -> 1: Just Buy , 2: Gift
        {
			var szFriendInfo = "";

            if(nSendGiftFlag != 1) //for gift
            {
                if($(":input[name=friend]").val() == "")
                {
					alert("Please Select your friend");
                    return;
                }
				szFriendInfo = $(":input[name=friend]").val();
            }

            $.ajax({
                async: true, //false : synchronize, true : asynchronize
                type: "GET",
                url: "ChargeItem.php",
                cache: false,
                data: {
                    "itemid": nItemID,
					"sendgiftflag": nSendGiftFlag,
					"friendinfo": szFriendInfo
                } ,
                beforeSend: function(){
                    $("#action").fadeOut();
                },
                complete: function(){
                    //$("#right").fadeIn(100);
                },
                error: function(xmlHttp, textStatus, errorThrown){
                    $("#action").html(xmlHttp.responseText);
                    $("#action").fadeIn();
                },
                success: function(result){
                    $("#action").html(result);
                }
            });
        }
		
		function PurchaseSuccess(nItemID, nSendGiftFlag) {
			$("#success_msg").slideDown();
			DisplayRightItemAsync(nItemID, nSendGiftFlag);
		}
		
		function PurchaseSuccess2(nItemID, nSendGiftFlag) {
			$("#success_msg2").slideDown();
			DisplayRightItemAsync(nItemID, nSendGiftFlag);
		}
		
		function PurchaseFail(nItemID, nSendGiftFlag) {
			$("#fail_msg").slideDown();
			DisplayRightItemAsync(nItemID, nSendGiftFlag);
		}

        function goShopCategory(szCategoryID, noright)
        {
			if (szCategoryID == "panel" || szCategoryID == "index" || szCategoryID == "search")
			{
				$('a.main img').each(function(){
					$(this).attr('src',$(this).attr('src').replace('a.png','.png'));
				});
				$(".submenu").hide();
				
				if (szCategoryID == "index")
					DisplayPageContentAsync("GetItemList.php");
				
				return;
			}
			
            var sub_tab_id = "#" + $(".submenu a[href*='" + szCategoryID + "']").parent().attr("id");
            var main_tab_id = sub_tab_id.replace("#sub","#");

            //main
            $('a.main img').each(function(){
                $(this).attr('src',$(this).attr('src').replace('a.png','.png'));
            });
            $("img",main_tab_id).attr("src", $("img",main_tab_id).attr("src").replace(".png", "a.png"));

            //sub
            $(".submenu").hide();
            $(sub_tab_id).show();
            $('img', sub_tab_id).each(function(){
                $(this).attr('src',$(this).attr('src').replace('a.png','.png'));
                if($(this).parent().attr("href").indexOf(szCategoryID) != -1)
                {
                    $(this).attr('src',$(this).attr('src').replace('.png','a.png'));
                }
            });
			
			if (noright!='no') {
				var szUrl = "GetItemList.php?categoryid=" + szCategoryID + "&firstitemselect=yes";
				DisplayPageContentAsync(szUrl);
			}
			selected_main_category = szCategoryID;
        }
		
		function goPage(szCategoryID, szSearch, szPage) {
			<?php if ($enable_search) { ?>if (szSearch=='')
				<?php } ?>goShopCategory(szCategoryID, 'no');
			
			var szUrl = "GetItemList.php?categoryid=" + szCategoryID + "&page=" + szPage;
			<?php if ($enable_search) { ?>if (szSearch!='')
				var szUrl = szUrl + "&search=" + szSearch;
			<?php } ?>
			DisplayPageContentAsync(szUrl);
		}
		
		<?php if ($enable_search) { ?>function goSearchBox() {
            $("#msg_hide_layer").css({opacity: 0.7,filter: 'alpha(opacity=70)'}).fadeIn()
            $("#msg_layer").slideDown();
            $(".msg_layer_sub").hide();
			$("#search_box").slideDown();
			
			$("#p_searchbox").focus();
		}
		
		function goSearch()
		{
			var szSearch = $("#p_searchbox").val();
			$("#p_searchbox").val('');
			
			var szUrl = "GetItemList.php?categoryid=AAAAAAAAZ&search=" + szSearch + "&firstitemselect=yes";
			DisplayPageContentAsync(szUrl);
			
			goShopCategory('search');
			CloseWindow(false);
		}
		
		<?php } ?><?php if (in_array($account2,$editor_list)&&$enable_edit) { ?>function goBackendPanel()
		{
			DisplayPageContentAsync('BackendPanel.php');
			
			goShopCategory('panel');
			
            $.ajax({
                async: true,
                type: "GET",
                url: "BackendPanel.php",
                cache: false,
                data: {sidebar: true} ,
                beforeSend: function(){
                    $("#action").fadeOut();
                },
                complete: function(){
                    $("#right").fadeIn(100);
                },
                error: function(xmlHttp, textStatus, errorThrown){
                    $("#action").html(xmlHttp.responseText);
                    $("#action").fadeOut();
                },
                success: function(result){
                    $("#right").html(result);
                }
            });
		}
		
		function goPanelPage(szPanelPage)
		{
			DisplayPageContentAsync('BackendPanel.php?view='+szPanelPage);
		}

		<?php } ?>document.oncontextmenu=new Function('return false;');
		document.ondragstart=new Function('return false;');
		document.onselectstart=new Function('return false;');
    </script>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
</head>
<body>
    <div id="msg_hide_layer">&nbsp;</div>
    <div id="msg_layer">
        <table style="table-layout:fixed;width:100%;height:100%;" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td>
                    <div id="purchase_msg" class="msg_layer_sub">
                        <input id="p_yes" type="button" value="" onclick="CloseWindow(true)" />
                        <input id="p_no" type="button" value="" onclick="CloseWindow(false)" />
                    </div>
                    <div id="success_msg" class="msg_layer_sub">
                        <img id="close" src="images/close.png" alt="" onclick="CloseWindow(false)" />        
                    </div>
                    <div id="success_msg2" class="msg_layer_sub">
                        <img id="close" src="images/close.png" alt="" onclick="CloseWindow(false)" />        
                    </div>
                    <div id="fail_msg" class="msg_layer_sub">
                        <img id="close" src="images/close.png" alt="" onclick="CloseWindow(false)" />
                    </div><?php if ($enable_search) { ?>
					<div id="search_box" class="msg_layer_sub">
						<form action="javascript:return false;">
							<input id="p_searchbox" type="text" style="width: auto;" />
							<input id="p_search" type="submit" value="" onclick="goSearch();" />
							<input id="p_close" type="reset" value="" onclick="CloseWindow(false)" />
						</form>
					</div><?php } ?>
                </td>
            </tr>
        </table>
    </div>
    <div id="wrapper">
      <div id="header">
		  <a id="logo" href="javascript: goShopCategory('index');"><img src="images/logo.png" alt="<?php echo $shop_title; ?>" /></a>
		  <div id="menu">
				<div class="menublock">
					<a id="menu_1" href="javascript: goShopCategory('AAAAAAAAA');" class="main"><img src="images/menu_1.png" /></a><img src="images/menu_divider.png" class="divider"/>
					<div id="submenu_1" class="submenu">
						<a href="javascript: goShopCategory('AAAAAAAAA')"><img src="images/menu_1_1.png" /></a>
						<a href="javascript: goShopCategory('AAAAAAAAB')"><img src="images/menu_1_2.png" /></a>
						<a href="javascript: goShopCategory('AAAAAAAAC')"><img src="images/menu_1_3.png" /></a>
					</div>
				</div>
				<div class="menublock">
					<a id="menu_2" href="javascript: goShopCategory('AAAAAAAAD');" class="main"><img src="images/menu_2.png" /></a><img src="images/menu_divider.png" class="divider"/>
					<div id="submenu_2" class="submenu">
						<a href="javascript: goShopCategory('AAAAAAAAD');"><img src="images/menu_2_1.png" /></a>
						<a href="javascript: goShopCategory('AAAAAAAAE');"><img src="images/menu_2_2.png" /></a>
						<a href="javascript: goShopCategory('AAAAAAAAF');"><img src="images/menu_2_3.png" /></a>
					</div>
				</div>
				<div class="menublock">
					<a id="menu_3" href="javascript: goShopCategory('AAAAAAAAG');" class="main"><img src="images/menu_3.png" /></a><img src="images/menu_divider.png" class="divider"/>
					<div id="submenu_3" class="submenu">
						<a href="javascript: goShopCategory('AAAAAAAAG');"><img src="images/menu_3_1.png" /></a>
						<a href="javascript: goShopCategory('AAAAAAAAH');"><img src="images/menu_3_2.png" /></a>
						<a href="javascript: goShopCategory('AAAAAAAAI');"><img src="images/menu_3_3.png" /></a>
					</div>
				</div>
				<div class="menublock">
					<a id="menu_4" href="javascript: goShopCategory('AAAAAAAAJ');" class="main"><img src="images/menu_4.png" /></a><img src="images/menu_divider.png" class="divider"/>
					<div id="submenu_4" class="submenu">
						<a href="javascript: goShopCategory('AAAAAAAAJ');"><img src="images/menu_4_1.png" /></a>
						<a href="javascript: goShopCategory('AAAAAAAAK');"><img src="images/menu_4_2.png" /></a>
						<a href="javascript: goShopCategory('AAAAAAAAL');"><img src="images/menu_4_3.png" /></a>
						<a href="javascript: goShopCategory('AAAAAAAAM');"><img src="images/menu_4_4.png" /></a>
					</div>
				</div>
				<div class="menublock">
					<a id="menu_5" href="javascript: goShopCategory('AAAAAAAAN');" class="main"><img src="images/menu_5.png" /></a><img src="images/menu_divider.png" class="divider"/>
					<div id="submenu_5" class="submenu">
						<a href="javascript: goShopCategory('AAAAAAAAN');"><img src="images/menu_5_1.png" /></a>
						<a href="javascript: goShopCategory('AAAAAAAAO');"><img src="images/menu_5_2.png" /></a>
						<a href="javascript: goShopCategory('AAAAAAAAP');"><img src="images/menu_5_3.png" /></a>
						<a href="javascript: goShopCategory('AAAAAAAAQ');"><img src="images/menu_5_4.png" /></a>
					</div>
				</div>
				<div class="menublock">
					<a id="menu_6" href="javascript: goShopCategory('AAAAAAAAR');" class="main"><img src="images/menu_6.png" /></a><img src="images/menu_divider.png" class="divider"/>
					<div id="submenu_6" class="submenu">
						<a href="javascript: goShopCategory('AAAAAAAAR');"><img src="images/menu_6_1.png" /></a>
					</div>
				</div>
				<div class="menublock">
					<a id="menu_7" href="javascript: goShopCategory('AAAAAAAAS');" class="main"><img src="images/menu_7.png" /></a><img src="images/menu_divider.png" class="divider"/>
					<div id="submenu_7" class="submenu">
						<a href="javascript: goShopCategory('AAAAAAAAS');"><img src="images/menu_7_1.png" /></a>
						<a href="javascript: goShopCategory('AAAAAAAAT');"><img src="images/menu_7_2.png" /></a>
						<a href="javascript: goShopCategory('AAAAAAAAU');"><img src="images/menu_7_3.png" /></a>
						<a href="javascript: goShopCategory('AAAAAAAAV');"><img src="images/menu_7_4.png" /></a>
						<a href="javascript: goShopCategory('AAAAAAAAW');"><img src="images/menu_7_5.png" /></a>
					</div>
				</div>
				<div class="menublock">
					<a id="menu_8" href="javascript: goShopCategory('AAAAAAAAX');" class="main"><img src="images/menu_8.png" /></a>
					<div id="submenu_8" class="submenu">
						<a href="javascript: goShopCategory('AAAAAAAAX');"><img src="images/menu_8_1.png" /></a>
						<a href="javascript: goShopCategory('AAAAAAAAY');"><img src="images/menu_8_2.png" /></a>
					</div>
				</div>
			</div>
		</div>
		<div id="content">
			<div id="left">
				<div id="list_outer">
					<div id="list">
						<div id="item_cat"><b class="title"><font face='Tahoma' size='2'>เลือกประเภทสินค้าจากทางด้านบน</font></b></div>
						<div id="item_content">
							<div class="pagetitle warn"><img src="images/bullet.jpg" /><font face='Tahoma' size='2'>ประกาศ</font></div>
							<div id="notice">
								<p><font face='Tahoma' size='2'><?php echo $paragraph['notice']; ?></font></p>
							</div>
							<div class="pagetitle warn"><img src="images/bullet.jpg" /><font face='Tahoma' size='2'>คำเตือน</font></div>
							<div id="warning">
								<p><font face='Tahoma' size='2'><?php echo $paragraph['warning']; ?></font></p>
								<p class="highlight"><br/>
							</div>
						</div>
						<p id="page_nav"></p>
					</div>
				</div>
				<div id="bottom_menu"></div>
			</div>
			<div id="right">
				
            </div>
			<div class="clear"></div>
		</div>
		</div>
	</div>
    <div id="action" style="display:none;"></div>
</body>
</html>