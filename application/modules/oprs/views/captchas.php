<!DOCTYPE html>
<html>
	<head>
		<title>Implement Captcha in Codeigniter using helper</title>
	</head>
	<body>
		<p id="image_captcha"><?php echo $captchaImg; ?></p>
		<a href="javascript:void(0);" class="captcha-refresh" ><span class="fa fa-refresh"></span></a>
		<form method="post">
			<input type="text" name="captcha" value=""/>
			<input type="submit" name="submit" value="SUBMIT"/>
		</form>
	</body>
</html>