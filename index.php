<html lang="no">
	<head>
		<meta charset="utf-8">
	</head>
	<body>

		<script>

			window.addEventListener('load', function() {
				var
					ad 	= null,
					ads 	= document.getElementsByClassName("EAad")

				function insertAfter(referenceNode, newNode) {
				    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
				}

				for (var i = ads.length-1; i >= 0; i--) {
					console.log("ad element : " + i);
					if (null !== (ad = ads.item(i)) ) {
						ad.addEventListener("mouseover", function(e) {
							// console.log("ad element: ", this);
						});
					}
				}

			});

		</script>

	<?php


	require_once('template.class.php');

	$templates = array();

	if ($handle = opendir('.')) {
		while (false !== ($entry = readdir($handle))) {
			if( (strpos($entry, '.html') !== false) && filesize($entry) ) {
				$templates[preg_replace("/.html/", "", $entry)] = new Template($entry);
			}
		}
		closedir($handle);
	}

	foreach ($templates as $key => $template) {
		print("<a name='$key' href='#$key'>$key</a><br />\n");

		$defaults = array(
			"custom4" 		=> "images/test.jpg", 
			"custom9" 		=> "Smakfull enplansvilla mot natur och strövområde", 
			"shouttitle" 	=> "UPPSALA – LUTHAGEN, GÖTGATAN 13"
			);

		$template->render($defaults);
		print("<a name='$key-src' href='#$key-src'>$key-src</a>\n");
		$template->showSource();
	}


	?>
	</body>
</html>