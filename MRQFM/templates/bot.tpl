<!DOCTYPE html>
<html>
  
  <script src="templates/public/js/jquery.min.js"></script>
  <head>
    <title>小Q老师的训练场</title>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="小Q老师,最懂你!">
	<meta name="author" content="Summit">


    <!-- Bootstrap -->
    <link href="templates/public/css/bootstrap.min.css" rel="stylesheet">
    
   
    
  </head>
  <body style="overflow-x:hidden;">
     
  
   	<div class="container">
    	<div class="row">
        	<form class="form-horizontal span6" action="bot.php" method="post">
			  <div class="control-group">
				<label class="control-label" for="input">输入</label>
				<div class="controls">
					<textarea rows="3"  id="input" name="input" placeholder="跟小Q老师说的话" ></textarea>
				</div>
			  </div>
			   <div class="control-group">
				<label class="control-label" for="output">响应</label>
				<div class="controls">
					<textarea rows="5"  id="output" name="output" placeholder="小Q老师回答的话" ></textarea>
				</div>
			  </div>
			  <div class="form-actions">
				<button type="submit" class="btn btn-primary">提交</button>
			  </div>
			</form>
			<form class="form-horizontal span6" action="bot.php" method="post">
			  <div class="control-group">
				<label class="control-label" for="message">发送内容</label>
				<div class="controls">
					<textarea rows="5"  id="message" name="message" placeholder="跟小Q老师说的话" ></textarea>
				</div>
			  </div>
			   <div class="control-group">
				<label class="control-label" for="respond">响应内容</label>
				<div class="controls">
					<textarea rows="3"  id="respond" name="respond">{$respond}</textarea>
				</div>
			  </div>
			  <div class="form-actions">
				<button type="submit" class="btn btn-primary">发送</button>
			  </div>
			</form>
        </div>
		
    </div>
    
    
    
    
    <!-- Bootstrap -->
    <script src="templates/public/js/bootstrap.js"></script>
	

 
  </body>
</html>