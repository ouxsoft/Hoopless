<?php
echo '<div class="cover">';
echo '<div class="container">';
echo '<div class="jumbotron">';
echo '<div class="row">';
echo '<div class="col-md-6 ss-transparent">';
$title = '';
$bool = true;
foreach(explode(' ',$instance->website['title']) as $part){
  if($bool){
    $title .= '<em>'.$part.'</em> ';
    $bool = false;
  } else {
    $title .= $part.' ';
  }
}
echo '<h1>'.$title.'</h1>';
echo '<h3>Web Designer & Developer</h3>';
echo '<p>I am a creative system designer and developer who specializes in building and evolving web-based solutions. I have designed and developed systems used to:</p>';
echo '<ul>';
echo '<li>manage and ship hazardous materials;</li>';
echo '<li>generate emission facility reports;</li>';
echo '<li>protect the human health and the environment;</li>';
echo '<li>train and certify employees;</li>';
echo '<li>entertain children;</li>';
echo '<li>manufacturer home decor;</li>';
echo '<li>maintain college web services; et al.</li>';
echo '</ul>';
echo '<p>If you are a business seeking a web-based solution or an employer looking to hire, send me a message.</p>';
echo '<br/><p><button type="button" class="btn btn-primary btn-lg" onclick="window.location.href=\''.$instance->href("contact.html").'\'">I\'m looking to hire<span class="glyphicon glyphicon-menu-right"></span></button> <button type="button" class="btn btn-default btn-lg" onclick="window.location.href=\''.$instance->href("contact.html").'\'">I need a web-solution <span class="glyphicon glyphicon-menu-right"></span></button></p>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
?>