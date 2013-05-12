<?php
require_once 'csrf-magic.php';
ini_set('display_errors', 0);
session_start();
include('config.php');
require_once 'library/HTMLPurifier.auto.php';
$config = HTMLPurifier_Config::createDefault();
$config->set('Core.Encoding', 'UTF-8'); 
$config->set('HTML.Doctype', 'XHTML 1.0 Transitional'); 
$purifier = new HTMLPurifier($config);
?> 
 <!DOCTYPE html> 
 <html lang="en">
 <head>
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta name="description" content=""> <meta name="author" content="">
 <meta name="keywords" content="">
 <meta charset='utf-8'>
 <meta name="msvalidate.01" content="AE9E53FE30852E5FCF9F04F6FD04DAE8" />
 <link href="bootstrap/css/bootstrap.css" rel="stylesheet"> 
 <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> 
 <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
<script src="http://static.tinymce.com/tinymce/js/4.0b1/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
  selector: "textarea",
	theme: "modern",
	plugins: [
		"advlist autolink lists link image charmap print preview hr anchor pagebreak",
		"searchreplace wordcount visualblocks visualchars code fullscreen",
		"insertdatetime media nonbreaking save table contextmenu directionality",
		"emoticons template paste"
	],
	toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
	toolbar2: "print preview media | forecolor backcolor emoticons",

	autosave_ask_before_unload: false
});
</script>

 <style>
