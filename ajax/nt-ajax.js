function requestPage(url, callBack){
	startLoading();
	if(url.indexOf("?")==-1){
		$.get( url+"?ajax=true", function( data ) {
		    callBack(data);
		    stopLoading();
		  });
	}else{
		$.get( url+"&ajax=true", function( data ) {
		    callBack(data);
		    stopLoading();
		  });
	}
}
function loadPage(url){
	requestPage(url, function(data){$("#page").html(data);
	var id;
	$("select").each(function(index){
		$(this).flexselect();
		id = $(this).attr("id");
		$("#"+id+"_flexselect").attr("placeholder", $(this).attr("placeholder"));
		$("#"+id+"_flexselect").val("");
	});
	});
}
$(window).on('hashchange', function(){
	checkHash();
});
$(document).ready(function(){
	checkHash();
});
function checkHash(){
	var hash = (window.location.hash);
	if(typeof hash !== 'undefined' && hash != null && hash!=""){
		hash = hash.substring(1);
		if(hash=="grades"){
			loadPage("/");
		}
		if(hash.indexOf("page:")==0){
			loadPage(hash.split(":")[1]);
		}
	}
}
function startLoading(){
	/*$( '<div id="loadingBackground"></div>' ).appendTo( 'body' );
	$( '<div id="loading"><div></div></div>' ).appendTo( 'body' );*/
	$("html").css("cursor", "progress");
}

function stopLoading(){
	/*$( '#loading' ).remove();
	$( '#loadingBackground' ).remove();*/
	$("html").css("cursor", "auto");
}
$(document).on('submit', 'form', function(e) {
	e.preventDefault();
    var form = $(e['currentTarget']);
    if(form.attr("warning")=="true"){
    	alertify.confirm(form.attr("message"), function (e) {
    	    if (e) {
    	    	_pData(form);
    	    } else {
    	        
    	    }
    	});
    }else{
    	_pData(form);
    }
});
function _pData(form){
	postData(form.attr("action"), form, function(form){
    	if(window.location.hash=="#page:"+form.attr("callBackUrl")){
    		window.location.hash = "grades";
    	}
    	window.location.hash = "page:"+form.attr("callBackUrl");
    });
}
function postData(urlToPost, form, callBack){
	startLoading();
	form.find("input").attr("disabled", true);
    form.find(".checkboxContainer").addClass("disabled");
    var stringData = "";
    var br = false;
    form.find("input").each(function(index){
    	if($(this).attr("type")!="submit"){
    		if($(this).val().indexOf(":")>-1){
    			alertify.error($(this).attr("placeholder") + " must not contain the char ':'!");
    			br = true;
    		}else{
    			if($(this).attr("type")=="checkbox"){
    				stringData += $(this).attr("name") + ":" + $(this).is(':checked') +";";
    			}else{
    				stringData += $(this).attr("name") + ":" + $(this).val() +";";
    			}
    		}
    	} 
    });
    if(br == true){
    	form.find("input").attr("disabled", false);
        form.find(".checkboxContainer").removeClass("disabled");
        stopLoading();
    	return false;
    }
    $.post( urlToPost, { data: stringData},function( data ) {
    	if(data == "true" || data == true){
    		alertify.success(successText);
    		callBack(form);
    	}else{
    		alertify.error(data);
    	}
    	form.find("input").attr("disabled", false);
        form.find(".checkboxContainer").removeClass("disabled");
        stopLoading();
    });
}