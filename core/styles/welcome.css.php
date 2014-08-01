<?php
/** DYNAMIC CSS FILE **/
if(session_status() == PHP_SESSION_NONE) {
	session_start();
}
$prim = $_SESSION['firstColor'];
$sec = $_SESSION['secondColor'];
?>
*{
	padding:0;
	margin:0;
	text-decoration:none;
	font-family:"Lato", sans-serif;
	list-style:none;
	font-size:1.2vw;
}
h1, h2, h3, h4, h5, h6{
	font-weight:200;
}
:focus{
    outline: none;
}
a{
	color:#fff;
}
html{
	background:#fff;
	color:<?php echo $prim; ?>;
}
/** start:placeholder **/
::-webkit-input-placeholder{
    color:<?php echo $prim; ?>;
}
:-moz-placeholder{
    color:<?php echo $prim; ?>;
    opacity:1;
}
::-moz-placeholder{
    color:<?php echo $prim; ?>;
    opacity:1;
}
:-ms-input-placeholder{
    color:<?php echo $prim; ?>;
}
input:disabled::-webkit-input-placeholder{
    color:#95a5a6;
}
input:disabled:-moz-placeholder{
    color:#95a5a6;
    opacity:1;
}
input:disabled::-moz-placeholder{
    color:#95a5a6;
    opacity:1;
}
input:disabled:-ms-input-placeholder{
    color:#95a5a6;
}
/** end:placeholder **/
/** start:input[type="text"], input[type="password"] and input[type="number"] **/
input[type="text"], input[type="number"],input[type="password"]{
	border: <?php echo $prim; ?> 1px solid;
	color:<?php echo $prim; ?>;
	font-size:250%;
	font-weight:200;
	width:98%;
	background-color:#FFF;
}
input[type="text"]:hover, input[type="text"]:active, input[type="text"]:focus, input[type="password"]:hover, input[type="password"]:active, input[type="password"]:focus, input[type="number"]:hover, input[type="number"]:active, input[type="number"]:focus{
	border: <?php echo $sec; ?> 1px solid;
}
/** end:input[type="text"], input[type="password"] and input[type="number"] **/
/** start:input[type="submit"] and input[type="button"] **/
input[type="submit"], input[type="button"]{
	height:3.3vw;
	width:98%;	
	background-color:<?php echo $prim; ?>;
	border:none;
	color:#fff;
	font-size:187.5%;
	cursor:pointer;
}
input[type="submit"]:hover, input[type="button"]:hover{
	background-color:<?php echo $sec; ?>;
}
input[type="submit"].join, input[type="button"].join{
	background-color:#f1c40f;
}
input[type="submit"].join:hover, input[type="button"].join:hover{
	background-color:#f39c12;
}
input[type="submit"].leave, input[type="button"].leave{
	background-color:#9b59b6;
}
input[type="submit"].leave:hover, input[type="button"].leave:hover{
	background-color:#8e44ad;
}
input[type="submit"].delete, input[type="button"].delete{
	background-color:#e74c3c;
}
input[type="submit"].delete:hover, input[type="button"].delete:hover{
	background-color:#c0392b;
}
input[type="submit"]:disabled, input[type="button"]:disabled, input[type="submit"]:disabled:hover, input[type="button"]:disabled:hover{
	color:#fff;
	background-color:#95a5a6;
	cursor:default;
}
/** end:input[type="submit"] **/
input[type="text"]:disabled{
	border:#95a5a6 1px solid !important;
	color:#95a5a6;
}
/** start:page **/
#page{
	position: absolute;
	top:50px;
	color:#000;
	font-weight:200;
	left:0;
	right:0;
	bottom:0;
	opacity:0;

	-moz-transition: all 2s ease-in-out;
	-webkit-transition: all 2s ease-in-out;
	transition: all 2s ease-in-out;
}
#page a{
	color:#000;
	font-weight:200;
}
/** end:page **/
/** start:menu **/
.nav-menu{
	margin-top:50px;
	width:80vw;
	margin-left:auto;
	margin-right:auto;
}
.nav-menu li{
	width: 16vw;
	height: 16vw;
	border: 10px solid #f6f6f6;
	overflow: hidden;
	position: relative;
	float:left;
	background: #fff;
	margin-right: -3vw;
	border-radius: 9999px;
	-moz-transition: all 400ms ease-in-out;
	-webkit-transition: all 400ms ease-in-out;
	transition: all 400ms ease-in-out;
}
.nav-menu li:hover{
	border-color:<?php echo $prim; ?>;
	-moz-transform: scale(1.1);
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
    z-index:102;
}
.nav-menu li:hover .nav-icon:before{
	color:<?php echo $prim; ?>;
}
.nav-icon:before{
	line-height: 10vw;
    position: absolute;
    width: 100%;
    height: 50%;
    left: 0px;
    top: 0px;
    text-align: center;
    transition: all 400ms linear;
    font-size:7vw;
    color:#f6f6f6;
}
.nav-content{
    position: absolute;
    left: 0px;
    width: 100%;
    height: 50%;
    top: 55%;
}
.nav-main{
    font-size: 2.2vw;
    color: <?php echo $prim; ?>;
    text-align: center;
    transition: all 400ms linear;
}
.nav-menu:after{
	clear:both;
}
/** end:menu **/
/** start:intro **/
#title{
	font-size:25vw;
	font-weight:100;
	width:79vw;
	opacity:0;
	position:fixed;
	overflow:hidden;
	top:10vw;
	left:-1vw;
	z-index:101;
	
	-moz-transition: all 2s ease-in-out;
	-webkit-transition: all 2s ease-in-out;
	transition: all 2s ease-in-out;
}
.title_in{
	opacity:1 !important;
	top:-6vw !important;
}
.title_fix{
	top:-0.75vw !important;
	left:0 !important;
	font-size:4vw !important;
	color:#fff;
}
#welcome, #to{
	font-weight:200;
	position:fixed;
	font-size:5vw;
	top:5vh;
	
	-moz-transition: all 2s ease-in-out;
	-webkit-transition: all 2s ease-in-out;
	transition: all 2s ease-in-out;
    
    -moz-transform: scale(0) rotate(30deg);
    -webkit-transform: scale(0) rotate(30deg);
    transform: scale(0) rotate(30deg);
}
#welcome{
	left:10vw;
}
#to{
	left:40vw;
}
.scaleWel{
	-moz-transform: scale(1) rotate(10deg) !important;
    -webkit-transform: scale(1) rotate(10deg) !important;
    transform: scale(1) rotate(10deg) !important;
}
.scaleTo{
	-moz-transform: scale(1) rotate(-5deg) !important;
    -webkit-transform: scale(1) rotate(-5deg) !important;
    transform: scale(1) rotate(-5deg) !important;
}
.topOut{
	top:-100vh !important;
}
/** end:intro **/
/** start:menubar **/
#menuBar{
	height:3.6vw;
	width:100%;
	top:0;
	left:0;
	right:0;
	position: fixed;
	background-color:<?php echo $prim; ?>;
	z-index:100;
	opacity:0;
	color:#fff;
	
	-moz-transition: all 2s ease-in-out;
	-webkit-transition: all 2s ease-in-out;
	transition: all 2s ease-in-out;
}
#menuBar > ul{
	float:right;
	margin-top:10px;
}
#menuBar > ul li.seperator{
	margin-top: 10px;
	border-top:#FFF 1px solid;
}
#menuBar > ul, #menuBar > ul >li{
	display:inline;
}
#menuBar > ul > li{
	margin-right:60px;
}
#menuBar li{
	font-size:187.5%;
}
#menuBar li a{
	font-size:85%;
}
.drop-down-arrow{
	cursor:pointer;
	position: relative;
}
.drop-down-arrow:after{
	width: 0; 
	height: 0; 
	margin-top:18px;
	margin-left:5px;
	border-left: 10px solid transparent;
	border-right: 10px solid transparent;
	border-top: 10px solid #fff;
	content:"";
	position: absolute;
}
.drop-down-arrow > .submenu{
	position: absolute;
	background-color:<?php echo $prim; ?>;
	padding:0px 5px 5px 5px;
	transition: all .3s ease-in-out;
	opacity:0;
	z-index:-1;
	margin-top:-300px;
	white-space: nowrap;
}
a:hover, a:focus, .drop-down-arrow:hover, .drop-down-arrow:focus{
	border-bottom: #fff 1px solid;
}
.drop-down-arrow:focus > .submenu{
	opacity:1;
	z-index:1;
	margin-top:10px;
}
/** end:menubar **/
/** start:error **/
#error{
	margin-top:20px;
	text-align: center;
	font-size: 2.2vw;
	color:#e74c3c;
}
/** end:error **/
/** start:windows **/
#windows{
	position:absolute;
	top:0;left:0;right:0;bottom:0;
}
#login:target, #login:target ~ #overlay, #login:target ~ #close, #register:target, #register:target ~ #overlay, #register:target ~ #close, #resetPW:target, #resetPW:target ~ #overlay, #resetPW:target ~ #close{
	opacity:1;
	z-index:200;
}
#login:target, #login:target ~ #close, #register:target, #register:target ~ #close, #resetPW:target, #resetPW:target ~ #close{
	z-index:210;
}
#login:target ~ #overlay, #register:target ~ #overlay{
	z-index:200;
}
.window{
	position:absolute;
	top:3vw;left:3vw;right:3vw;
	padding-bottom:3vw;
	background-color:#fff;
	z-index:-1;
	opacity:0;
	
	-moz-transition: all .4s linear;
	-webkit-transition: all .4s linear;
	transition: all .4s linear;
}
#close{
	position: absolute;
	top:3vw;
	right:4%;
	z-index:-1;
	opacity:0;
	
	-moz-transition: all .4s linear;
	-webkit-transition: all .4s linear;
	transition: all .4s linear;
}
#close a{
	color:#e74c3c;
	font-size:2.5vw;
}
.windowContent{
	position:relative;
	margin-left:2%;
}
.window h1{
	font-size:3.5vw;
	margin-left:-1%;
}
#overlay{
	position:fixed;
	top:0;left:0;right:0;bottom:0;
	background-color:rgba(0,0,0,0.5);
	z-index:-1;
	opacity:0;
	
	-moz-transition: all .4s linear;
	-webkit-transition: all .4s linear;
	transition: all .4s linear;
}
/** end:windows **/
.vis{
	opacity:1 !important;
}
.clear{
	clear:both;
}
/** start:media queries **/
@media screen and (max-device-width: 480px){
	*{
		font-size:2.5vw;
		font-weight:normal !important;
	}
	.window h1{
		font-size:10vw !important;
	}
	#close a{
		font-size:6vw;
	}
	#menuBar{
		height:10vw;
	}
	.title_fix{
		font-size:9vw !important;
	}
	.nav-menu{
		margin:0;
		margin-top:5vw;
		width:80vw;
	}
	.nav-menu li{
		width: 100%;
		height: 10vw;
		border: 0px solid rgba(0,0,0,0);
		background: <?php echo $prim; ?>;
		border-radius: 0;
		margin-top:3vw;
	}
	.nav-icon:before{
		line-height: 10vw;
	    position: absolute;
	    left:3vw;	    
	    height: 100%;
	    width: auto;
	    font-size:7vw;
	}
	.nav-content{
	    top:1.8vw;
		left:12vw;
	}
	.nav-main{
		color:#fff;
	    font-size: 5vw;
	    font-weight:normal;
	    text-align: left;
	    left:6vw;
	}
	.nav-menu li:hover{
		-moz-transform: translateX(10%);
    	-webkit-transform: translateX(10%);
		transform: translateX(10%);
	}
	input[type="submit"], input[type="button"]{
		height:7vw;
	}
	#error{
		font-size: 5vw;
		text-align:left;
	}
}
/**iPhone 3,4**/@media screen and (device-aspect-ratio: 2/3) {
	
}
/**iPhone 5**/@media screen and (device-aspect-ratio: 40/71) {
	
}
/**iPad+iPad Mini**/@media screen and (device-aspect-ratio: 3/4) {
	
}
/** end:media queries **/