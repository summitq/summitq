<!DOCTYPE html>
<html>
  
  <script src="templates/public/js/jquery.min.js"></script>
  <head>
    <title>小Q老师的电台</title>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="小Q老师,最懂你!">
	<meta name="author" content="Summit">


    <!-- Bootstrap -->
    <link href="templates/public/css/bootstrap.min.css" rel="stylesheet">
    <link href="templates/public/plugin/css/style.css" rel="stylesheet">
    <link href="templates/public/css/application.css" rel="stylesheet">
    <script type="text/javascript" src="templates/public/plugin/jquery-jplayer/jquery.jplayer.js"></script>
    <script type="text/javascript" src="templates/public/plugin/ttw-music-player.js"></script>
    
     <script type="text/javascript">

		var myPlaylist = [
			{section name=key loop=$playlist}
				{
					mp3:'{$playlist[key].url}',
					oga:'',
					title:'{$playlist[key].title}',
					artist:'{$playlist[key].artist}',
					rating:{$playlist[key].rating_avg},
					buy:'',
					price:'',
					duration:'{$playlist[key].length}',
					cover:'{$playlist[key].picture}'
				} {if $smarty.section.key.last=="0"},{/if}
			{/section}
		];

        $(document).ready(function(){
            var description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce id tortor nisi. Aenean sodales diam ac lacus elementum scelerisque. Suspendisse a dui vitae lacus faucibus venenatis vel id nisl. Proin orci ante, ultricies nec interdum at, iaculis venenatis nulla. ';

            $('#player').ttwMusicPlayer(myPlaylist, {
                autoPlay:true, 
                description:'',
                jPlayer:{
                    swfPath:'public/plugin/jquery-jplayer' //You need to override the default swf path any time the directory structure changes
                }
            });
        });
    </script>
    
  </head>
  <body style="overflow-x:hidden;">
     
  
   	<div class="container">
    	<div class="row">
        	<div class="span12"  id="player">
            </div>
        </div>
    </div>
    
    
    
    
    <!-- Bootstrap -->
    <script src="templates/public/js/bootstrap.js"></script>
	

 
  </body>
</html>