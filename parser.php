<?php
function process_and_parse(){

	global $variables;
	global $hints;

	$requires_code = "<!DOCTYPE html>\n<html data-require=\"";
	$title_code = "\">\n<head>\n\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n\t<title>";
	$vars_code = "</title>\n\t<script src=\"../khan-exercise.js\"></script>\n</head>\n<body>\n\t<div class=\"exercise\">\n\t<div class=\"vars\"";
	$close_vars_code = "\">";
	$var_id_code = "\t<var id=\"";
	$end_var_code = "</var>\n\t";

	$problem_code = "</div>\n\n\t<div class=\"problems\">\n\t\t<div>\n\t\t\t<div class=\"problem\">\n\t\t\t\t<p>";
	$question_code = "</p>\n\t\t\t</div>\n\t\t\t<div class=\"question\">\n\t\t\t\t<p>";
	$solution_code1 = "</p>\n\t\t\t</div>\n\t\t\t<div class=\"solution\" data-inexact data-max-error=\"";
	$solution_code2 = "\" data-type=\"decimal\">";
	$hints_code = "</div>\n\t\t</div>\n\t</div>\n\n\t<div class=\"hints\">\n";

	$end_hints_and_file_code = "\t</div>\n\t</div>\n</body>\n</html>\n";

	$no_var_comment = "\t<!-- There are no variables! -->\n\t";
	$no_hint_comment = "\t\t<!-- There are no hints! -->\n";

	$super_String = $requires_code."math"." word-problems".$title_code.$_SESSION['subject_session'].$vars_code.">\n\t";

	if ($variables->get_my_vars()) {
		foreach ($variables->get_my_vars() as $name => $type_of_var) {
			$super_String = $super_String . $var_id_code.$name.$close_vars_code.$type_of_var.$end_var_code;
		}
	} else {
		$super_String = $super_String . $no_var_comment;
	}
	
	$super_String = $super_String . $problem_code.$_SESSION['statement_session'].$question_code.$_SESSION['text_session'].$solution_code1;

	if ($_SESSION['solution_error_session'] != "") {
		$super_String = $super_String . $_SESSION['solution_error_session'] .$solution_code2.$_SESSION['solution_session'].$hints_code;
	} else {
		$super_String = $super_String . "0".$solution_code2.$_SESSION['solution_session'].$hints_code;
	}

	if ($hints->get_my_vars()) {
		foreach ($hints->get_my_vars() as $index => $hint) {
			$super_String = $super_String .  "\t\t<div>".$hint."</div>\n";
		}
	} else {
		$super_String = $super_String .  $no_hint_comment;
	}

	$super_String = $super_String .  $end_hints_and_file_code;
	echo $super_String;

	$fh = fopen("./Khan Academy/code/khanacademy-stable/khan-exercises/exercises/aa.html", 'w') or exit("Unable to open file!");
	fwrite($fh, $super_String);
	fclose($fh);
}






?>