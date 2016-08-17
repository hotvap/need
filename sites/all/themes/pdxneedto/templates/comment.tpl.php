<div class="lastcomments_item<?php echo ' '.$classes; ?>">
  <?php
  /* print $picture; */ ?>

<?php print render($title_prefix); ?>
<div class="lastcomments_title">
<?php
//if(isset($comment->mail) and strlen($comment->mail)){
//    echo '<a href="mailto:'.$comment->mail.'">'.$comment->name.'</a>';
//}else{
    echo $comment->name;
//}
?>, <?php print '<span>'.date('d.m.Y',$comment->created).'</span>'; ?>
</div>
<?php print render($title_suffix); ?>
<div class="lastcomments_body">
<?php
hide($content['links']);
if( isset($content['comment_body'][0]['#markup']) and strlen($content['comment_body'][0]['#markup']) ){
    echo nl2br($content['comment_body'][0]['#markup']);
}
//print render($content);
print render($content['links']);
?>
</div>
</div> <!-- end comment -->