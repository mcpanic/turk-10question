<?php
/*
	For videos that are available on YouTube: YouTube API is used.
*/
define("DB_HOST", "50.116.6.114");    // MySQL host name
define("DB_USERNAME", "turk_10questions");    // MySQL username
define("DB_PASSWD", "xLhbByt5AHZa2NQS");    // MySQL password
define("DB_NAME", "turk_10questions");    // MySQL database name. vt.sql uses the default video_learning name. So be careful.

$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWD, DB_NAME);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

$id = $_GET["id"];
$workerId = $_GET["workerId"];
$assignmentId = $_GET["assignmentId"];

$video = array();
$result = $mysqli->query("SELECT * FROM segments WHERE id = '$id'");
while($entry = $result->fetch_assoc()){
	$video = $entry;
}

$didTutorial = FALSE;
$result = $mysqli->query("SELECT * FROM worker_tutorial WHERE workerid = '$workerId'");
// echo $result->num_rows . " rows returned";
if ($result->num_rows == 1)
	$didTutorial = TRUE;

$questionArray = array(
	"1.	The person is reserved",
	"2.	The person is generally trusting", 
	"3.	The person tends to be lazy", 
	"4.	The person is relaxed, handles stress well", 
	"5.	The person has few artistic interests", 
	"6.	The person is outgoing, sociable", 
	"7.	The person tends to find fault with others", 
	"8.	The person does a thorough job", 
	"9.	The person gets nervous easily", 
	"10. The person has an active imagination"
);
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Perceived Personality Study</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">		
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,300,400italic,400,700' rel='stylesheet' type='text/css'>
    <style>
        body {
            padding-top: 20px;
            padding-bottom: 40px;
            /*width: 800px;*/
        }
        #preview {
        	text-align: center;
        }
        #preview img {
        	width: 640px;
        }
		#title h2{
			text-align: center;
		}
        #interviewee-prior{
        	border: 1px gray solid;
        	padding: 10px;
        	margin: 20px;
        }
		#preview, #tutorial, #interviewee-prior-header, #interviewee-prior, #inst, #video-player, #questions, #demographics, #final{
			display: none;
		}	
		#tutorial input[type=radio]{
			margin-left: 40px;
		}		
		#tutorial .header-agree{
			margin-left: 70px;
		}
		.tutorial-success {
			display: none;
		}
		.tutorial-question{
			font-size: 16px;
			font-weight: bold;			
			height: 25px;
		}
		.tutorial-header{
			font-size: 18px;
			font-weight: bold;
			margin-top: 30px;
			margin-bottom: 5px;
		}	
		#tutorial-player{
			text-align: center;
		}
		.warning {
			color: red;
		}
		.interviewee-name {
			font-weight: bold;
			font-size: 16px;
			margin-top: 10px;
			text-align: center;
		}
        .interviewee-photo img, .interviewer-photo img{
        	max-width: 100%;
        	max-height: 150px;
        }
        .interviewer-photo img{
        	width: 120px;
        }
		.a-doyouknow input[type=radio]{
			margin-left: 30px;
		}
		.a-confidence input[type=radio]{
			margin-left: 10px;
			/*margin-right: 5px;*/
		}

		.q-confidence, .a-confidence{
			display: none;
		}

		.a-confidence .left-label, .a-confidence .right-label{
			font-weight: bold;
		}
		#ytplayer{
			margin-left: 50px;
		}
		#errorMsg {
			display: none;
			/*background: yellow;*/
			padding: 10px;
			font-weight: bold;
			width: 640px;
			margin-left: 50px;
		}		
		.questions {
			/*margin: 10px;*/
		}
        .question-interviewer, .question-interviewee{
        	margin: 20px;
        }
        .question-header{
        	height: 180px;
        	position: relative;
        	text-align: center;
        }
        .question-header div{
        	margin-left: 20px;
        	position:absolute; 
        	/*top:50%; */
        	bottom: 0;
        }
        .question-header .desc, .tutorial-header .desc{
        	font-weight: bold;
        	font-size: 16px;
        }
        .question-header div img{
        	top:-100px;
        }
		.question-text{
			font-size: 18px;
			font-weight: bold;
			margin-top: 30px;
			margin-bottom: 5px;
		}
        .question input[type=radio]{
        	margin-left: 20px;
        }		
        #demographics input[type=radio]{
        	margin-left: 20px;
        }
        #demographics div{
        	margin-bottom: 15px;
        }        
        #demographics span{
        	display: inline-block;
        	width: 50px;
        }        
		.disclaimer{
			font-size:0.8em;
		}
		#taskSub{
			padding:20px 100px 20px 100px;
		}
		label:hover{
			cursor: pointer;
			color: steelblue;
		}
		label {
			font-weight: normal !important;
		}
    </style>
	<!-- <link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.8.22.custom.css" type="text/css" /> -->
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="css/style.css" type="text/css" />
    <!-- // <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script> -->
