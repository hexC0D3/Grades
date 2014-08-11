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
	font-size:16px;
}
body, body a{
	color:#fff;
}
:focus{
    outline: none;
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
/** start:title **/
h1, h2, h3, h4, h5, h6{
	color:<?php echo $prim; ?>;
	font-weight:200;
	float:left;
}
h1{
	margin-bottom:40px;
}
h1:after, h2:after, h3:after, h4:after, h5:after, h6:after{
	clear:both;
}
h1{
	font-size:250%;
	border-bottom:<?php echo $prim; ?> 2px solid;
}
h2{
	font-size:150%;
	margin-bottom:20px;
}
h1 a, h2 a{
	font-size:100%;
}
/** end:title **/
/** start:menubar **/
#menuBar{
	height:50px;
	width:100%;
	top:0;
	left:0;
	right:0;
	position: fixed;
	background-color:<?php echo $prim; ?>;
	z-index:100;
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
	font-size:70%;
}
.title{
	font-size:312.5%;
	margin-left:10px;
	line-height:100%;
}
.title:hover{
	border:none;
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
/** start:page **/
#page{
	margin-top:calc(50px + 2%);
	margin-left:2%;
	color:#000;
	position: relative;
	z-index:10;
}
#page a{
	color:<?php echo $prim; ?>;
}
#page a:hover{
	border-color:<?php echo $prim; ?>;
}
/** start:textarea **/
textarea{
	color:<?php echo $prim; ?>;
	font-size:150%;
	font-weight:200;
	border:1px solid <?php echo $prim; ?>;
	max-width:98%;
	width:98%;
	min-width:98%;
	max-height:30vw;
	height:30vw;
	min-height:30vw;
}
/** end:textarea **/
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
	height:40px;
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
table input[type="submit"], input[type="button"]{
	width:calc(25% - 6px);
	float:left;
	margin-right:2px;
	font-size:125%;
	font-weight:200;
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
/** start:flatSelect **/
.flexselect_dropdown {
	display: none;
	position: absolute;
	z-index: 999999;
	margin-top: -1px;
	border: 1px solid <?php echo $prim; ?>;
	max-height: 200px;
	overflow-x: hidden;
	overflow-y: auto;
	background-color: #fff;
	color: <?php echo $prim; ?>;
	text-align: left;
}
.flexselect_dropdown ul {
	width: 100%;
	list-style: none;
	padding: 0;
	margin: 0;
}
.flexselect_dropdown li {
	margin: 0px;
	padding: 2px 5px;
	cursor: pointer;
	display: block;
	width: 100%;
	overflow: hidden;
}
.flexselect_selected {
	/*background-color: <?php echo $prim; ?>;*/
	color: <?php echo $sec; ?>;
}
/** end:flatSelect **/
/** start:flatCheckbox **/
.checkboxContainer{
	border: <?php echo $prim; ?> 1px solid;
	height:50px;
	width:98%;
}
.checkboxContainer.disabled .checkbox_label{
	color:#95a5a6;
}
.checkboxContainer.disabled{
	border:#95a5a6 1px solid !important;
}
.checkbox_label{
	font-size:250%;
	font-weight:200;
	padding-right:40px;
	color:<?php echo $prim; ?>;
}
input[type="checkbox"]{
	display:none;
}
input[type="checkbox"] + label{
	position:absolute;
	margin-top:5px;
	width: 40px;
	height: 40px;
	background-color: <?php echo $prim; ?>;
	display: inline-block;
}
input[type="checkbox"]:checked + label {
	background-color: <?php echo $sec; ?>;
}
input[type="checkbox"]:checked + label:after {
	top:4px;
	position: absolute;
	padding-left:10px;
	color: #fff;
	font-size: 156.25%;
	content: '\2715';
}
input[type="checkbox"]:disabled + label{
	background-color: #95a5a6;
}
input[type="checkbox"]:disabled + span{
	color:#95a5a6;
}
/** end:flatCheckbox **/
/** start:table **/
table{
	width:100%;
}
thead{
	color:<?php echo $prim; ?>;
}
th{
	text-align:left;
	font-size:156.25%;
}
th.actions{
	width:35%;
}
tbody{
	
}
tfooter{
	
}
tr:nth-child(even){
	background-color: rgba(166, 166, 166,0.08);
}
td{
	
}
/** end:table **/
/** start:#gradesTable **/
#gradesTable{
	width:60%;
	position:relative;
	float: left;
}
#gradesTable td:not(.subject){
	border-left: rgb(200,200,200) 1px solid;
}
#gradesTable td{
	font-weight:200;}

