<?php
/*
Plugin Name: blumenversand-weltweit.de News
Plugin URI: http://wordpress.org/extend/plugins/blumenversand-weltweitde-news/
Description: Adds a customizeable widget which displays the latest news by http://www.blumenversand-weltweit.de/
Version: 0.1
Author: Frank Kugler
Author URI: http://www.blumenversand-weltweit.de/
License: GPL3
*/

function blumennews()
{
  $options = get_option("widget_blumennews");
  if (!is_array($options)){
    $options = array(
      'title' => 'blumenversand-weltweit.de News',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Objekt erzeugen 
  $rss = simplexml_load_file( 
  'http://www.blumenversand-weltweit.de/feed/'); 
  ?> 
  
  <ul> 
  
  <?php 
  // maximale Anzahl an News, wobei 0 (Null) alle anzeigt
  $max_news = $options['news'];
  // maximale L�nge, auf die ein Titel, falls notwendig, gek�rzt wird
  $max_length = $options['chars'];
  
  // RSS Elemente durchlaufen 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Titel in Zwischenvariable speichern
    $title = $i->title;
    // L�nge des Titels ermitteln
    $length = strlen($title);
    // wenn der Titel l�nger als die vorher definierte Maximall�nge ist,
    // wird er gek�rzt und mit "..." bereichert, sonst wird er normal ausgegeben
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_blumennews($args)
{
  extract($args);
  
  $options = get_option("widget_blumennews");
  if (!is_array($options)){
    $options = array(
      'title' => 'blumenversand-weltweit.de News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  blumennews();
  echo $after_widget;
}

function blumennews_control()
{
  $options = get_option("widget_blumennews");
  if (!is_array($options)){
    $options = array(
      'title' => 'blumenversand-weltweit.de News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['blumennews-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['blumennews-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['blumennews-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['blumennews-CharCount']);
    update_option("widget_blumennews", $options);
  }
?> 
  <p>
    <label for="blumennews-WidgetTitle">Widget Title: </label>
    <input type="text" id="blumennews-WidgetTitle" name="blumennews-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="blumennews-NewsCount">Max. News: </label>
    <input type="text" id="blumennews-NewsCount" name="blumennews-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="blumennews-CharCount">Max. Characters: </label>
    <input type="text" id="blumennews-CharCount" name="blumennews-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="blumennews-Submit"  name="blumennews-Submit" value="1" />
  </p>
  
<?php
}

function blumennews_init()
{
  register_sidebar_widget(__('blumenversand-weltweit.de News'), 'widget_blumennews');    
  register_widget_control('blumenversand-weltweit.de News', 'blumennews_control', 300, 200);
}
add_action("plugins_loaded", "blumennews_init");
?>
