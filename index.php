<html lang="no">
  <head>
    <meta charset="utf-8">

    <style type="text/css">

      .sourcecode {
        width   : 80%;
        display : none;
      }

      .horizontal {
        width   : 100%;
        margin  : 12px;
      }

      .horizontal ul {
        width : 100%;
      }

      .horizontal li {
        display           : inline;
        list-style-type   : none;
        padding-right     : 20px;
      }

    </style>

  <script src="assets/js/lib/codemirror.min.js"></script>
  <link rel="stylesheet" href="assets/css/codemirror.css" />

  </head>
  <body>



    <div class="horizontal">
      <ul id="toc" style="display:inline; list-style-type: none; padding-right: 20px;"></ul>
    </div>

    <script>



      window.addEventListener('load', function() {

        var
          sourceEditor = null,
          editorOptions = {
            lineNumbers : true,
            lineWrapping : true,
            viewPortMargin : Infinity,
            tabSize : 3,
            readOnly : true
          };


        /*  support functions  */
        function insertAfter(referenceNode, newNode) {
            referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
        }


        function onHashChange () {
          var
            currentId       = window.location.hash.substr(1),
            currentElement  = document.getElementById(currentId);

          console.log("hashchange     : " + window.location.hash);
          console.log("currentId      : " + currentId);

          // console.log("currentElement : " + currentElement.tagName);

          if(currentElement  && ( currentElement.tagName && currentElement.tagName === "TEXTAREA")) {
            // currentElement.style.display = "block";
            sourceEditor = CodeMirror.fromTextArea(currentElement, editorOptions);
            sourceEditor.getWrapperElement().style.display = 'block';

            sourceEditor.on("change", function() {
              var
                wrap = sourceEditor.getWrapperElement(),
                approp = sourceEditor.getScrollInfo().height > 200 ? "200px" : "auto";

              if (wrap.style.height != approp) {
                wrap.style.height = approp;
                sourceEditor.refresh();
              }
            });

          }

        } // onHashChange


        function init () {
          var
            i, li, newAnchor, ad,
            toc = document.getElementById("toc"),
            ads = document.getElementsByClassName("EAad");


          if ("onhashchange" in window) {
            window.addEventListener("hashchange", onHashChange);
          }


          for (var i = ads.length-1; i >= 0; i--) {
            if (null !== (ad = ads.item(i)) ) {
              ad.addEventListener("mouseover", function(e) {
                // console.log("ad element: ", this);

              });
            }
          }


          for (i = 0; i < document.anchors.length; i++) {

            // skip the source anchors
            if (document.anchors[i].name.indexOf("source") > -1) {
              continue;
            }

            // create nav link to each ad
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
        "custom4"     => "images/test.jpg", 
        "custom9"     => "Smakfull enplansvilla mot natur och strövområde", 
        "shouttitle"  => "UPPSALA – LUTHAGEN, GÖTGATAN 13",
        "shouttext"   => "Nyrenoverad enplansvilla om 74 välplanerade kvadrat! Stor södervänd tomt med bl a skog och allmänning som granne"
        );


      ksort($templates);

      // then render all the templates with example data
      foreach ($templates as $key => $template) {
        print("<p /><a name='$key' href='#$key'>$key</a>\n");

        $template->render($defaults);
        $template->showSource();
      }

    ?>

  </body>
</html>