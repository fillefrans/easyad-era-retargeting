<html lang="no">
  <head>
    <meta charset="utf-8">

    <style type="text/css">

      #editor { 
        position  : absolute;
        top       : 0;
        right     : 0;
        bottom    : 0;
        left      : 0;
      }


      .sourcecode {
        width   : 80%;
        display : none;
      }

      .horizontal {
        width   : 100%;
        margin  : 12px;
      }

      .horizontal ul {
        width   : 100%;
        display : inline;

        list-style-type : none; 
        padding-right   : 20px;
      }

      .horizontal li {
        display           : inline;
        list-style-type   : none;
        padding-right     : 20px;
      }

    </style>

  </head>
  <body>




    <div class="horizontal">
      <ul id="toc"></ul>
    </div>

    <script>


      window.addEventListener('load', function() {

        /*  support functions  */
        function insertAfter(referenceNode, newNode) {
            referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
        }


        function onHashChange () {
          var
            currentId       = window.location.hash.substr(1),
            editorId        = currentId.replace("-source", "-editor"),
            currentElement  = document.getElementById(currentId),
            editorElement   = document.getElementById(editorId);

          console.log("hashchange     : " + window.location.hash);
          console.log("currentId      : " + currentId);

          // console.log("currentElement : " + currentElement.tagName);

          if(currentElement  && ( currentElement.tagName && currentElement.tagName === "TEXTAREA")) {
            // currentElement.style.display = "block";
            var
              editor = ace.edit(editorId);

            editor.setOptions({
              maxLines : Infinity,
              useWrapMode : true,
              fontSize : 16
            });
            // alert(currentElement.value);
            editor.setTheme("ace/theme/monokai");
            editor.getSession().setMode("ace/mode/html");
            editor.getSession().setUseWrapMode(true);
            editor.getSession().setUseSoftTabs(true);
            editor.setReadOnly(true);
            // editor.commands.bindKey("Tab", null);
            editor.getSession().setValue(currentElement.value);
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

      require_once('templatelist.class.php');


      // set some example data for previewing purposes
      $defaults = array(
        "custom4"     => "assets/images/test.jpg", 
        "custom9"     => "Smakfull enplansvilla mot natur och strövområde", 
        "shouttitle"  => "UPPSALA – LUTHAGEN, GÖTGATAN 13",
        "shouttext"   => "Nyrenoverad enplansvilla om 74 välplanerade kvadrat! Stor södervänd tomt med bl a skog och allmänning som granne"
        );

      $templates = new TemplateList(".");

      $templates->render($defaults, true); // second param is whether to show source code

      // $templates->showTimers();

      // // then render all the templates with example data
      // foreach ($templates->items as $key => $template) {
      //   print("<p /><a name='$key' href='#$key'>$key</a>\n");

      //   $template->render($defaults);
      //   $template->showSource();
      // }

    ?>


  <script src="/pi/assets/js/lib/ace/ace.js" type="text/javascript" charset="utf-8"></script>

  </body>
</html>