</head>

<body>
	<div class="container">
		<div id="title" class="row">
			<h2 class="col-sm-12 col-md-12 col-lg-12">
				How do you perceive the personality of the others?
			</h2>
			<br/>
			<div>
				We want to understand how people perceive the personality of the others. <br/>
				This questionnaire will gather data to help answer that question.
			</div>
		</div>		

		<div id="preview" class="row">
			<br/>
			<div class="col-sm-12 col-md-12 col-lg-12">
				<img src="img/preview-screenshot-clooney.png">
			</div>
		</div>

	<form id="submitForm" action="https://www.mturk.com/mturk/externalSubmit" method="POST">

		<br/>
		<div id="tutorial" class="row">
			<h3 class="instruction">
				Tutorial Session
			</h3>
			<ul>
				<li>In this tutorial you will watch a video clip and answer 10 questions regarding personality. </li>
				<br/>
				<li>After this tutorial, we want you to be comfortable answering the 10 questions on different video.</li>
				<br/>
				<li>You will have to do this tutorial only once. If you decide to do other HITs from us, you won’t see this tutorial again.</li>
				<br/>
				<li>Please avoid preconception. Please answer each question based just on what you see in the video. Avoid answering each question based on your prior knowledge of the person.</li>
				<br/>
			</ul>
			<div id="tutorial-player">
				<iframe id="tutplayer" type="text/html" width="640" height="390"
				  src="http://www.youtube.com/embed/Hty9p4AHPZA?start=58&end=91&version=3&autoplay=0&controls=0&rel=0&showinfo=0&origin=http://juhokim.com" frameborder="0"></iframe>
			</div>
			<br/>
			<div class="col-sm-12 col-md-12 col-lg-12">
				<div class="tutorial-question">Answer the 10 questions. </div>
				<div class="warning">Remember! Please do not use your preconception of the person to answer these questions.</div>				
			</div>

<?php
	    echo "<div class='col-sm-6 col-md-6 col-lg-6'>";
	    echo "<div class='tutorial-header'><span>The person in the video </span></div>";
	    for($i=0; $i<10; $i++){
	    	echo "<div class='tutorial-question'><span class='tutorial-question-text'>" . $questionArray[$i] . "</span>";
	    	echo "</div>";
	    }
	    echo "</div>";
	    echo "<div class='col-sm-6 col-md-6 col-lg-6'>";
	    echo "<div class='tutorial-header'><span class='desc'>Disagree strongly</span>   <span class='desc header-agree'>Agree strongly</span></div>";
	    for($i=0; $i<10; $i++){
	    	echo "<div class='tutorial-question'>";
	    	echo "<label><input type='radio' name='question-tutorial-" . ($i+1) . "' value='1'/></label>";
	    	echo "<label><input type='radio' name='question-tutorial-" . ($i+1) . "' value='2'/></label>";
	    	echo "<label><input type='radio' name='question-tutorial-" . ($i+1) . "' value='3'/></label>";
	    	echo "<label><input type='radio' name='question-tutorial-" . ($i+1) . "' value='4'/></label>";
	    	echo "<label><input type='radio' name='question-tutorial-" . ($i+1) . "' value='5'/></label>";
	    	echo "</div>";
	    }
	    echo "</div>";
	    
