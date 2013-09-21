
	
		$(document).ready(function() {

			// var tasks = [];

		    var addToolTick = function() {
	    		var id = "toolTick";          
	    		var duration = 100;
	   			var offset = 49.2;
	    		var html = "<span class='marker btn-inverse toolTick' id='" + id + "' style='left:" + offset + "%;'></span>";  
	    		var i = $(html);

	    		// On hover add "Click to remove"
	    		i.qtip({
					content: {
						text: 'Instruction Here<br/>(10 sec mark)'
					},
					position: {
						my: 'top center', // Use the corner...
						at: 'bottom center' // ...and opposite corner
					},
					show: {
						event: false, // Don't specify a show event...
						ready: true // ... but show the tooltip when ready
					},
					hide: false, // Don't specify a hide event either!
					style: {
						classes: 'qtip-shadow qtip-' + 'dark'
					}
				});
	    		$("#timelineHolder").append(i);      
	    	};

      		var makeTask = function(video, labels, genre) {
      			var infoDes;
      			var ht = '<div class="section task">' +
      					// '<h2> Video </h2>' +
						// '<div class="video">' +
						// 	'<div id="mediaplayer" width="100%" height="400">JW Player goes here</div>' + 
						// '</div>' +
						'<div id="errorMsg"></div>' +						
						'<br/><div id="timerDisplay">0:00</div></span><div id="timelineHolder" style="position:relative">' +
						'<div id="timeline"></div>' + 
						'</div>' + 
						'<div class="info"><div>' +
							'<h3> Which best describes the instruction around the 10 second time mark? </h3>' +
							'<div id="tipLabel"><strong>Tip: Pick the most concrete and actionable instruction.</strong></div>' +
							// '<div>Select multiple ONLY if there are more than one instructions in this video.</div>' +
							// '<div><strong>GOOD: concrete and actionable.</strong> <span class="good-examples"></span></div>' +
							// '<div><strong>BAD: too generic and not actionable.</strong> <span class="bad-examples"></span></div>' +
							'<div id="labelSelection"></div>' +
							'<input type="radio" name="labelRadios" value="@">' + '<i>I have a better description.</i><br/>' +

							'<div id="otherLabel" style="display:none"><h4>Please write an alternative label: </h4>' +
								'<input type="text" id="otherLabelText"></div>' +
							'<input type="radio" name="labelRadios" value="noop">' + '<i>There is no instruction around the 10 second mark.</i>' +
						'</div>' +
					'</div>';

				$("#tasks").append(ht);
				switch(genre) {
					case 'c':
						$(".good-examples").html("(e.g., add olive oil, put dough in flour)");
						$(".bad-examples").html("(e.g., make a pizza, it is important not to mix)");
						break;
					case 'm':
						$(".good-examples").html("(e.g., apply eye shadow, use damp brush)");
						$(".bad-examples").html("(e.g., make pretty, lips)");
						break;
					case 'p':
						$(".good-examples").html("(e.g., select Gaussian Blur, duplicate a layer)");
						$(".bad-examples").html("(e.g., make bright, finish up)");

					// case 'c':
					// 	$("#selection-good-examples").html("<strong>GOOD</strong>: cut tomatoes in slices AND add basil (different instructions, pick both)");
					// 	$("#selection-bad-examples").html("<strong>BAD</strong> : thinly slice tomatoes AND cut tomatoes in thin slices (too similar, pick only one)");
					// 	break;
					// case 'm':
					// 	$("#selection-good-examples").html("<strong>GOOD</strong>: apply concealer AND blend with fingertips (different instrutions, pick both)");
					// 	$("#selection-bad-examples").html("<strong>BAD</strong> : apply concealer AND use concealer (too similar, pick only one)");
					// 	break;
					// case 'p':
					// 	$("#selection-good-examples").html("<strong>GOOD</strong>: add new layer AND click color dodge (different instrutions, pick both)");
					// 	$("#selection-bad-examples").html("<strong>BAD</strong> : add new layer AND insert new layer (too similar, pick only one)");
					// 	break;
					default:
						console.log('ERROR: Genre type not found.')
				}

				// randomize array
				var labelIndex, key;
				var labels_obj = {};
				var keys = [];
				// turn into an object to store the randomize order,
				// which helps track which item was selected by the user.
				for (labelIndex in labels){
					labels_obj[labelIndex] = labels[labelIndex];
				}
			    for(key in labels_obj){
			        if(labels_obj.hasOwnProperty(key)){
			            keys.push(key);
			        }
			    }			
				keys.sort(function () { if (Math.random()<.5) return -1; else return 1; });
				// console.log("after:", keys);
				// labels.sort(function () { if (Math.random()<.5) return -1; else return 1; });
				$("#order").val(keys);
				for (labelIndex in keys) {
					var key = keys[labelIndex];
					var label = labels_obj[key];
					var inputString = '<input type="radio" name="labelRadios" value="' + key + '">&quot;' + label.toLowerCase() + '&quot;<br>';
					$("#labelSelection").append(inputString);					
				}
				// for (labelIndex in labels) {
				// 	var label = labels[labelIndex];
				// 	var inputString = '<input type="radio" name="labelRadios" value="' + label.toLowerCase() + '">&quot;' + label.toLowerCase() + '&quot;<br>';
				// 	$("#labelSelection").append(inputString);					
				// }

				$("input[type=radio][name=labelRadios]").change(function() {
					var labelVal = $(this).val();
					if (labelVal == '@') {
						$('#otherLabel').show();
					} else {
						$('#otherLabel').hide();
						$('#instruction').val(labelVal);
						if (videoPlayed) {
							$("#taskSub").removeClass('disabled').removeAttr('disabled');
						}
					}
				});

				// $("input[type=checkbox][name=labelRadios]").change(function() {
				// 	var labelVal = $(this).val();
				// 	if (labelVal == '@') {
				// 		if ($("input[type=checkbox][value='@']").is(':checked')){
				// 			$("input[type=checkbox][value!='@']").each(function(){
				// 				if ($(this).is(':checked')){
				// 					$(this).trigger('click');
				// 				}	
				// 			});						
				// 		}
				// 		$('#otherLabel').toggle();
				// 	} else {
				// 		if ($(this).is(':checked')){
				// 			if ($("input[type=checkbox][value='@']").is(':checked')){
				// 				$("input[type=checkbox][value='@']").trigger('click');
				// 			}							
				// 		}
						
				// 		$('#instruction').val(labelVal);
				// 		if (videoPlayed) {
				// 			$("#taskSub").removeClass('disabled').removeAttr('disabled');
				// 		}
				// 	}
				// });

				$('#otherLabelText').keyup(function() {
					$("#instruction").val($(this).val());
					if ($(this).val() != "" && videoPlayed) {
						$("#taskSub").removeClass('disabled').removeAttr('disabled');
					} else {
						$("#taskSub").addClass('disabled').attr('disabled', 'disabled');
					}
				})

				// jwplayer("mediaplayer").setup({
				// 	flashplayer: "js/libs/jwplayer/player.swf",
				// 	controlbar: "bottom",
				// 	file: video,
				// 	// file: "http://www.youtube.com/watch?v=iTXnpGe7a1A",
				// 	start: start,					
				// 	events: {
				// 		onReady: function(event) {
				// 			jwplayer().seek(start);
				// 		},
				// 		onTime: function(event) {
				// 			// console.log(event.position);
				// 			var offset = parseInt(event.position - start);
				// 			if (offset < 10)
				// 				$("#timerDisplay").text("0:0" + offset);
				// 			else
				// 				$("#timerDisplay").text("0:" + offset);
				// 			// if (event.position > 120)
				// 			// 	jwplayer().pause();
				// 			//$("#timeline").slider( "option", "max", jwplayer().getDuration());
				// 			//$("#timeline").slider('value', event.position);
				// 			$("#timeline").slider( "option", "max", 20);
	   //          			$("#timeline").slider('value', event.position - start);
				// 		}, 
				// 		onPlay: function(event) {
				// 			setTimeout( function() { 
				// 				videoPlayed = true;
				// 				if ($("#instruction").val() != "") {
				// 				 	$("#taskSub").removeClass('disabled').removeAttr('disabled');
				// 				}
				// 			}, 20000);
    //       				}
				// 	}
				// });


				$( "#timeline").slider({
					range: "min",
					min: 0,
					max: 279,
					step: 0.1,
					animate: true,
					slide: function(event, ui){
						// jwplayer().seek(ui.value + start);
						if (player)
							player.seekTo(ui.value + start);
					}
	      		});
      		};

			if (params['assignmentId'])
				$("#assignmentId").val(params['assignmentId']);
			if (params['id'])
				$("#video").val(params['id']);

			var vid = <?php echo json_encode($video_id); ?>,
				allLabels = <?php echo json_encode($all_labels); ?>,
				genre = vid.split('_')[1][0],	// c = Cooking, p = Photoshop, m = Makeup
				video = null;
				video = "<?php echo urldecode(stripslashes($video['url'])); ?>";

			//allLabels = allLabels.replace(/\"/g, "").split(',');
			console.log(allLabels);
			switch(genre) {
				case 'c':
					$(".genreText").each(function() { $(this).text("cooking") });
					$(".good-examples").text("(e.g., add olive oil, put dough in flour)");
					$(".bad-examples").text("(e.g., make a pizza, it is important not to mix)");
					break;
				case 'm':
					$(".genreText").each(function() { $(this).text("makeup") });
					$(".good-examples").text("(e.g., apply eye shadow, use damp brush)");
					$(".bad-examples").text("(e.g., make pretty, lips)");
					break;
				case 'p':
					$(".genreText").each(function() { $(this).text("Photoshop") });
					$(".good-examples").text("(e.g., select Gaussian Blur, duplicate a layer)");
					$(".bad-examples").text("(e.g., make bright, finish up)");
					break;
				default:
					console.log('ERROR: Genre type not found.')
			}
			console.log(genre, video);
      		makeTask(video, allLabels, genre);


      		$('#readBtn').click(function() {
      				$('#task').show();
      				addToolTick();
      		});

      		var enableRead = function() {
				$('#readBtn').removeClass('disabled').removeAttr('disabled');
      		}
      		setTimeout(enableRead, 5000);
   		
		});