body {
		margin-top: 50px;
		margin-right: 20px;
		margin-bottom: 10px;
		margin-left: 20px;
	}
 li{ 
 color:#484848; font-family: TimesNewRoman, "Times New Roman", Times, Baskerville, Georgia, serif; }
 pre{ background-color:black; color:#00CC00; } 
 #content { text-align:left; color:#484848; font-size:small; font-family: Georgia, Serif;}
p.center{
text-align:center;
}
table {
margin-left:auto;
margin-right:auto;
}
th{
background: #636363; /* Old browsers */
background: -moz-linear-gradient(top,  #636363 0%, #1f2227 54%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#636363), color-stop(54%,#1f2227)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  #636363 0%,#1f2227 54%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  #636363 0%,#1f2227 54%); /* Opera 11.10+ */
background: -ms-linear-gradient(top,  #636363 0%,#1f2227 54%); /* IE10+ */
background: linear-gradient(to bottom,  #636363 0%,#1f2227 54%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#636363', endColorstr='#1f2227',GradientType=0 ); /* IE6-9 */
}
td{
background: #1f2227; /* Old browsers */
}
table tr.separator { height: 5px; }
#panel
{
padding:10px;
display:none;
}
textarea#sty {
    width:970px;
    height: 120px;
    border:1px solid #cccccc;
    padding:0px;
    font-family: Tohama,sans-serif;
}
.spacer5 { height: 20px; width: 100%; font-size: 0; margin: 0; padding: 0; border: 0; display: block; }
</style> 
 </head> <body> 
<div class="navbar-wrapper">
<div class="container">
<div class="navbar navbar-fixed-top">
<div class="navbar-inner">
<ul class="nav">
<li><a href="http://megalodon.com"><strong>Megalodon.com</strong></a></li>
<li><a href="index.php">Home</a></li>
<li class="active"><a href="index.php">Forum</a></li>
<li><a href="control.php">Control Panel</a></li>
<li><a href="register.php">Register</a></li>
</ul>
<?php
session_id($_COOKIE['PHPSESSID']);
session_start();
if(!empty($_SESSION['username'])){
$usr = htmlspecialchars($_SESSION['username']);
echo "<ul class=\"nav pull-right\"><li class=\"divider-vertical\"></li><li class=\"dropdown\" > <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">Welcome Back&nbsp;,&nbsp;$usr</a><ul class=\"dropdown-menu\"><li><a href=\"control.php\">Settings</a></li><li class=\"divider\"></li><li><a href=\"logout.php?".SID."\">Logout</a></li></ul></li></ul>";
//echo '<p style="display:block;float:right;padding:3px 5px 5px;margin-right:8px;color: #008dbb;">Welcome&nbsp;back&nbsp;,&nbsp;'.$usr.'</p><a style="display:block;float:right;padding:3px 5px 5px;margin-right:8px;" class="btn btn-info btn-small" href="logout.php?'.SID.'">Logout.</a></div>';
}else{

echo '<ul class="nav pull-right"><li class="divider-vertical"></li><li><a href="/login">Login</a></li></ul>';
}

?>
			 
            </div> 
		
          </div> 
        </div> 
      </div>  
    </div> 
<div class="spacer5"></div>
<?php
//***********************Threads************************//

?>
<?php
if (!$_GET['id']) {
    $current = htmlspecialchars($_SERVER['PHP_SELF']);
    $db      = new PDO("mysql:host=127.0.0.1;dbname=$db_database", $db_username, $db_password);
    $query   = $db->query("SELECT topic_id,topic_title,date_format(topic_create_time,'%d %b %Y %h:%i %p') as fmt_topic_create_time,topic_owner from forum_topics order by topic_create_time desc");
    $rows    = $query->rowCount();
    if ($rows < 1) {
        $display = "<p><em>No Threads.</em></p>";
    } else {
        $display = "
<ul class=\"breadcrumb\" style=\"margin-left:-13px;font-size:30px;\">
  <li class=\"active\"><a href=\"topics.php\">ACT4Security Forums</a> <span class=\"divider\">/</span></li>
</ul>
<table class=\"table table-hover\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
<tr><th>Thread / Author</th>
<th>Replies</th></tr>";
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $topic_id          = $row['topic_id'];
            $topic_title       = htmlspecialchars($purifier->purify($row['topic_title']));
            $topic_create_time = $row['fmt_topic_create_time'];
            $topic_owner       = $purifier->purify($row['topic_owner']);
            $num_res           = $db->query("SELECT COUNT(post_id) from forum_posts where topic_id = $topic_id");
            $num_posts         = $num_res->fetchColumn();
            $display .= "<tr><td><a href=\"topics.php?id=$topic_id\">
	<strong>$topic_title</strong></a><br/>
	Created on $topic_create_time by $topic_owner</td>
	<td align=center>$num_posts</td>
	</tr>";
        		}
   			 }
  	  $display .= "</table>
     	 	       <p><div  id=\"flip\"><button class=\"btn btn-primary\">New Thread</button></div>
    		       <div id=\"panel\">
		       <form method=\"post\" action=\"$current\">
	    	       <p><strong>Thread Subject:</strong></p>
			<input type=\"text\" name=\"topic_title\" class=\"input-xxlarge\" size=200 maxlength=150>
			<p><strong>Your Message:</strong><br />
	  	        <textarea name=\"post_txt\" rows=8 cols=80 id=\"sty\" wrap=virtual></textarea>
			<br /><button class=\"btn btn-primary\" type=\"submit\">Post Thread</button>
			</form>
			</div>";
    
    if (isset($_SESSION['username'])) {
        $owner = $_SESSION['username'];
        $title = $_POST['topic_title'];
        $text  = $_POST['post_txt'];
        if ($title == '' or $text == '') {
            
        } else {
            $db    = new PDO("mysql:host=127.0.0.1;dbname=$db_database", $db_username, $db_password);
            $query = "INSERT INTO forum_topics values('',?,now(),?)";
            $par   = array($title, $owner);
            $stmt  = $db->prepare($query);
            $stmt->execute($par);
            $id    = $db->lastInsertId('topic_id');
            $query = "INSERT INTO forum_posts values('',?,?,now(),?,'1')";
            $par   = array($id,$text,$owner);
            $stmt  = $db->prepare($query);
            $stmt->execute($par);
            echo "<div class=\"alert alert-info\"> <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button><p>The <strong>$title</strong> Topic has been created.</p></div>";
           }
    } else
			{
        echo "<div id=\"panel\" ><div class=\"alert alert-error\"> <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>Only registered users can post a new Topic to register click <a href=\"register.php\">here</a></div></div>";
   	  }
	
    print $display;
				}else {
		if(!(isset($pagenum)))
			{
			$pagenum = 1;
			}
    $db   = new PDO('mysql:host=127.0.0.1;dbname=$db_database', $db_username, $db_password);
    $sqls = "SELECT topic_title FROM forum_topics WHERE topic_id =?";
    $pres = array($_GET['id']);
    $qu   = $db->prepare($sqls);
    $qu->execute($pres);
		$pagenum = $_GET['page'];
    $rows = $qu->rowCount();
    $result      = $qu->fetch(PDO::FETCH_ASSOC);
    $topic_title = $purifier->purify($result['topic_title']);
    $topic_title = htmlspecialchars($topic_title);
    if ($rows < 1) {
        $display = $rows . "<p><em>You Have selected an invalid topic. please <a href=\"topics.php\">Try Again</a>.</em></p>";
    } else {
	$tid = $_GET['id'];
	$goes = "SELECT * FROM forum_posts where topic_id=?";
	$getrow  =$db->prepare($goes); 
	$getrow->bindValue(1,$tid,PDO::PARAM_INT);
	$getrow->execute();
	$gotrow = $getrow->rowCount();
	$pagerows = 7;
	$last = ceil($gotrow/$pagerows);
	if($pagenum < 1 )
	{
	$pagenum=1;
	}
	elseif($pagenum > $last)
	{
	$pagenum = $last;
	}
	$max = ($pagenum - 1 ) * $pagerows;
        $goo   = "SELECT post_id , post_text , date_format(post_create_time,'%d %b %Y %h:%i %p') as fmt_post_create_time,post_owner,verify from forum_posts where topic_id=? order by post_create_time asc limit ?,?";
	
  $querz = $db->prepare($goo);
	 $querz->bindValue(1,$_GET['id'],PDO::PARAM_INT);
	 $querz->bindValue(2,$max,PDO::PARAM_INT);
	$querz->bindValue(3,$pagerows,PDO::PARAM_INT);
        $querz->execute();
$rul = htmlspecialchars($_SERVER['PHP_SELF']);
        $display = "
  <ul class=\"breadcrumb\" style=\"margin-left:-13px;font-size:30px;\">
  <li><a href=\"topics.php\">Megalodon Forums</a> <span class=\"divider\">/</span></li>
  <li class=\"active\">$topic_title</li>
  </ul>
  <table class=\"table table-hover\" id=\"forums\" cellpadding=0.5 cellspacing=0.5 border=0.5>
  <tr><th>Author</th>
  <th>Thread</th>
  </tr>";


        while ($rowz = $querz->fetch(PDO::FETCH_ASSOC)) {
            if($rowz['verify']=='0'){ 
                $post_id = $rowz ['post_id'];
               	$post_text = $purifier->purify('<em>Reply is currently Awaiting for moderation.</em>');
           	$post_create_time = $rowz['fmt_post_create_time'];
            	$post_owner  = htmlspecialchars($purifier->purify($rowz['post_owner']));
                $display .= "
			<tr>
			<td width=15% valign=top>$post_owner<br>[$post_create_time]</td>
			<td width=85% valign=top>$post_text<br><br>
			</tr><tr class=\"separator\" />";
          	  }else{
          	 $post_id          = $rowz['post_id'];
          	 $post_text        = nl2br($purifier->purify($rowz['post_text']));
      		 $post_create_time = $rowz['fmt_post_create_time'];
          	 $post_owner       = $purifier->purify($rowz['post_owner']);
         	 $display .= "
			<tr>
			<td width=15% valign=top>$post_owner<br>[$post_create_time]</td>
		        <td width=85% valign=top>$post_text<br><br>
		        </tr><tr class=\"separator\"></tr>"; 
				}
      					  }
        $in = htmlspecialchars($_GET['id']);
        $poos = htmlspecialchars($_SERVER['PHP_SELF']."?id=".$in);
				$next = $pagenum+1;
				$prev = $pagenum-1;
        $display .= "</table>
		     <div class=\"pagination pagination-small pagination-centered\">
  		     <ul>
   		     <li><a href=\"$poos&page=$pagenum\">Page $pagenum of $last</a></li>
    	   	     <li><a href=\"$poos&page=$prev\">Prev</a></li>";
    	   	     
 for ($i=1;$i <= $last;$i++){
 	 $display .= "<li><a href=\"$poos&page=$i\">$i</a></li>";
	}
		$display .="
 	 <li><a href=\"$poos&page=$next\">Next</a></li>
	 </ul>
	 </div>
      	 <form method=\"post\" action=\"$poos\">
    	<textarea name=\"post_text\" id=\"sty\" rows=10 cols=30 ></textarea>
	<br/>
	<button class=\"btn btn-primary\" type=\"submit\">New Reply</button>
	<br/>
	</form>";
    	    if(isset($_SESSION['username']))
     		   {
           		 if(isset($_POST['post_text']))
           		 {
$db   = new PDO("mysql:host=127.0.0.1;dbname=$db_database", $db_username, $db_password);
$tox  = "INSERT INTO forum_posts values('',?,?,now(),?,'1')";
$ro   = array($_GET['id'],$_POST['post_text'],$_SESSION['username']);
$query = $db->prepare($tox);
$query->execute($ro);
echo "<div class=\"alert alert-info\"> <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>Your Reply is posted.</div>";
      	      }
       	 }else
     	       {
	 if(isset($_POST['post_text'])){
		$db   = new PDO('mysql:host=127.0.0.1;dbname=$db_database', $db_username, $db_password);
	if(isset($_SESSION['username'])){
$tox  = "INSERT INTO forum_posts values('',?,?,now(),?,'1')";
	} else {
$tox = "INSERT INTO forum_posts values('',?,?,now(),?,'0')";
}
$ro   = array($_GET['id'],$_POST['post_text'],'[GUEST]');
$query = $db->prepare($tox);
$query->execute($ro);
echo "<div class=\"alert alert-info\"> <button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>Your Reply is posted.</div>";
  			 }
            			}
  			}
echo "<html><head><title>$topic_title</title></head><body>";
print $display;
    
}
?>

<?php
//******************************FORUM**********************************//
?>

<p style="text-align: center;color: #000000;">Powered By Megalodon Forums&nbsp;&copy;</p>
<script type="text/javascript"> var _gaq = _gaq || []; _gaq.push(['_setAccount', 'UA-39374609-1']); _gaq.push(['_trackPageview']); (function() { var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s); })(); </script> <script src="/bootstrap/jquery-1.9.1.js">
 
 </script> <script src="bootstrap/js/bootstrap.min.js"/></script> <script src="bootstrap/js/bootstrap.js"></script> <script> $(document).ready(function(){ $('carousel').carousel(); $('.dropdown-toggle').dropdown(); }) ;</script> 
 <script> 
function login()
{
$('#login').modal();
}
$(document).ready(function(){

  $("#flip").click(function(){
    $("#panel").slideToggle("slow");
  });
});
</script>

</body> </html>
