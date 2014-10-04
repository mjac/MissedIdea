<html>
<head>
<title>Make DB content</title>
</head>

<?php

require 'MissedIdea.php';

	$facebookId = "JAKE1";
	$objUser = new \MissedIdea\Person($facebookId);

	echo "Make Idea";
	echo "<form id=\"newIdea\" method=\"POST\" action=\"idea.php\">";
	echo "<textarea rows=\"6\" cols=\"30\" name=\"ideaText\" >Insert your idea here...</textarea><br />";
	echo "<input type=\"hidden\" name=\"userId\" value=\"".$objUser->getUserId()."\" >";
	echo "<input type=\"hidden\" name=\"createIdea\" value=\"true\" >";
	echo "What is the category (1-5):<br />";
	echo "<input type=\"text\" name=\"categoryId\" />";
	echo "<input type=\"submit\" value=\"Create idea\" />";
	echo "</form>";
	echo "<br />";
	echo "<br />";

	echo "Add comment";
	echo "<form id=\"newIdea\" method=\"POST\" action=\"comment.php\">";
	echo "<textarea rows=\"6\" cols=\"30\" name=\"commentText\" >Insert your comment here...</textarea><br />";
	echo "<input type=\"hidden\" name=\"createComment\" value=\"true\" >";
	echo "<input type=\"hidden\" name=\"userId\" value=\"".$objUser->getUserId()."\" >";
	echo "Idea Id (be careful!!)<br />";
	echo "<input type=\"text\" name=\"ideaId\" />";
	echo "<input type=\"submit\" value=\"Create idea\" />";
	echo "</form>";
	echo "<br />";
	echo "<br />";
?>

</html>