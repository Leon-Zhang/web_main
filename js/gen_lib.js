

//Include a javascript from another.
function IncludeJavaScript(jsFile)
{
  document.write('<script type="text/javascript" src="'
    + jsFile + '"></scr' + 'ipt>'); 
  /*var js = document.createElement("script");

  js.type = "text/javascript";
  js.src = jsFilePath;

  document.body.appendChild(js);*/
}

function isNumericKey(keycode)
{
 var regex = /[0-9]|\./;
 return (regex.test(String.fromCharCode( keycode )));
}

//call sample: <input name="user_name" onkeypress="validate_numericinput(this,event)" />
function validate_numericinput(evt)
{
 var theEvent = evt || window.event;
 var keycode = theEvent.keyCode || theEvent.which;
 if( !isNumericKey(keycode) ) {
  theEvent.returnValue = false;
  theEvent.preventDefault();
 }
}

function validate_numericinput_entersubmit(field,evt)
{
 var theEvent = evt || window.event;
 var keycode = theEvent.keyCode || theEvent.which;
 if (keycode == 13){
  field.form.submit();
  theEvent.returnValue = false;
 }else{
  if( !isNumericKey(keycode) ) {
   theEvent.returnValue = false;
   theEvent.preventDefault();
  }
 }
}

function isCheckboxsChecked(chkboxes)
{
 for(var i=0;i<chkboxes.length;i++){
  if(chkboxes[i].checked){
   return true;
  }
 }
 return false;
}

function isEmailLegal(str)
{
 var at="@";
 var dot=".";
 var ddot="..";
 var lat=str.indexOf(at);
 var lstr=str.length;
 var ldot=str.indexOf(dot);
 if(str.indexOf(at)==-1){
  return false;
 }
 if(str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
  return false;
 }
 if(str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
  return false;
 }
 if(str.indexOf(at,(lat+1))!=-1){
  return false;
 }
 if(str.indexOf(ddot)!=-1){
  return false;
 }
 if(str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
  return false;
 }
 if(str.indexOf(dot,(lat+2))==-1){
  return false;
 }
 if(str.indexOf(" ")!=-1){
  return false;
 }
 return true;
}


function CloseWindow_Confirm(msg)
{
 if(confirm(msg)){
  window.close();
 }
}

function RunFile(file) {
 var ws = new ActiveXObject("WScript.Shell");
 ws.run(file);
}


//IncludeJavaScript('json2.js');

var ajax = {};
ajax.name = "Ajax";
ajax.xmlhttpPost = function(strURL,strVars,fn_resp_addr,fn_jresp_addr,fn_xmlresp_addr,strResponsePrefix,
strResponseSuffix)
{
	var xmlHttpReq = false;
    var self = this;
    // Mozilla/Safari
    if(window.XMLHttpRequest) {
        self.xmlHttpReq = new XMLHttpRequest();
    }
    // IE
    else if(window.ActiveXObject) {
        self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    self.xmlHttpReq.open('POST', strURL, true);
    self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    self.xmlHttpReq.onreadystatechange = function() {
     if(self.xmlHttpReq.readyState == 4) {
     	var strResponseText=strResponsePrefix+self.xmlHttpReq.responseText+strResponseSuffix;
			if(fn_jresp_addr!=null){
				if(window.JSON){
					var json_obj=JSON.parse(self.xmlHttpReq.responseText);
					fn_jresp_addr(json_obj);
				}else{
				}
			}else if(fn_xmlresp_addr!=null){
				//alert(self.xmlHttpReq.responseText);
				if (window.DOMParser)
				{
					parser=new DOMParser();
					xmlDoc=parser.parseFromString(strResponseText,"text/xml");
				}else // Internet Explorer
				{
					xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
					xmlDoc.async="false";
					xmlDoc.loadXML(strResponseText);
				}
				fn_xmlresp_addr(xmlDoc);
			}else if(fn_resp_addr!=null){
				fn_resp_addr(self.xmlHttpReq.responseText);
			}
     }
    }
    self.xmlHttpReq.send(strVars);
}

function ShowHideDiv(id)
{
 lay=document.getElementById(id);
 if(lay.style.visibility=="visible"){
	lay.style.visibility="hidden";
	lay.style.display="none";
 }else{
	lay.style.visibility="visible";
	lay.style.display="inline";
 }
}

//function AlignElementPos(elem_align_name,elem_host_name,top_off,left_off,right_off,bottom_off)
function AlignElementPos(elem_align_name,elem_host_name)
{
 elem_align=document.getElementById(elem_align_name);
 elem_host=document.getElementById(elem_host_name);
 
 elem_align.style.top=elem_host.style.top;
 elem_align.style.right=elem_host.style.width;
}
