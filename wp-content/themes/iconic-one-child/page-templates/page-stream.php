<link href="/live-assets/bootstrap-4.0.0/css/bootstrap.min.css" rel="stylesheet">
<?php
/**
* Template Name: Page Template for Streamer
*
* Description: Use this page template for right sidebar.
 *
 * @package WordPress - Themonic Framework
 * @subpackage Iconic_One
 * @since Iconic One 1.0
 */

get_header(); ?>
<?php $user = wp_get_current_user(); ?>
<style>
  /* Overwite bootstrap breaking the page */
  #page {
	box-sizing: content-box;
  }
  #masthead {
	box-sizing: content-box;
	  }
#primary {
  padding-top: 10px;
}
#live-nav {
  display: none;
}
#live-nav > .tab-content {
  margin-top: 1px;
}
.card-header > h5 > button {
		margin-bottom:0px;
		padding-top:0px;
		padding-bottom:0px;
}
@media (min-width: 576px) {
  .card-body {
	-webkit-column-count: 3; /* Chrome, Safari, Opera */
	-moz-column-count:    3; /* Firefox */
	column-count:         3;
  	-webkit-column-gap:   20px; /* Chrome, Safari, Opera */ 
	-moz-column-gap:      20px; /* Firefox */
	column-gap:           20px;
  }
}
@media (min-width: 992px) {
  .card-body {
	-webkit-column-count: 4; /* Chrome, Safari, Opera */
	-moz-column-count:    4; /* Firefox */
	column-count:         4;
  	-webkit-column-gap:   20px; /* Chrome, Safari, Opera */ 
	-moz-column-gap:      20px; /* Firefox */
	column-gap:           20px;
  }
}
		  .dvr-controls > .live-info {
				display: none;
			  }
