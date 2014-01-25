<?php

  require_once('template.class.php');


  class TemplateList {

    private   $rootfolder = "";
    private   $subfolders = array();

    private   $start  = 0;
    private   $timers = array();


    // array containing all the listed templates
    public  $items = array();


    public function __construct ($rootfolder, $recursive = false) {

      $this->start = microtime(true);
      if ( !file_exists($rootfolder) || !is_dir($rootfolder) ) {
        return;
      }

      $this->rootfolder = $rootfolder;

      $this->init();

      $this->timers['ready'] = microtime(true);
    }


    public function showTimers () {
      $now = microtime(true);
      print($this->start . "\n");
      foreach ($this->timers as $key => $value) {
        print("\t$key : " . ($value - $this->start . "\n"));
      }
    }


    public static function folderToKey ($folder) {
      $result = "";
      $charsToDelete = 0;

      if ( strpos($folder, ".") === 0 ) {
        $charsToDelete++;
      }
      if ( strpos($folder, "/") <= 1 ) {
        $charsToDelete++;
      }

      return str_replace("/", "-", substr($folder, $charsToDelete));

    }


    private function init () {


      $this->timers['init'] = microtime(true);


      $this->subfolders = glob($this->rootfolder . "/*", GLOB_ONLYDIR);

      if ( count($this->subfolders) === 0 ) {
        return false;
      }

      // print ("<pre>\n");

      foreach ( $this->subfolders as $folder ) {
        if ($folder === "./assets") {
          continue;
        }

        // $key = TemplateList::folderToKey($folder);
        $this->items[TemplateList::folderToKey($folder)] = TemplateList::readFolder($folder);
      }


      // print ("</pre>\n");

    }


    private function readFolder ($folder) {

      // print ($folder . "\n");


      $templates = array();

      // create a list of all "[nn]x[nn].html" files in current directory
      if ($handle = opendir($folder)) {
        while (false !== ($entry = readdir($handle))) {

          if ( $entry === "." || $entry === ".." ) {
            continue;
          }



          $entrypath = "$folder/$entry";


          if(is_dir($entrypath)) {

            $templates[TemplateList::folderToKey($entrypath)] = $this->readFolder($entrypath);
            // foreach ($templategroup as $template) {
            //   print ("\ttemplate : " . $template . "\n");
            // }
            continue;
          }



          if( (strpos($entry, '.html') !== false) && filesize($entrypath) ) {
            $name = preg_replace("/.html/", "", $entry);

            $entryname = "$folder/$name";

            $templates[$name] = new Template($entrypath);

            // print ( "\t" . TemplateList::folderToKey($entryname) . "\t => " . $entrypath . "\n");

          }
        }
        closedir($handle);
      }
      else {
        // print( "unable to open dir : $folder \n" );
      }

      ksort($templates);
      return $templates;
    }





    public function render ($defaults = null, $showsource = false) {


      foreach ($this->items as $site => $customers) {
        print ("<div id='$site' class='site'>\n");
        foreach ($customers as $customer => $formats) {
          print ("<div id='$customer' class='customer'>\n");
          if(is_array($formats)) {
            foreach ($formats as $format => $template) {
              if($template instanceof Template) {
                $template->render($defaults, $showsource);
              }
              else {
                print("NOT A TEMPLATE : $format");
              }
            }
          }
          else {
            if($formats instanceof Template) {
              print("<p /><a name='$customer' href='#$customer'>$customer</a>\n");
              $formats->render($defaults, $showsource);
              // $template->showSource();
            }
            else {
              // print("\t\tNOT A TEMPLATE : $formats\n");
            }
          }
          print ("</div>\n"); // closes the customer tag
        }
        print ("</div>\n"); // closes the site tag
      }


    }

  }


?>