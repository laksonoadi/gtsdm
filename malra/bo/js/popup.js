/*
@author     galih@gmail.com
@version    0.1
@copyright  2009
@lib : popup js require jquery.js, ui.core.js, ui.draggable.js, ui.resizable.js
@penggunaannya: showPopup(<url>,[<title>[,<widht>[,<height>[,<offsetLeft>[,<offsetTop>]]]]]);
@showPopup('http://localhost/gtFinansi/index.php', 'gtWindow Popup', 400, 300, 10, 20)
*/

function createNewWindow(title, objwidth, objheight, xpos, ypos)
{
   if (document.getElementById('popup-container') != undefined) return true;
   var a = new jElement();
   
   a.theElement = 'div';
   a.setElementOn = 'body-application';
   a.style="width: "+objwidth+"px; position: fixed; border: 1px solid #ddd; padding: 0px; left:"+xpos+"px; top:"+ypos+"px; background: #fff; z-index:999999; box-shadow: 0px 1px 5px -2px #333;";
   a.attribute = new Array('id|popup-container');
   a.createElement();
   
   a.theElement = 'div';
   a.setElementOn = 'popup-container';
   a.style="width: 100%; cursor:move; padding: 15px 15px 10px; border-bottom: 1px solid #ddd";
   a.attribute = new Array('id|popup-header');
   a.createElement();
   
   
   a.theElement = 'div';
   a.setElementOn = 'popup-header';
   a.style="";
   a.attribute = new Array('id|popup-title');
   a.createElement();
   /* 
   a.theElement = 'img';
   a.setElementOn = 'popup-title';
   a.style="float: right; cursor: pointer;";
   a.attribute = new Array('id|popup-close','src|images/popup/icon-close.gif');
   a.createElement();
   a.theElement.onclick = function(e)
   {
      var prnt = document.getElementById('body-application');
      var chld = document.getElementById('popup-container');
      $("#popup-container").fadeOut(300,function(){prnt.removeChild(chld);});
   } */
   a.theElement = 'button';
   a.setElementOn = 'popup-title';
   a.style="";
   a.attribute = new Array('id|popup-close','src|images/popup/icon-close.gif', 'class|close', 'title|Tutup popup');
   a.createElement();
	a.theElement.innerHTML = '&times;';
   a.theElement.onclick = function(e)
   {
      var prnt = document.getElementById('body-application');
      var chld = document.getElementById('popup-container');
      $("#popup-container").fadeOut(300,function(){prnt.removeChild(chld);});
   }
   
   a.theElement = 'h4';
   a.setElementOn = 'popup-title';
   a.style="font-family: Arial, Helvetica, sans-serif; margin: 0;";
   a.attribute = new Array('id|popup-title-text');
   a.createElement();
   a.theElement.innerHTML = title;
   
   /*a.theElement = 'div';
   a.setElementOn = 'popup-header';
   a.style="background: url(images/popup/icon-alert.gif) no-repeat ; position:absolute; left:5px; top:27px; height: 30px; width: 30px;";
   a.attribute = new Array('id|popup-icon');
   a.createElement();
   a.theElement.style.bgImage = 'icon-alert.gif';
   */
   /* a.theElement = 'div';
   a.setElementOn = 'popup-header';
   a.style="float: right; height: 30px; width: 30px;";
   a.attribute = new Array('id|popup-close');
   a.createElement(); */
   
   a.theElement = 'div';
   a.setElementOn = 'popup-container';
   a.style="width: 100%; height: "+(objheight)+"px; padding:0px; font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; overflow-y:auto;";
   a.attribute = new Array('id|popup-content');
   a.createElement();
   
   a.theElement = 'div';
   a.setElementOn = 'popup-content';
   a.style="width: 100%; padding: 10px; font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #000000;";
   a.attribute = new Array('id|popup-subcontent');
   a.createElement();
   
   $("#popup-container").hide();
   $("#popup-container").fadeIn(300); 
   
   return true;
}

function showPopup(url, title, width, height, offsetleft, offsettop)
{
   if (!url) return;
   if (!title) title="gtWindow Popup";
   
   if (!parseInt(width)) width = 240;
   else width = parseInt(width);
   
   if (!parseInt(height)) height = 400;
   else height = parseInt(height);
   
   if (!parseInt(offsetleft)) offsetleft = 0;
   else offsetleft = parseInt(offsetleft);
   
   if (!parseInt(offsettop)) offsettop = 0;
   else offsettop = parseInt(offsettop);
   
   var scrollX = (document.documentElement) ? document.documentElement.scrollLeft : document.body.scrollLeft;
   var scrollY = (document.documentElement) ? document.documentElement.scrollTop : document.body.scrollTop;
   
   var screenWidth = (document.documentElement) ? document.documentElement.clientWidth : document.body.clientWidth;
   var screenHeight = (document.documentElement) ? document.documentElement.clientHeight : document.body.clientHeight;
   
   var xpos = offsetleft + (screenWidth/2) - (width/2);
   var ypos = offsettop + (screenHeight/2) - (height/2);
   
   var popup;
   if (popup = document.getElementById("popup-container"))
   {
      $("#popup-subcontent").html('');
      $(popup).animate({left: xpos, top: ypos}, 300);
   }
   else
   {
      createNewWindow(title, width, height, xpos, ypos);
      $("#popup-header").mousedown(function(){
      $("#popup-container").draggable({opacity: 0.50});});
      
      $("#popup-header").mouseup(function(){
      $("#popup-container").draggable( 'disable' );
         if($('#popup-container').css('top').split('p')[0] < 0) $('#popup-container').css('top',0); 
         if($('#popup-container').css('left').split('p')[0] < 0) $('#popup-container').css('left',0); 
         if($('#popup-container').css('left').split('p')[0] > screenWidth-width) $('#popup-container').css('left',screenWidth-width-5);
         
      })
   }
   
   GtfwAjax.replaceContentWithUrl("popup-subcontent",url+"&ascomponent=1");
   
   return false;
}