?>			
		<div>
			<button class="btn btn-primary btn-lg" id="tutorial-button" style="float:right">Submit</button>
		</div>
		</div>		
		<br/>

		<div class="alert alert-success tutorial-success">You successfully completed the tutorial!</div>
		<div id="interviewee-prior-header" class="row">
			<h3 class="instruction">1. Before we start...</h3>
		</div>
		<div  id="interviewee-prior" class="row">
			<div class="col-sm-3 col-md-3 col-lg-3">
				<div class="interviewee-photo"></div>
				<div class="interviewee-name"></div>
				<div class="interviewee-title"></div>
			</div>
			<div class="col-sm-9 col-md-9 col-lg-9">
				<div class="q-doyouknow">Do you know this person?</div>
				<div class="a-doyouknow">
					<label><input type="radio" name="doyouknow" value="yes"/> Yes</label>
					<label><input type="radio" name="doyouknow" value="no"/> No</label>
				</div>
				<br/>
				<div class="q-confidence">
					How confident are you that you could describe the personality of this person?
				</div>
				<div class="a-confidence">
					<span class="left-label col-sm-3 col-md-3 col-lg-3">not very <br/>confident</span>
					<span class="col-sm-6 col-md-6 col-lg-6">
						<label><input type="radio" name="confidence" value="1"/> 1</label>
						<label><input type="radio" name="confidence" value="2"/> 2</label>
						<label><input type="radio" name="confidence" value="3"/> 3</label>
						<label><input type="radio" name="confidence" value="4"/> 4</label>
						<label><input type="radio" name="confidence" value="5"/> 5</label>
					</span>
					<span class="right-label col-sm-3 col-md-3 col-lg-3">strongly <br/>confident</span>
				</div>
			</div>
		</div>
		<br/>

		<div id="inst" class="row">
			<h3 class="instruction">2. Read the instruction.</h3>
			<ul>
			<li>
			<strong>Think ahead.</strong> We will ask you 10 questions (below) about the person in the video. <br/>
Read them <i>now</i>, and think ahead how you would answer each question <i>as you watch the video</i>.
			</li>
			<br/>
			<li>
			<strong>Avoid preconception.</strong> Please answer each question <i>based just on what you see in the video.</i> <br/>
In other words, avoid answering them based on your prior knowledge of the person.
			</li>
			<br/>
<!-- 			<li>
			<strong>Concentrate.</strong> We will pause the video for several times. <i>Unpause it as quickly as you can.</i> <br/>
The total duration of pauses will be a part of your feedback.
			</li>
			<br/> -->
			</ul>
			<div>
				<strong>The 10 questions:</strong><br/>
				How well do the following statements describe the person in the video? <br/>
				(Adjust your browser so that these questions are visible while watching the video.) <br/><br/>
				<div class="col-sm-6 col-md-6 col-lg-6">
				    1. The person is reserved<br/>
				    2. The person is generally trusting<br/>
				    3. The person tends to be lazy<br/>
				    4. The person is relaxed, handles stress well<br/>
				    5. The person has few artistic interests<br/>					
				</div>
				<div class="col-sm-6 col-md-6 col-lg-6">
				    6. The person is outgoing, sociable<br/>
				    7. The person tends to find fault with others<br/>
				    8. The person does a thorough job<br/>
				    9. The person gets nervous easily<br/>
				    10. The person has an active imagination<br/>					
				</div>				
			</div>			
		</div>
		<br/>

		<div id="video-player" class="row">
			<h3 class="instruction">3. Now, watch the video.</h3>
			<div id="ytplayer">You need Flash player 8+ and JavaScript enabled to view this video.</div>
