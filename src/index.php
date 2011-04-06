<?php session_start(); ?>
<!doctype html>
<html lang="cs-cz" dir="ltr">
<head>
	<meta charset="UTF-8">
	<title>Test</title>
	<style type="text/css">
		<![CDATA[
			* {
				
			}
			body {
				font-size: 14px;
				font-family: Tahoma, Arial, sans-serif;
				background-color: white;
				color: #555;
			}
			form {
				background-color: #ccc;
				padding: 20px;
				border: 1px solid #aaa;
				border-radius: 10px;
				-moz-border-radius: 10px;
				-webkit-border-radius: 10px;
				width: 300px;
				margin: 20px auto;
			}
			dt {
				float: left;
				display: block;
				width: 100px;
				text-align: right;
				padding-right: 10px;
				padding-top: 2px;
			}
			input, textarea, select {
				margin-bottom: 10px;
				border: 1px solid #aaa;
				border-radius: 4px;
				-moz-border-radius: 4px;
				-webkit-border-radius: 4px;
				padding: 3px;
			}
			input[type=submit] {
				clear: both;
			}
			.lform-item-errors {
				color: red;
				display: block;
				float: right;
				margin-top: -46px;
				font-size: 10px;
			}
			.lform-radiolist {
				display: block;
			}
			.error {border-color: red;}
		]]>
	</style>
</head>
<body>
<?php
require_once 'lforms.php';

		$form = new LForms('comment');
		$form->addItem('hidden','skryta');
		$form->addItem('text','name', TRUE)->setLabel('Jméno');
		$form->addItem('text','email', TRUE)->setLabel('email','neveřejné')
			->setValue('@')->addEmptyValue('@');
		$form->addItem('text','www')->setLabel('www')
			->setValue('http://')->addEmptyValue('http://');
		$form->addItem('textarea','message')->setLabel('Zpráva');
		//$form->addItem('file','image', TRUE)->setRequiredFiletype('jpg|jpeg');
		$form->addItem('checkbox','testxtxt')->setLabel('Neco')
			->setValue('1')
			->setDefaultValue('0');
		$form->addItem('submit','post_comment')
			->setValue('Připsat')
			->addEmptyValue('Připsat');
		$form->loadValues();
		
		if ($form->isSent()) if($form->isValid()) {
			print_r($form->getValues());
  		}
		
		$form->loadValues();
		
		echo $form->render();
?>
</body>
</html>