#gradesTable tr{
	border:none;
}
.subject{
	font-size:250%;
	color:#000;
	width:100px;
	max-width: 200px;
	overflow: hidden;
	white-space: nowrap;
	text-overflow:ellipsis;
}
.subject a{
	font-size:100%;
	color:#000 !important;
}
.mark{
	font-size:187.5%;
	color:#000;
	text-align:center;
	width:70px;
	white-space: nowrap;
}
.negativeMark{
	color:#e74c3c;
}
.averageMark{
	font-size:250%;
	width:15%;
}
/** end:#gradesTable **/
/** start:chart **/
#theChart{
	float:left;
	margin-left:1%;
	max-width:34%;
}
#averageMark{
	margin-left: 60.8%;
	font-weight:200;
}
#averageMark{
	font-size: 187.5%;
}
#averageMark span{
	font-size:100%;
}
/** end:chart **/
/** end:page **/
/** start:datepicker **/
#ui-datepicker-div{
	border: 1px <?php echo $prim; ?> solid;
	background-color:#fff;
	margin-top:-1px;
}
#ui-datepicker-div, #ui-datepicker a, .ui-datepicker a{
	color:<?php echo $prim; ?>;
}
#ui-datepicker a, .ui-datepicker a:hover{
	border:none;
}
.ui-datepicker table {
	width:100%;
}
.ui-datepicker-title {  
    text-align: center;  
}
.ui-datepicker-next{
	float:right;
}
#ui-datepicker-div td, #ui-datepicker-div th{
	width:30px;
}
.ui-datepicker-next:after{
	clear:both;
}
.ui-datepicker-prev, .ui-datepicker-next{
	cursor:pointer;
}
.ui-state-disabled{
	color:#95a5a6 !important;
}
.ui-state-active{
	color:<?php echo $sec; ?> !important;
}
/** end:datepicker **/
.clear{
	clear: both;
}
/** start:loading
#loadingBackground{
	position:absolute;
	top:0;
	left:0;
	right:0;
	bottom:0;
	background-color:rgba(0,0,0,0.5);
	z-index:9999998;
}
#loading, #loading div{
	-webkit-border-radius: 50%;
	-moz-border-radius: 50%;
	border-radius: 50%;
	z-index:9999999;
}
#loading{
	width: 2.5em;
	height: 2.5em;
	background-color: #444;
	background-color: rgba( 0, 0, 0, .5 );
	position: fixed;
	top: 50%;
	left: 50%;
	padding: 0.625em;
	margin: -1.25em 0 0 -1.25em;
	-webkit-box-shadow: 0 0 2.5em rgba( 0, 0, 0, .75 );
	-moz-box-shadow: 0 0 2.5em rgba( 0, 0, 0, .75 );
	box-shadow: 0 0 2.5em rgba( 0, 0, 0, .75 );
}
#loading div{
	width: 100%;
	height: 100%;
	background-color: #fff;
	-webkit-animation: loading .5s ease infinite;
	-moz-animation: loading .5s ease infinite;
	-o-animation: loading .5s ease infinite;
	animation: loading .5s ease infinite;
}
@-webkit-keyframes loading{
	from { opacity: .5;	-webkit-transform: scale( .75 ); }
	50%	 { opacity: 1;	-webkit-transform: scale( 1 ); }
	to	 { opacity: .5;	-webkit-transform: scale( .75 ); }
}
@-moz-keyframes loading{
	from { opacity: .5;	-moz-transform: scale( .75 ); }
	50%	 { opacity: 1;	-moz-transform: scale( 1 ); }
	to	 { opacity: .5;	-moz-transform: scale( .75 ); }
}
@-o-keyframes loading{
	from { opacity: .5;	-o-transform: scale( .75 ); }
	50%	 { opacity: 1;	-o-transform: scale( 1 ); }
	to	 { opacity: .5;	-o-transform: scale( .75 ); }
}
@keyframes loading{
	from { opacity: .5;	transform: scale( .75 ); }
	50%	 { opacity: 1;	transform: scale( 1 ); }
	to	 { opacity: .5;	transform: scale( .75 ); }
}
/** end:loading **/
/** hide mobile stuff **/
#mobileMenuTrigger{
	display:none;
}
/** start:media queries **/
@media screen and (max-device-width: 480px){
	*{
		font-size:2.5vw;
		font-weight:normal !important;
	}
	a:focus, a:hover{
		border:none;
	}
	#page{
		margin-top:8.5vw;
	}
	#mobileMenuTrigger{
		display:block;
		position:absolute;
		left:2%;
	}
	#mobileMenuTrigger:before{
		color:#fff;
		font-size:8vw;
		margin-left:2%;
		float: left;
	}
	#mobileMenuTrigger:after{clear:both;}
	.title{
		margin-left:10%;
	}
	#menuBar > ul li.seperator{
		margin-top: 0;
		border:none;
	}
	#menuBar ul, #menuBar li{
		margin-top:-50vh;
	}
	#menuBar:target ul, #menuBar:target li{
		margin-top:0;
	}
	#menuBar{
		height:8.5vw;
	}
	#menuBar:target{
		position: absolute;
		height: auto;
	}
	#menuBar:target ul .submenu{
		opacity:1 !important;
		position:relative;
	}
	#menuBar:target ul{
		display:block;
		float:none;
	}
	#menuBar:target > ul{
		margin-top:5vw;
	}
	#menuBar:target li{
		display:block !important;	
		font-size:10vw;
		display: block;
		border: none;
	}
	#menuBar:target li a{
		font-size:7vw;
		display:block;
	}
	#menuBar > ul > li{
		margin-right:0;
	}
	.drop-down-arrow:after{
		display:none;
		z-index:-1;
	}
	.drop-down-arrow > .submenu{
		z-index:1;
	}
	table, #gradesTable{
		width:98%;
	}
	.actions{
		display:none;
	}
	#gradesTable{
		margin-top:4vw;
	}
	#gradesTable *{
		font-size:4vw;
	}
	#theChart{
		max-width:100%;
		margin-top:5vw;
	}
	#averageMark{
		margin-left: 3%;
		margin-top:3vw;
	}
	#averageMark{
		font-size: 200%;
	}
	#averageMark span{
		font-size:100%;
	}
	input[type="submit"], input[type="button"]{
		height:7vw;
	}
	/** mobile menu **/
}