<!-- 			<iframe id="ytplayer" type="text/html" width="640" height="390"
				src="http://www.youtube.com/embed/q7IEvfd1oXo?version=3&autoplay=0&controls=0&rel=0&showinfo=0&origin=http://juhokim.com" frameborder="0"></iframe>
 -->
		    <div id="errorMsg" class="alert alert-danger"></div>
		</div>
		<br/>

		<div id="questions" class="row">
			<h3 class="instruction">4. Answer the 10 questions.</h3>
			<div class="instruction col-sm-12 col-md-12 col-lg-12">
				How well do the following statements describe the person in the video?
			</div>
			<br/>
<?php

	    echo "<div class='col-sm-12 col-md-12 col-lg-12'>";
	    echo "<div class='question-header'><div><span class='interviewee-photo'></span> <br/><span class='desc interviewee-name'></span></div></div>";
	    for($i=0; $i<10; $i++){
	    	echo "<div class='question'><div class='question-text'>" . $questionArray[$i] . "</div>";
	    	echo "<label><input type='radio' name='question-interviewee-" . ($i+1) . "' value='1'/> Disagree strongly</label><br/>";
	    	echo "<label><input type='radio' name='question-interviewee-" . ($i+1) . "' value='2'/> Disagree a little</label><br/>";
	    	echo "<label><input type='radio' name='question-interviewee-" . ($i+1) . "' value='3'/> Neither agree nor disagree</label><br/>";
	    	echo "<label><input type='radio' name='question-interviewee-" . ($i+1) . "' value='4'/> Agree a little</label><br/>";
	    	echo "<label><input type='radio' name='question-interviewee-" . ($i+1) . "' value='5'/> Agree strongly</label><br/>";
	    	echo "</div>";
	    }	    
	    echo "</div>";    	
		// echo "<div class='col-sm-6 col-md-6 col-lg-6'>";
		// echo "<div class='question-header'><div><span class='interviewer-photo'></span> <br/><span class='desc'>Interviewer (reporter)</span></div></div>";
	 //    for($i=0; $i<10; $i++){
	 //    	echo "<div class='question'><div class='question-text'>" . $questionArray[$i] . "</div>";
	 //    	echo "<label><input type='radio' name='question-interviewer-" . ($i+1) . "' value='1'/> Disagree strongly</label><br/>";
	 //    	echo "<label><input type='radio' name='question-interviewer-" . ($i+1) . "' value='2'/> Disagree a little</label><br/>";
	 //    	echo "<label><input type='radio' name='question-interviewer-" . ($i+1) . "' value='3'/> Neither agree nor disagree</label><br/>";
	 //    	echo "<label><input type='radio' name='question-interviewer-" . ($i+1) . "' value='4'/> Agree a little</label><br/>";
	 //    	echo "<label><input type='radio' name='question-interviewer-" . ($i+1) . "' value='5'/> Agree strongly</label><br/>";
	 //    	echo "</div>";
	 //    }
	 //    echo "</div>";