#video-title {
  font-size: 1.5em;
  padding-bottom: 10px;
}
#video-player {
    margin-left: auto;
    margin-right: auto;
    position: relative;
	width: 100%;
}
#video-player:before {
  display: block;
  content: "";
  width: 100%;
  padding-top: 56.25%;
}
#video-player > div {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  height: 100% !important;
  width: 100% !important;
}
#video-stats {
    text-align: left;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
    color: #010101;
    font-family: "Open Sans", Arial, sans-serif;
    font-weight: bold;
    margin-bottom: 10px;
    padding-left: 3px;
    padding-right: 3px;
	padding-top: 3px;
}
#video-stats-viewers {
    float: right;
}
#video-bandwidth {
    margin-left: auto;
    margin-right: auto;
    text-align: center;
    width: 100%;
    color: #050505;
    font-size: 90%;
    font-family: "Open Sans", Arial, sans-serif;
    font-weight: bold;
  padding-bottom: 12px;
}
.loading {
    height: 14px;
    width: 14px;
  -webkit-animation:spin 2s linear infinite;
  -moz-animation:spin 2s linear infinite;
  animation:spin 2s linear infinite;
}
.fast-loading {
    height: 14px;
    width: 14px;
  -webkit-animation:spin 1s linear infinite;
  -moz-animation:spin 1s linear infinite;
  animation:spin 1s linear infinite;
}
@-moz-keyframes spin { 100% { 
  -moz-transform:rotate(360deg); 
  }
}
@-webkit-keyframes spin { 100% { 
  -webkit-transform:rotate(360deg); 
  }
}
@keyframes spin { 100% {
  -webkit-transform:rotate(360deg);
  transform:rotate(360deg);
  }
}
#player_column {
  text-align: center;
}
.col-centered {
  float: none;
  margin: 0 auto;
}
#chat {
  padding-top: 22px;
  height: 420px;
  margin-bottom: 20px;
}
#primary {
  padding-left: 10px;
  padding-right: 10px;
}
#chat-column {
  padding-left: 0px;
}
#video-description {
  line-height: 1.5em;
}
</style>
<script src="/live-assets/bootstrap-4.0.0/js/bootstrap.min.js"></script>
<script src="/live-assets/clappr-0.2.85/clappr.min.js"></script>
<script src="/live-assets/clappr-rtmp-0.0.20/rtmp.js"></script>
<script src="/live-assets/clappr-pip-2.0.1.min.js"></script>
<script src="/live-assets/batc-player.js"></script>
<script src="/live-assets/batc-chat.js"></script>
<script src="/live-assets/socket.io-2.1.0.min.js"></script>
<script>
user_chatnick = "<?php echo esc_attr( get_user_meta( $user->ID, 'chat_name', true ) ); ?>";
var stream_name = window.location.pathname.split( '/' ).slice(-1)[0];
function update_viewers(num) {
  $("#video-stats-viewers").text("Current Viewers: "+num);
}
jQuery(document).ready(function($)
{
  if(stream_name=='')
  {
	var max_title_length = 42;
    $.getJSON("/live-api/stream_list.php", function( json )
    {
      if(!json.error)
      {
        /* Event Streams */
        var active_events = 0;
        var inactive_events = 0;
        if(json.events.length != 0)
        {
          json.events.sort(sortStreamTitle);
          $.each(json.events, function(id, json_event)
          {
			if(json_event.stream_title && json_event.stream_title != ""
			  && json_event.stream_output_url && json_event.stream_output_url != "")
			{
			  if(json_event.active)
			  {
				$("#online-events-card > .card-body").append(
				  $("<li></li>").append(
					$("<a></a>").attr('href','/live/'+json_event.stream_output_url).text(json_event.stream_title.substring(0,max_title_length))
				  )
				);
				active_events++;
			  }
			  else
			  {
				$("#offline-events-card-collapse > .card-body").append(
				  $("<li></li>").append(
					$("<a></a>").attr('href','/live/'+json_event.stream_output_url).text(json_event.stream_title.substring(0,max_title_length))
				  )
				);
				inactive_events++;
			  }
			}
          });
        }
        if(active_events == 0)
        {
          $("#online-events-card > .card-body").append($("<b></b>").text("No Streams."));
        }
        if(inactive_events == 0)
        {
          $("#offline-events-card-collapse > .card-body").append($("<b></b>").text("No Streams."));
        }
        /* Members */
        var active_members = 0;
        var inactive_members = 0;
        if(json.members.length != 0)
        {
          json.members.sort(sortStreamTitle);
          $.each(json.members, function(id, json_member)
          {
			if(json_member.stream_title && json_member.stream_title != ""
			  && json_member.stream_output_url && json_member.stream_output_url != "")
			{
			  if(json_member.active)
			  {
				  $("#online-members-card > .card-body").append(
					$("<li></li>").append(
					  $("<a></a>").attr('href','/live/'+json_member.stream_output_url).text(json_member.stream_title.substring(0,max_title_length))
					)
				  );
				  active_members++;
			  }
			  else
			  {
				  $("#offline-members-card-collapse > .card-body").append(
					$("<li></li>").append(
					  $("<a></a>").attr('href','/live/'+json_member.stream_output_url).text(json_member.stream_title.substring(0,max_title_length))
					)
				  );
				  inactive_members++;
			  }
			}
          });
        }
        if(active_members == 0)
        {
          $("#online-members-card > .card-body").append($("<b></b>").text("No Streams."));
        }
        if(inactive_members == 0)
        {
          $("#offline-members-card-collapse > .card-body").append($("<b></b>").text("No Streams."));
        }
        /* Repeaters */
        var active_repeaters = 0;
        var inactive_repeaters = 0;
        if(json.repeaters.length != 0)
        {
          json.repeaters.sort(sortStreamTitle);
          $.each(json.repeaters, function(id, json_repeater)
          {
			if(json_repeater.stream_title && json_repeater.stream_title != ""
			  && json_repeater.stream_output_url && json_repeater.stream_output_url != "")
			{
			  if(json_repeater.active)
			  {
				  $("#online-repeaters-card > .card-body").append(
					$("<li></li>").append(
					  $("<a></a>").attr('href','/live/'+json_repeater.stream_output_url).text(json_repeater.stream_title.substring(0,max_title_length))
					)
				  );
				  active_repeaters++;
			  }
			  else
			  {
				  $("#offline-repeaters-card-collapse > .card-body").append(
					$("<li></li>").append(
					  $("<a></a>").attr('href','/live/'+json_repeater.stream_output_url).text(json_repeater.stream_title.substring(0,max_title_length))
					)
				  );
				  inactive_repeaters++;
			  }
			}
          });
        }
        if(active_repeaters == 0)
        {
          $("#online-repeaters-card > .card-body").append(
			$("<b></b>").text("No Streams.")
		  );
        }
        if(inactive_repeaters == 0)
        {
          $("#offline-repeaters-card-collapse > .card-body").append(
			$("<b></b>").text("No Streams.")
		  );
        }
      }
    });
    $("#live-nav").show();
  }
  else
  {
    $.getJSON("/live-api/stream.php", {'name': stream_name}, function( json )
	{
      if(!json.error)
      {
        document.title = json.stream_title + " - BATC Live Streaming";
        $("#video-title").text(json.stream_title);
        
        /* Build Video Player Here */
		batcPlayer_init('#video-player', json.stream_output_url, !!json.streaming_type_flash, false); // TODO: Add Flash/Transcoded switches
        
        /* Build Chat */
        if('chat_on' in json && json.chat_on=='1')
        {
            $("#chat_column").append($("<div></div>").attr('id','chat'));
            var chat = new BATC_Chat({
                element: $("#chat"),
                room: json.stream_output_url,
                nick: user_chatnick,
                viewers_cb: update_viewers,
                guests_allowed: (json.guest_chat_login=='1')
            });
        }
        else
        {
          $("#chat_column").remove();
          $("#player_column").removeClass("col-lg-8").addClass("col-lg-10").addClass("col-centered");
        }
        
        if("stream_description" in json && json.stream_description.length!=0)
        {
          $("#content").append($("<div></div>").addClass("card").addClass("card-body").addClass("bg-light").attr('id','video-description').append(json.stream_description));
        }
      } 
      else 
      {
        if(json.error=="Stream not found")
        {
          $("#video-title").text("Stream not found");
        }
        else
        {
          $("#video-title").text("An error has occured. Please contact the Administrator.");
          console.log(json);
        }
      }
    });

    var full_width = $("<div></div>").addClass("row").attr('id', 'full_width');
    var player_column = $("<div></div>").addClass("col-lg-8").attr('id', 'player_column');
      player_column.append($("<h4></h4>").attr('id','video-title').html("&nbsp;"));
      player_column.append($("<div></div>").attr('id','video-player'));
    
    var video_stats_status_obj = $("<span></span>").attr('id','video-stats-status').text('Loading..');
    var video_stats_viewers_obj = $("<span></span>").attr('id','video-stats-viewers');
    var video_stats_obj = $("<div></div>").attr('id','video-stats').append(video_stats_status_obj).append(video_stats_viewers_obj);
    
    player_column.append(video_stats_obj);
    full_width.append(player_column);

    var chat_column = $("<div></div>").addClass("col-lg-4").attr('id', 'chat_column');
    full_width.append(chat_column);

    $("#content").append(full_width);

  }
});
function sortStreamTitle(a,b) {
  if (a.stream_title < b.stream_title)
    return -1;
  if (a.stream_title > b.stream_title)
    return 1;
  return 0;
}
</script>
<div id="primary" class="site-content">
<div id="content" role="main">

