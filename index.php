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
$video = array();
$result = $mysqli->query("SELECT * FROM videos WHERE id = '$id'");
while($entry = $result->fetch_assoc()){
	$video = $entry;
}

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

        #interviewee-prior{
        	border: 1px gray solid;
        	padding: 10px;
        	margin: 20px;
        }

        .interviewee-photo img{
        	max-width: 100%;
        	max-height: 150px;
        }
		.a-doyouknow input[type=radio]{
			margin-left: 30px;
		}
		.a-confidence input[type=radio]{
			margin-left: 20px;
		}

		.q-confidence, .a-confidence{
			display: none;
		}

		.questions {
			/*margin: 10px;*/
		}

        .question-interviewer, .question-interviewee{
        	margin: 20px;
        }
        .question-header{
        	height: 150px;
        	position: relative;
        }
        .question-header div{
        	position:absolute; 
        	/*top:50%; */
        	bottom: 0;
        }
        .question-header div img{
        	top:-100px;
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
    </style>
	<!-- <link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.8.22.custom.css" type="text/css" /> -->
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="css/style.css" type="text/css" />
    <!-- // <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script> -->
</head>

<body>
	<div class="container">
	<form id="submitForm" action="https://www.mturk.com/mturk/externalSubmit" method="POST">

		<div id="title" class="row">
			<h2 class="col-sm-12 col-md-12 col-lg-12">
				How do you perceive the personality of the others?
			</h2>
		</div>
		<h3>1. Before we start...</h3>
		<div id="interviewee-prior" class="row">
			<div class="col-sm-3 col-md-3 col-lg-3">
				<div class="interviewee-photo"></div>
				<div class="interviewee-name"></div>
				<div class="interviewee-title"></div>
			</div>
			<div class="col-sm-9 col-md-9 col-lg-9">
				<div class="q-doyouknow">Do you know this person?</div>
				<div class="a-doyouknow">
					<input type="radio" name="doyouknow" value="yes"/> Yes
					<input type="radio" name="doyouknow" value="no"/> No
				</div>
				<br/>
				<div class="q-confidence">
					How confident are you that you could describe the personality of this person?
				</div>
				<div class="a-confidence">
					not very confident
					<input type="radio" name="confidence" value="1"/> 1
					<input type="radio" name="confidence" value="2"/> 2
					<input type="radio" name="confidence" value="3"/> 3
					<input type="radio" name="confidence" value="4"/> 4
					<input type="radio" name="confidence" value="5"/> 5
					&nbsp;&nbsp;&nbsp;strongly confident
				</div>
			</div>
		</div>
		<br/>
		<div id="video-player" class="row">
			<h3 class="instruction" class="col-sm-12 col-md-12 col-lg-12">
				2. Now, watch this video clip carefully; we will ask you about 
				how you’ve perceived the personality of the people appearing in the video.
			</h3>
			<br/>
			<div id="ytplayer">You need Flash player 8+ and JavaScript enabled to view this video.</div>
			<!-- <iframe id="ytplayer" type="text/html" width="640" height="390"
		  src="https://www.youtube.com/v/<?php echo $video['slug']; ?>?enablejsapi=1&version=3"
		  frameborder="0"></iframe> -->
		</div>
		<br/>
		<div id="questions" class="row">
			<h3>3. How do you think?</h3>
			<div class="instruction col-sm-12 col-md-12 col-lg-12">
				How well do the following statements describe the interviewer and the interviewee in the video? 
			</div>
<?php
    	$questionArray = array(
    		"1.	... is reserved",
			"2.	... is generally trusting", 
			"3.	... tends to be lazy", 
			"4.	... is relaxed, handles stress well", 
			"5.	... has few artistic interests", 
			"6.	... is outgoing, sociable", 
			"7.	... tends to find fault with others", 
			"8.	... does a thorough job", 
			"9.	... gets nervous easily", 
			"10.	... has an active imagination"
    	);
		echo "<div class='col-sm-6 col-md-6 col-lg-6'>";
		echo "<div class='question-header'><div>Interviewer (reporter)</div></div>";
	    for($i=0; $i<10; $i++){
	    	echo "<div class='question-interviewee'>" . $questionArray[$i] . "<br/>";
	    	echo "<input type='radio' name='question-interviewee-" . ($i+1) . "' value='$j'/> Disagree strongly<br/>";
	    	echo "<input type='radio' name='question-interviewee-" . ($i+1) . "' value='$j'/> Disagree a little<br/>";
	    	echo "<input type='radio' name='question-interviewee-" . ($i+1) . "' value='$j'/> Neither agree nor disagree<br/>";
	    	echo "<input type='radio' name='question-interviewee-" . ($i+1) . "' value='$j'/> Agree a little<br/>";
	    	echo "<input type='radio' name='question-interviewee-" . ($i+1) . "' value='$j'/> Agree strongly<br/>";
	    	echo "</div>";
	    }
	    echo "</div>";
	    echo "<div class='col-sm-6 col-md-6 col-lg-6'>";
	    echo "<div class='question-header'><div>Interviewee <span class='interviewee-photo'></span></div></div>";
	    for($i=0; $i<10; $i++){
	    	echo "<div class='question-interviewer'>" . $questionArray[$i] . "<br/>";
	    	echo "<input type='radio' name='question-interviewer-" . ($i+1) . "' value='$j'/> Disagree strongly<br/>";
	    	echo "<input type='radio' name='question-interviewer-" . ($i+1) . "' value='$j'/> Disagree a little<br/>";
	    	echo "<input type='radio' name='question-interviewer-" . ($i+1) . "' value='$j'/> Neither agree nor disagree<br/>";
	    	echo "<input type='radio' name='question-interviewer-" . ($i+1) . "' value='$j'/> Agree a little<br/>";
	    	echo "<input type='radio' name='question-interviewer-" . ($i+1) . "' value='$j'/> Agree strongly<br/>";
	    	echo "</div>";
	    }	    
	    echo "</div>";
?>
		</div>

		<div id="demographics" class="row">
			<h3>4. You're almost done!</h3>
			<div>
				These questions are optional, but we’d appreciate it if you’ve answered them. Please describe yourself:
			</div>
			<div><span>Gender:</span>
				<input type="radio" name="gender" value="male"/> Male
				<input type="radio" name="gender" value="female"/> Female
			</div>
			<div><span>Ethnicity:</span>
				<input type="radio" name="ethnicity" value="caucasian"/> Caucasian
				<input type="radio" name="ethnicity" value="african-american"/> African American
				<input type="radio" name="ethnicity" value="asian-pacific-islander"/> Asian/Pacific Islander
				<input type="radio" name="ethnicity" value="indian-alaskan-native"/> Indian/Alaskan native
				<input type="radio" name="ethnicity" value="hispanic"/> Hispanic
				<input type="radio" name="ethnicity" value="other"/> Other
			</div>
			<div><span>Age:</span>
				<input type="radio" name="age" value="caucasian"/> Younger than 12
				<input type="radio" name="age" value="african-american"/> 12-17
				<input type="radio" name="age" value="asian-pacific-islander"/> 18-24
				<input type="radio" name="age" value="indian-alaskan-native"/> 25-34
				<input type="radio" name="age" value="hispanic"/> 35-50
				<input type="radio" name="age" value="other"/> Older than 50
			</div>
		</div>

		<div id="final" class="row">
			<h3>5. Now you are done. Thank you!</h3>
			<!-- <form action="http://www.mturk.com/mturk/externalSubmit"> -->
	        <input type="hidden" name="assignmentId" id="assignmentId" value="">
			<div class="disclaimer">
				The goal of this study is to understand how people perceive the personality of the others. <br/>
				This study is a part of research conducted at Massachusetts Institute of Technology. <br/>
				Your participation is voluntary and you have the right to stop at any time. 
			</div>

			<button id='taskSub' type="submit" class="btn btn-lg btn-primary disabled" style="float:right" disabled="disabled">Submit</button>
			<hr>
			<div>Question? Shoot an email to yalesong at mit dot edu.</div>
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

		// Get Parameters
		var prmstr = window.location.search.substr(1);
		var prmarr = prmstr.split ("&");
		var params = {};
		for ( var i = 0; i < prmarr.length; i++) {
			var tmparr = prmarr[i].split("=");
			params[tmparr[0]] = tmparr[1];
		}
		var id = params[id];
		var video = <?php echo json_encode($video); ?>;
		var pic_url = "http://groups.csail.mit.edu/mug/time10q/mturk/pics/";
		console.log(video);
		// 2. This code loads the IFrame Player API code asynchronously.
		// var tag = document.createElement('script');
		// tag.src = "https://www.youtube.com/iframe_api";
		// var firstScriptTag = document.getElementsByTagName('script')[0];
		// firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

		// 3. This function creates an <iframe> (and YouTube player)
		//    after the API code downloads.
		// var player;
		// function onYouTubeIframeAPIReady() {
		//   player = new YT.Player('ytplayer', {
		//     height: '390',
		//     width: '640',
		//     videoId: "<?php echo $video['slug']; ?>",
		//     events: {
		//       'onReady': onPlayerReady,
		//       'onStateChange': onPlayerStateChange
		//     }
		//   });
		// }
	    var player;
		var vidParams = { allowScriptAccess: "always" };
		var atts = { id: "ytplayer" };
		swfobject.embedSWF("http://www.youtube.com/v/" + video["video_id"] + "?enablejsapi=1&playerapiid=ytplayer&version=3&controls=0&rel=0",
                   "ytplayer", "640", "360", "8", null, null, vidParams, atts);

		function onYouTubePlayerReady(playerId) {
		      player = document.getElementById("ytplayer");
		      console.log("onPlayerReady", start, start+20);
		      player.addEventListener("onStateChange", "onPlayerStateChange");
		      player.loadVideoById({'videoId': video["video_id"]});
		      // player.loadVideoById({'videoId': video["video_id"], 'startSeconds': start, 'endSeconds': start+20});
		      setInterval(updateytplayerInfo, 600);
		      updateytplayerInfo();
		}		      

		function updateytplayerInfo(){
			// if (player) {
			//     var position = player.getCurrentTime();
			// 	var offset = parseInt(position - start);
			// 	if (offset < 10)
			// 		$("#timerDisplay").text("0:0" + offset);
			// 	else
			// 		$("#timerDisplay").text("0:" + offset);		
			// 	$("#timeline").slider( "option", "max", 20);
   //          	$("#timeline").slider('value', offset);		    
			// }							
		}
		/*
	      // 4. The API will call this function when the video player is ready.
	      function onPlayerReady(event) {
		    console.log("onPlayerReady", start, start+20);
		    //event.target.playVideo();
			player.cueVideoById({'videoId': '<?php echo $video['slug']; ?>', 'startSeconds': start, 'endSeconds': start+20});
			//player.cueVideoById({'videoId': 'bHQqvYy5KYo', 'startSeconds': 50, 'endSeconds': 60});

	      }
	    */
	    // 5. The API calls this function when the player's state changes.
	    //    The function indicates that when playing a video (state=1),
	    //    the player should play for six seconds and then stop.
	    var done = false;
	    function onPlayerStateChange(state) {
	      	console.log("CHANGE", state);
	      	if (state == -1){
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
	      	} else if (state == 1 && !done) {
			    setTimeout( function() { 
					 videoPlayed = true;
					 if ($("#instruction").val() != "") {
					 	$("#taskSub").removeClass('disabled').removeAttr('disabled');
					 }
				}, 20000);
	            done = true;
	        }
	    }

	    function stopVideo() {
	        player.stopVideo();
	    }   

	    function addPriorInfo(){
	    	$(".interviewee-photo").html("<img src='" + pic_url + video["video_id"] + ".jpg'/>");
	    	$(".interviewee-name").text(video["name"]);
	    	$(".interviewee-title").text(video["title"]);
	    }

	   //  function addQuestions(){
	   //  	var $question;
	   //  	var questionArray = [
	   //  		"1.	... is reserved",
				// "2.	... is generally trusting", 
				// "3.	... tends to be lazy", 
				// "4.	... is relaxed, handles stress well", 
				// "5.	... has few artistic interests", 
				// "6.	... is outgoing, sociable", 
				// "7.	... tends to find fault with others", 
				// "8.	... does a thorough job", 
				// "9.	... gets nervous easily", 
				// "10.	... has an active imagination"
	   //  	];
	   //  	var i, j;
	   //  	for (i=0; i<questionArray.length; i++){
	   //  		$question = $("<div/>").addClass("question").text(questionArray[i]);
	   //  		for (j=0; j<5; j++){
	   //  			$("<input type='radio'>").attr("name", "question-" + (i+1)).text("Disagree strongly").appendTo($question);
	   //  			$("<input type='radio'>").attr("name", "question-" + (i+1)).text("Disagree a little").appendTo($question);
	   //  			$("<input type='radio'>").attr("name", "question-" + (i+1)).text("Neither agree nor disagree").appendTo($question);
	   //  			$("<input type='radio'>").attr("name", "question-" + (i+1)).text("Agree a little").appendTo($question);
	   //  			$("<input type='radio'>").attr("name", "question-" + (i+1)).text("Agree strongly").appendTo($question);
	   //  		}
	   //  		$("#questions").append($question);	
	   //  	}
	    	
	   //  }

	    $(document).ready(function(){
	    	addPriorInfo();
	    	// addQuestions();

	    	$("input[name='doyouknow']").change(function(){
	    		var selected = $("input[name='doyouknow']:checked").val();
	    		if (selected == "yes"){
	    			$(".q-confidence").show();
	    			$(".a-confidence").show();
	    		}
	    	});
	    });
	</script>        
</body>

</html>