?>
		</div>
		<br/>
		<div id="demographics" class="row">
			<h3>5. You're almost done!</h3>
			<div>
				These questions are optional, but we’d appreciate it if you’ve answered them. Please describe yourself by selecting one answer for each question.
			</div>
			<div><span>Gender:</span>
				<label><input type="radio" name="gender" value="male"/> Male</label>
				<label><input type="radio" name="gender" value="female"/> Female</label>
			</div>
			<div><span>Ethnicity:</span>
				<label><input type="radio" name="ethnicity" value="caucasian"/> Caucasian</label>
				<label><input type="radio" name="ethnicity" value="african-american"/> African American</label>
				<label><input type="radio" name="ethnicity" value="asian-pacific-islander"/> Asian/Pacific Islander</label><br/>
				<span class="padding">&nbsp;</span>
				<label><input type="radio" name="ethnicity" value="indian-alaskan-native"/> Indian/Alaskan native</label>
				<label><input type="radio" name="ethnicity" value="hispanic"/> Hispanic</label>
				<label><input type="radio" name="ethnicity" value="other"/> Other</label>
			</div>
			<div><span>Age:</span>
				<label><input type="radio" name="age" value="less-than-12"/> Younger than 12</label>
				<label><input type="radio" name="age" value="12-17"/> 12-17</label>
				<label><input type="radio" name="age" value="18-24"/> 18-24</label>
				<label><input type="radio" name="age" value="25-34"/> 25-34</label>
				<label><input type="radio" name="age" value="35-50"/> 35-50</label>
				<label><input type="radio" name="age" value="older-than-50"/> Older than 50</label>
			</div>
			<div>
				<div>Please leave any comments about the task:</div>
				<textarea id="comment" name="comment" rows="3" cols="50"></textarea>
			</div>
		</div>
		<br/>
		<div id="final" class="row">
			<h3>6. You are done. Thank you!</h3>
			<!-- <form action="http://www.mturk.com/mturk/externalSubmit"> -->
	        <input type="hidden" name="assignmentId" id="assignmentId" value="">
			<input type="hidden" name="pausedTimes" id="pausedTimes" value="">
			<input type="hidden" name="pausedTimestamps" id="pausedTimestamps" value="">
			<input type="hidden" name="resumedTimestamps" id="resumedTimestamps" value="">
			<div class="disclaimer">
				The goal of this study is to understand how people perceive the personality of the others. <br/>
				This study is a part of research conducted at the Massachusetts Institute of Technology. <br/>
				Your participation is voluntary and you have the right to stop at any time. <br/>
			</div>

			<button id='taskSub' type="submit" class="btn btn-lg btn-primary disabled" style="float:right" disabled="disabled">Submit</button>
			<hr>
			<div>Question? Send an email to yalesong at mit dot edu.</div>
		</div>
	</form>
	</div> 

	<!-- // <script type="text/javascript" src="js/libs/jquery-ui-1.8.22.custom.min.js"></script>  -->
	<!-- // <script type="text/javascript" src="js/libs/jwplayer/jwplayer.js"></script> -->
	<!-- <link rel="stylesheet" type="text/css" href="js/jquery.qtip.min.css" /> -->
	<!-- // <script type="text/javascript" src="js/jquery.qtip.min.js"></script> -->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <!-- <script>window.jQuery || document.write('<script src="js/jquery-1.10.2.min.js"><\/script>')</script> -->
    <script src="js/bootstrap.min.js"></script>
    <!-- // <script src="js/main.js"></script> -->
	<script type="text/javascript">
		// var videoPlayed = false;

		function isInt(n) {
		   return typeof n === 'number' && n % 1 == 0;
		}

		function formatTime(stringTime){
			var data = stringTime.split(":");
			var sec = parseInt(data[0])*60 + parseInt(data[1]);
			return sec;
		}

		// Get Parameters
		var prmstr = window.location.search.substr(1);
		var prmarr = prmstr.split ("&");
		var params = {};
		for ( var i = 0; i < prmarr.length; i++) {
			var tmparr = prmarr[i].split("=");
			params[tmparr[0]] = tmparr[1];
		}
		
		var id = params["id"];
		var debug = false;
		if (typeof params["debug"] !== "undefined" && params["debug"] == 1)
			debug = true;
		var num_pauses = 5;
		if (typeof params["pauses"] !== "undefined" && isInt(parseInt(params["pauses"])))
			num_pauses = parseInt(params["pauses"]);

		var video = <?php echo json_encode($video); ?>;
		var pic_url = "http://groups.csail.mit.edu/mug/time10q/mturk/pics/";

		var isPreview = false;
		if (typeof params["assignmentId"] === "undefined" || params["assignmentId"] == "ASSIGNMENT_ID_NOT_AVAILABLE")
			isPreview = true;
		if (typeof params["preview"] !== "undefined" && params["preview"] == 0)
			isPreview = false;

		var workerId = "";
		if (typeof params["workerId"] !== "undefined")
			workerId = params["workerId"];

