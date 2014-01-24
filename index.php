<html lang="no">
	<head>
		<meta charset="utf-8">

		<style type="text/css">
			.horizontal
			{
				width 	: 100%;
				margin 	: 12px;
			}

			.horizontal ul
			{
				width: 100%;
			}

			.horizontal li
			{
				display 				: inline;
				list-style-type 	: none;
				padding-right 		: 20px;
			}
		</style>



	</head>
	<body>



		<div class="horizontal">
			<ul id="toc" style="display:inline; list-style-type: none; padding-right: 20px;"></ul>
		</div>

		<script>


			window.addEventListener('load', function() {


				/*  support functions  */

				function insertAfter(referenceNode, newNode) {
				    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
				}


				function init ()
				{
					var 
						i, li, newAnchor, ad,
						toc = document.getElementById("toc"),
						ads = document.getElementsByClassName("EAad");


					for (var i = ads.length-1; i >= 0; i--) {
						console.log("ad element : " + i);
						if (null !== (ad = ads.item(i)) ) {
							ad.addEventListener("mouseover", function(e) {
								// console.log("ad element: ", this);
							});
						}
					}


					for (i = 0; i < document.anchors.length; i++) {
						// skip the source anchors
						if (document.anchors[i].name.indexOf("-src") > -1) {
							continue;
						}

						li = document.createElement("li");
						newAnchor = document.createElement('a');
						newAnchor.href = "#" + document.anchors[i].name;
						newAnchor.innerHTML = document.anchors[i].text;
						li.appendChild(newAnchor);
						toc.appendChild(li);
					}

				} // init ()



				/*  Initialisation code  */

				init();

			}); // onload event listener

		</script>

	<?php


	require_once('template.class.php');

	$templates = array();


	// create a list of all "[nn]x[nn].html" files in current directory
	if ($handle = opendir('.')) {
		while (false !== ($entry = readdir($handle))) {
			if( (strpos($entry, '.html') !== false) && filesize($entry) ) {
				$templates[preg_replace("/.html/", "", $entry)] = new Template($entry);
			}
		}
		closedir($handle);
	}


	// set some example data for previewing purposes
	$defaults = array(
		"custom4" 		=> "images/test.jpg", 
		"custom9" 		=> "Smakfull enplansvilla mot natur och strövområde", 
		"shouttitle" 	=> "UPPSALA – LUTHAGEN, GÖTGATAN 13",
		"shouttext" 	=> "Nyrenoverad enplansvilla om 74 välplanerade kvadrat! Stor södervänd tomt med bl a skog och allmänning som granne"
		);


	// then render all the templates with example data
	foreach ($templates as $key => $template) {
		print("<a name='$key' href='#$key'>$key</a><br />\n");

		$template->render($defaults);
		print("<a name='$key-src' href='#$key-src'>$key source code</a>\n");
		$template->showSource();
	}


	?>
	</body>
</html>