<div id="live-nav">
  <center><h1>BATC Live Video Streamer</h1></center>
  <ul class="nav nav-tabs" role="tablist">
	<li class="nav-item">
	  <a class="nav-link active" data-toggle="tab" href="#online-tab" role="tab">Online Streams</a>
	</li>
	<li class="nav-item">
	  <a class="nav-link" data-toggle="tab" href="#offline-tab" role="tab">Offline Streams</a>
	</li>
  </ul>
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="online-tab">
	  
	  <div class="card" id="online-members-card">
		<div class="card-header">
		  <h5 class="mb-0">
			Members
		  </h5>
		</div>
		  <div class="card-body">
		  </div>
	  </div>
	  <div class="card" id="online-repeaters-card">
		<div class="card-header">
		  <h5 class="mb-0">
			Repeaters
		  </h5>
		</div>
		  <div class="card-body">
		</div>
		</div>
	  <div class="card" id="online-events-card">
		<div class="card-header">
		  <h5 class="mb-0">
			Events
		  </h5>
		</div>
		  <div class="card-body">
		  </div>
	  </div>
	  
    </div>
    <div role="tabpanel" class="tab-pane" id="offline-tab">
	
	<div id="accordion">
	  <div class="card" id="offline-members-card">
		<div class="card-header" id="headingOne">
		  <h5 class="mb-0">
			<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#offline-members-card-collapse" aria-expanded="false" aria-controls="collapseOne">
			  Members
			</button>
		  </h5>
		</div>
	
		<div id="offline-members-card-collapse" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
		  <div class="card-body">
		  </div>
		</div>
	  </div>
	  <div class="card" id="offline-repeaters-card">
		<div class="card-header" id="headingTwo">
		  <h5 class="mb-0">
			<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#offline-repeaters-card-collapse" aria-expanded="false" aria-controls="collapseTwo">
			  Repeaters
			</button>
		  </h5>
		</div>
		<div id="offline-repeaters-card-collapse" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
		  <div class="card-body">
		  </div>
		</div>
	  </div>
	  <div class="card" id="offline-events-card">
		<div class="card-header" id="headingThree">
		  <h5 class="mb-0">
			<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#offline-events-card-collapse" aria-expanded="false" aria-controls="collapseThree">
			  Events
			</button>
		  </h5>
		</div>
		<div id="offline-events-card-collapse" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
		  <div class="card-body">
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>

</div><!-- #content -->
</div><!-- #primary -->
<?php get_footer(); ?>