<?php
		if ($didTutorial)
			echo "var isTutorialCompleted = true;";
		else
			echo "var isTutorialCompleted = false;";
?>
	    var player;
		var vidParams = { allowScriptAccess: "always", allowFullScreen: "false" };
		var atts = { id: "ytplayer" };
		if (debug)
			swfobject.embedSWF("http://www.youtube.com/v/" + video["video_id"] + "?enablejsapi=1&start=" + formatTime(video["start"]) + "&end=" + formatTime(video["end"]) + "&playerapiid=ytplayer&version=3&controls=1&rel=0&origin=http://juhokim.com",
                   "ytplayer", "640", "360", "8", null, null, vidParams, atts);
		else
			swfobject.embedSWF("http://www.youtube.com/v/" + video["video_id"] + "?enablejsapi=1&start=" + formatTime(video["start"]) + "&end=" + formatTime(video["end"]) + "&playerapiid=ytplayer&version=3&controls=0&rel=0&origin=http://juhokim.com",
                   "ytplayer", "640", "360", "8", null, null, vidParams, atts);


		function onYouTubePlayerReady(playerId) {
		      player = document.getElementById("ytplayer");
		      console.log("onPlayerReady");
		      player.addEventListener("onStateChange", "onPlayerStateChange");
		      player.cueVideoById({'videoId': video["video_id"], 'startSeconds': formatTime(video["start"]), 'endSeconds': formatTime(video["end"])});
		      // player.loadVideoById({'videoId': video["video_id"], 'start': formatTime(video["start"]), 'end': formatTime(video["end"])});
		      setInterval(updateytplayerInfo, 600);
		      updateytplayerInfo();
		}		      

		var duration = 0;
		var position = 0;
		var paused_times = [];
		var paused_timestamps = [];
		var resumed_timestamps = [];
		function updateytplayerInfo(){
			/*
			if (player) {
				if (duration == 0)
					duration = player.getDuration();
			    position = parseInt(player.getCurrentTime());
			    console.log(position, parseInt(duration/num_pauses));
			    if (duration != 0 && position != 0 && (position % parseInt(duration/num_pauses) == 0)){
			    	// if the player has been paused already in this position, do not pause again.
			    	if (duration - position < 10) // ignore the last one.
			    		return;
			    	if ($.inArray(position, paused_times) == -1){
			    		player.pauseVideo();
			    		paused_times.push(position);
			    		paused_timestamps.push(new Date().getTime());
			    		console.log("paused at", paused_times);
			    		$("#pausedTimes").val(paused_times);
			    		$("#pausedTimestamps").val(paused_timestamps);
			    		$("#errorMsg").show().html("Video is paused. Please click on the player to resume.");
			    	}
			    }    
			}
			*/							
		}

	    // 5. The API calls this function when the player's state changes.
	    //    The function indicates that when playing a video (state=1),
	    //    the player should play for six seconds and then stop.
	    function onPlayerStateChange(state) {
	      	console.log("CHANGE", state);
	      	if (state == -1){
	      		/*
			    setTimeout( function() { 
			  		if (player.getPlayerState() == -1){
						$("#errorMsg").show()
							.html("Cannot see the video? Please open <a target='_blank' href='<?php echo urldecode(stripslashes($video['url'])); ?>&t=" +
								parseInt(start) + "s'>this link</a>, watch the video for 20 seconds, and answer the question below.");
						setTimeout( function() { 
							 videoPlayed = true;
							 if ($("#instruction").val() != "") {
							 	$("#taskSub").removeClass('disabled').removeAttr('disabled');
							 }
						}, 20000);
					}
				}, 5000);		      		
				*/
	      	} else if (state == 1) {
	      		$("#errorMsg").hide();
	      		console.log("playing", player.getCurrentTime());
				resumed_timestamps.push(new Date().getTime());
			    $("#resumedTimestamps").val(resumed_timestamps);

			 //    setTimeout( function() { 
				// 	 videoPlayed = true;
				// 	 if ($("#instruction").val() != "") {
				// 	 	$("#taskSub").removeClass('disabled').removeAttr('disabled');
				// 	 }
				// }, 20000);
	   //          done = true;
			} else if (state == 2) {
				console.log("paused", player.getCurrentTime());
	        } else if (state == 0){
	        	// ended
	        	$("#errorMsg").show().removeClass("alert-danger").addClass("alert-info").html("Video finished playing. Please answer the questions below.");
	        	// console.log("video ended, show the questionnaire now.");
	        	$("#questions").show();
	        	// $("#demographics").show();
	        	// $("#final").show();
	        }
	    }

	    function stopVideo() {
	        player.stopVideo();
	    }   

	    function addPriorInfo(){
	    	$(".interviewee-photo").html("<img src='" + pic_url + video["video_id"] + ".jpg'/>");
	    	$(".interviewee-name").text(video["name"]);
	    	// $(".interviewee-title").text(video["title"]);
	    	$(".interviewer-photo").html("<img src='img/time_logo2.jpg'/>");
	    }


	    $(document).ready(function(){
	    	console.log("[debug]", debug);
	    	console.log("[num pauses]", num_pauses);
	    	console.log("[is preview]", isPreview);
	    	console.log("[is tutorial completed]", isTutorialCompleted);

	    	addPriorInfo();

	    	$("input[name='doyouknow']").change(function(){
	    		var selected = $("input[name='doyouknow']:checked").val();
	    		if (selected == "yes"){
	    			$(".q-confidence").show();
	    			$(".a-confidence").show();
	    		} else if (selected == "no"){
	    			$("#inst").show();
	    			$("#video-player").show();
	    		}
	    	});
	    	$("input[name='confidence']").change(function(){
	    		$("#inst").show();
	    		$("#video-player").show();
	    	});

	    	$("#questions :radio").change(function() {
			    var names = {};
			    $('#questions :radio').each(function() {
			        names[$(this).attr('name')] = true;
			    });
			    var count = 0;
			    $.each(names, function() { 
			        count++;
			    });
			    if ($('#questions :radio:checked').length !== count) {
			        console.log($('#questions :radio:checked').length, "out of", count, "answered");
			    } else {
			    	$("#taskSub").removeClass("disabled").removeAttr("disabled");
			    	$("#demographics").show();
	        		$("#final").show();
			    }
			});

	    	$("#tutorial-button").click(function(){
		    	// $("#tutorial :radio").change(function() {
				    var names = {};
				    $('#tutorial :radio').each(function() {
				        names[$(this).attr('name')] = true;
				    });
				    var count = 0;
				    $.each(names, function() { 
				        count++;
				    });
				    if ($('#tutorial :radio:checked').length !== count) {
				        console.log($('#tutorial :radio:checked').length, "out of", count, "answered");
				        alert("Please answer all the questions!");
				    } else {
			    		if (workerId != ""){
				    		$.ajax({
							  	url: "update-tutorial-info.php?workerid=" + workerId,
							}).done(function(data) {
							  	console.log("update-tutorial-info returned", data);
							});	    			
			    		}
						$("#tutorial").hide();
						$(".tutorial-success").show();
			    		$("#interviewee-prior").show();
			    		$("#interviewee-prior-header").show();
				    }
				// });


	    		// prevent submitting the mturk form
	    		return false;
	    	});

	    	if (isPreview){
	    		$("#preview").show();
	    	} else {
		    	if (isTutorialCompleted){
		    		$("#interviewee-prior").show();
		    		$("#interviewee-prior-header").show();
		    	} else {
		    		$("#tutorial").show();
		    	}

				if (debug){
					$("#tutorial").show();
		    		$("#interviewee-prior").show();
		    		$("#interviewee-prior-header").show();				
					$("#questions").show();
					$("#inst").show();
					$("#video-player").show(); 
					$("#questions").show(); 
					$("#demographics").show();
					$("#final").show();
				}	    		
	    	}

	    });
	</script>        
</body>

</html>
