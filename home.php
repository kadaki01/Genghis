<div class="container">
	
	<?php
	include("variable_Modal.php");
	include("parser.php");
	include("variable_handling.php");

	if (isset($_SESSION['session_variables']) || isset($_SESSION['session_hints'])) {
		$variables = unserialize($_SESSION['session_variables']);
		$hints = unserialize($_SESSION['session_hints']);	
	} else {
		$variables = new set_of_vars();
		$hints = new set_of_vars();
	}

	if (isset($_SESSION['text_session'])) {
		$exercise_text = $_SESSION['text_session'];	
	}
	if (isset($_SESSION['subject_session'])) {
		$subject_text = $_SESSION['subject_session'];	
	}
	if (isset($_SESSION['statement_session'])) {
		$statement_text = $_SESSION['statement_session'];	
	}
	if (isset($_SESSION['solution_session'])) {
		$solution_text = $_SESSION['solution_session'];	
	}

	if ($_POST['s'] == "submit_changes" || $_POST['s'] == "submit_changes_edit" || $_POST['s'] == "submit_changes_edit_hint") {

		if($_POST['s'] == "submit_changes_edit"){
			$variables->replace_variable($_POST['old_name'], $_POST['var_type'], $_POST['var_name'], $_POST['min'], $_POST['max']);
		} elseif (isset($_POST['var_name']) && $_POST['var_name'] != "") {
			$variables->add_variable($_POST['var_type'], $_POST['var_name'], $_POST['min'], $_POST['max']);
		} 

		if ($_POST['s'] == "submit_changes_edit_hint") {
			$hints->replace_variable($_POST['old_hint_index'] ,"Hint", $_POST['editted_hin_text']);
		} elseif (isset($_POST['hin_text']) && $_POST['hin_text'] != "") {
			$hints->add_variable("Hint", $_POST['hin_text']);
		}
		
	} else if ($_POST['s'] == "cancel_changes") {
		header('Location: http://www.gast.it.uc3m.es/~jusanzm');
	} 

	if (isset($_POST['exer_text'])) {
		$exercise_text = $_POST['exer_text'];
	}
	if (isset($_POST['subj_text'])) {
		$subject_text = $_POST['subj_text'];
	}
	if (isset($_POST['stat_text'])) {
		$statement_text = $_POST['stat_text'];
	}
	if (isset($_POST['sol_text'])) {
		$solution_text = $_POST['sol_text'];
	}
	
	if ($_GET['del'] != "") {
		$variables->delete_variable($_GET['del']);
		header('Location: http://www.gast.it.uc3m.es/~jusanzm'); 
	}

	if ($_GET['del_hint'] != "") {
		$hints->delete_variable($_GET['del_hint']);
		header('Location: http://www.gast.it.uc3m.es/~jusanzm'); 
	}


	$_SESSION['session_variables'] = serialize($variables);
	$_SESSION['session_hints'] = serialize($hints);
	$_SESSION['text_session'] = $exercise_text;
	$_SESSION['statement_session'] = $statement_text;
	$_SESSION['subject_session'] = $subject_text;
	$_SESSION['solution_session'] = $solution_text;
	?> 
	<!-- Example row of columns -->
	<div class="row">
		<div class="span7">

			<!--  %%%%% Creation and management of number variables %%%%%  -->
			<div class="well">
				<h3>Variables</h4>
					<br/>
					<h4>Current variables</h5>	
						<?php
						if (isset($variables)) {
							?>
							<table class="table table-condensed table-striped">
								<thead>
									<tr>
										<th>Edit</th>
										<th>Type of Variable</th>
										<th>Name</th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ($variables->get_my_vars() as $name => $type_of_var) {
										if ($_GET['edit'] != "$name") {
											?>	
											<tr>
												<td class="span1">
													<a href=<?php echo "\"?edit=" . $name . "\""; ?> rel="tooltip" title=<?php echo "\"Edit variable ". $name."\""; ?>><span id="tooltips" style="line-height:175%"><i class="icon-pencil"></i></span></a>
												</td>
												<td class="span5">
													<?php $variables->pretty_print($type_of_var); ?>
												</td>
												<td>
													<?php echo $name; ?>
												</td>
												<td class="span1">
													<a class="close" href=<?php echo "\"?del=" . $name . "\""; ?> >&times;</a>
												</td>
											</tr>
											<?php 
										} else {
											?>
											<tr>
												<form class="form-inline" action="?p=home" method="post">

													<td>
														<button name="s" value="submit_changes_edit" type="submit" class="btn btn-success"><i class="icon-ok"></i></button>
														<button name="s" value="cancel_changes" class="btn btn-danger"><i class="icon-remove"></i></button>
													</td>
													<?php $properties = $variables->property_dump($type_of_var);  ?>
													<td>

														<select class="span2" name="var_type" id="var_type" onChange='remove_textbox()'>
															<?php 
															foreach (array('Number', 'Person name') as $key => $value) {
																if ($value == $properties[0]) {
																	echo "<option value=\"".$value."\" selected=\"selected\">".$value."</option>";
																} else {
																	echo "<option value=\"".$value."\">".$value."</option>";
																}
															}
															?>
														</select>
														
														<input type="text" class="input-small" value=<?php echo "\"".$properties[1]."\""; ?> name="min" id="min">
														<input type="text" class="input-small" value=<?php echo "\"".$properties[2]."\""; ?> name="max" id="max">
														<input type="hidden" name="old_name" value=<?php echo "\"".$name."\""; ?> >
													</td>
													
													<td>
														<input type="text" class="input-small" value=<?php echo "\"".$name."\""; ?> name="var_name" id="var_name">
													</td>
													
												</form>
											</tr>

											<?php
										}
									}

									?>
								</tbody>
							</table>
							<?php
						} else {
							echo "There are no variables!";
						}

						?>
						<h4>Add new variable</h4>
						<form class="form-inline" action="?p=home" method="post">

							<!-- <div class="controls"> -->
							<select class="span2" name="var_type" id="var_type" onChange="remove_textbox()">
								<option>Number</option>
								<option>Person name</option>
							</select>
							<input type="text" class="input-small" placeholder='Variable name' name="var_name" id="var_name">
							<input type="text" class="input-small" placeholder="Minimum" name="min" id="min">
							<input type="text" class="input-small" placeholder="Maximum" name="max" id="max">
							<button type="submit" name="s" value="submit_changes" class="btn btn-success"><i class="icon-plus-sign icon-white"></i></button>
						</form>
						<!-- </div> -->
					</div>

					<!--  %%%%% Creation and management of the exercise text %%%%%  -->
					<div class="well">

						


						
						<form class="" action="?p=home" method="post">
							<h4>Title</h4>
							<textarea class="span6" name="subj_text" id="subj_text" rows="1"><?php
							if (isset($subject_text)) {
								echo $subject_text;
							}
							?></textarea>
							<h4>Statement</h4>
							<textarea class="span6" name="stat_text" id="stat_text" rows="5"><?php
							if (isset($statement_text)) {
								echo $statement_text;
							}
							?></textarea>
							<h4>Question</h4>
							<textarea class="span6" name="exer_text" id="exer_text" rows="5"><?php
							if (isset($exercise_text)) {
								echo $exercise_text;
							}
							?></textarea>
							<h4>Solution</h4>
							<textarea class="span6" name="sol_text" id="sol_text" rows="1"><?php
							if (isset($solution_text)) {
								echo $solution_text;
							}
							?></textarea>
							<h3>Hints</h3>
							<div class="well">
								<h4>Current Hints</h4>
								<?php if ($hints->get_my_vars()) {
									?>
									<table class="table table-condensed table-striped">
										<thead>
											<tr>
												<th>Edit</th>
												<th>#</th>
												<th>Content</th>
											</tr>
										</thead>
										<tbody>
											<?php
											foreach ($hints->get_my_vars() as $index => $hint) {
												if ($_GET['edit_hint'] != "$index") {
													?>	
													<tr>
														<td class="span1">
															<a href=<?php echo "\"?edit_hint=" . $index . "\""; ?> rel="tooltip" title=<?php echo "\"Edit hint ". ($index+1)."\""; ?>><span id="tooltips" style="line-height:175%"><i class="icon-pencil"></i></span></a>
														</td>
														<td class="span1">
															<?php echo $index+1; ?>
														</td>
														<td>
															<?php echo $hint; ?>
														</td>
														<td>
															<a class="close" href=<?php echo "\"?del_hint=" . $index . "\""; ?> >&times;</a>
														</td>
													</tr>
													<?php 
												} else {
													?>
													<tr>
														<form class="form-inline" action="?p=home" method="post">

															<td>
																<button name="s" value="submit_changes_edit_hint" class="btn btn-success"><i class="icon-ok"></i></button>
																<button name="s" value="cancel_changes" class="btn btn-danger"><i class="icon-remove"></i></button>
															</td>
															<td class="span1">
																<?php echo $index+1; ?>
															</td>
															<td>
																<textarea class="span5" name="editted_hin_text" id="eddited_hin_text" rows="2"><?php echo $hint; ?></textarea>
																<input type="hidden" name="old_hint_index" value=<?php echo "\"".$index."\""; ?> >
															</td>
														</form>
													</tr>
													<?php
												}

											}

											?>
										</tbody>
									</table>
									<?php
								} else {
									echo "There are no hints!";
								} ?>

								<h4>Add new hint</h4>
								<textarea class="span6" name="hin_text" id="hin_text" rows="2"><?php
							//if (isset($hints_text)) {
							//	echo $hints_text;
							//}
								?></textarea>
							</div>
							<button type="submit" name="s" value="submit_changes" class="btn">Save text</button>
						</form>
					</div>
					<!-- <p><a class="btn btn-primary btn-large" href="?process=1">Done, please parse! &raquo;</a></p>  -->
				</div>


				<div class="span5">
					<ul class="nav nav-tabs" id="tab">
						<li class="active"><a href="#preview" data-toggle="tab">Preview</a></li>
						<li><a href="#code" data-toggle="tab">Code</a></li>
					</ul>

					<div id="myTabContent" class="tab-content">
						<div style="margin-left:-30px;" class="tab-pane active" id="preview">
							<iframe class="span5" src="http://163.117.141.253:8000/exercises/aa.html" height="700" frameborder="0" ></iframe>

							
						</div>
						<div class="tab-pane fade" id="code">
							<textarea class="span5" rows="30"><?php process_and_parse(); ?></textarea>
						</div>
					</div>

				</div>

			</div>












			<hr>
			<footer>
				<p>&copy; UC3M 2012</p>
			</footer>

</div> <!-- /contai