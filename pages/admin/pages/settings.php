<?php
include('lib/alert.class.php');
include('lib/build_tree.function.php');

/*
page_menu
	id
	page_id

page_permission (rename)
	id
	page_id
	`signin_required` (move)
	state
*/

if($instance->verify(true)){
	switch($_POST['submit']){
		case 'save':
			// add validation
			$db->bind('id',$record_id);
			$db->bind('title',$_POST['page']['title']);
			$db->bind('url',$_POST['page']['url']);
			$db->bind('parent_id',$_POST['page']['parent_id']);
			$db->bind('description',$_POST['page']['description']);
			$db->bind('standalone',$_POST['page']['standalone']);
			$db->bind('change_freq',$_POST['page']['change_freq']);
			$db->bind('priority',$_POST['page']['priority']);
			$db->query('UPDATE `pages` SET `parent_id` = :parent_id, `name` = :title, `meta_description` = :description, `link` = :url, `change_freq` = :change_freq, `priority` = :priority, `standalone` = :standalone WHERE `id` = :id LIMIT 1;');

			$db->bind('page_id',$record_id);
			$db->bind('state',$_POST['page']['state']);
			$db->query('UPDATE `page_permissions` SET `state` = :state WHERE `page_permissions`.`page_id` = :page_id');

			$alert->add('success','Page successfully updated');
			break;
		case 'new':
		echo 'new';
			$db->bind('id',$record_id);
			$exists = $db->single('SELECT 1 FROM `pages` WHERE `id` = :id;');
			if($exists!=1){
				$db->query("INSERT INTO `pages` (`id`, `parent_id`, `name`, `meta_description`, `link`, `change_freq`, `priority`, `standalone`, `signin_required`, `timestamp`) VALUES (NULL, '1', 'Title', 'Description', 'example.html', 'weekly', '0.5', NULL, NULL, CURRENT_TIMESTAMP);");
				$db->bind('id',$db->lastInsertId());
				$db->query("INSERT INTO `page_permissions` (`id`, `page_id`, `state`) VALUES (NULL, :id, 'active');");

				$alert->add('success','Page successfully added');
			}
			break;
		case 'remove':
			$db->bind('id',$record_id);
			$db->query('DELETE FROM `pages` WHERE `id` = :id');
			$db->bind('id',$record_id);
			$db->query('DELETE FROM `page_permissions` WHERE `page_id` = :id;');

			$alert->add('success','Page successfully removed');
			break;
	}
}

$pages = $db->query('SELECT `pages`.`id`, `pages`.`parent_id`, `pages`.`name`, `pages`.`meta_description`, `pages`.`link`, `pages`.`priority`, `pages`.`change_freq`, `pages`.`standalone`, `pages`.`signin_required`, `page_permissions`.`state` FROM `pages` LEFT JOIN `page_permissions` ON `pages`.`id` = `page_permissions`.`page_id` ORDER BY `pages`.`name`;');

function print_menu($array, $level) {
	global $instance;
	global $pages;
	global $record_id;
	foreach($array as $key => $value) {
		if($record_id==$value['id']){
			//when clicked on highlighted
			echo '<div class="well well-sm" id="page'.$value['id'].'" style="margin: 0.2em 0 0.2em '.($level*2+1).'em;">';
			echo '<form id="edit" name="edit" method="post" action="'.$instance->href(NULL, $value['id']).'#page'.$value['id'].'" enctype="multipart/form-data">';

			echo '<div style="float:right">';
			echo '<a href="'.$instance->href($value['link']).'" style="margin-right: 1em"><span class="glyphicon glyphicon-link"></span></a>';
			echo '<a href="?q=#page'.$value['id'].'"><span class="glyphicon glyphicon glyphicon-minus"></span></a>';
			echo '</div>';


			echo '<h3>'.$value['name'].'</h3>';

			// title
			echo '<label for="title">Title</label>';
			echo '<input id="title" name="page[title]" class="form-control" type="text" value="'.$value['name'].'"/>';

			// url
			echo '<label for="url">URL</label>';
			echo '<input id="url" name="page[url]" class="form-control" type="text" value="'.$value['link'].'"/>';

			// parent page
			if($value['parent_id']==0){
				echo '<label for="parent_id">Parent Page</label>';
				echo '<select id="parent_id" name="page[parent_id]" class="form-control"><option value="0" selected>None</option></select>';
			} else {
				echo '<label for="parent_id">Parent Page</label>';
				echo '<select id="parent_id" name="page[parent_id]" class="form-control">';
				foreach($pages as $key2 => $value2){
					echo '<option value="'.$value2['id'].'"'.(($value2['id']==$value['parent_id'])?' selected':'').'>'.$value2['name'].'</option>';
				}
				echo '</select>';
			}
			// description
			echo '<label for="description">Description</label>';
			echo '<textarea id="description" name="page[description]" class="form-control">'.$value['meta_description'].'</textarea>';

			// standalone
			echo '<label for="standalone">Standalone</label>';
			echo '<select id="standalone" name="page[standalone]" class="form-control">';
			foreach(array('No','Yes') as $key2 => $value2){
				echo '<option value="'.$key2.'"'.(($value['standalone']==$key2)?' selected':'').'>'.$value2.'</option>';
			}
			echo '</select>';

			// status
			echo '<label for="priority">State</label>';
			echo '<select id="priority" name="page[state]" class="form-control">';
			foreach(array('disabled','active','protected') as $key2 => $value2){
				echo '<option'.(($value['state']==$value2)?' selected':'').'>'.$value2.'</option>';
			}
			echo '</select>';

			// update frequency
			echo '<label for="change_freq">Update Frequency</label>';
			echo '<select id="change_freq" name="page[change_freq]" class="form-control">';
			foreach(array('always','hourly','daily','weekly','monthly','yearly','never') as $key2 => $value2){
				echo '<option value="'.$value2.'"'.(($value2==$value['change_freq'])?' selected':'').'>'.$value2.'</option>';
			}
			echo '</select>';

			// site map priority
			echo '<label for="priority">Site Map Priority</label>';
			echo '<select id="priority" name="page[priority]" class="form-control">';
			for($i = 0; $i<1; $i += 0.1){
				// double quotes done to fix issue with values 0.8, 0.9, 1.0 -- from floats?
				echo '<option'.(("{$value['priority']}"=="{$i}")?' selected':'').'>'.$i.'</option>';
			}
			echo '</select>';

			echo '<br/>';
			echo '<button type="submit" name="submit" class="btn btn-primary" form="edit" value="save">Save</button> ';
			echo '<button type="submit" name="submit"  class="btn btn-danger" form="edit" value="remove">Remove</button>';
			echo '<div style="float:right">';
			echo '<button type="submit" name="submit"  class="btn btn-default" form="edit" value="cancel">Cancel</button>';
			echo '</div>';
			echo '</form>';
			echo '</div>';
		} else {
			echo '<div class="well well-sm" id="page'.$value['id'].'" style="background: #FFF; margin: 0.2em 0 0.2em '.($level*2+1).'em;">';
			echo $value['name'];
			echo '<div style="float:right">';
			echo '<a href="'.$instance->href($value['link']).'" style="margin-right: 1em"><span class="glyphicon glyphicon-link"></span></a>';
			echo '<a href="'.$instance->href(NULL, $value['id']).'#page'.$value['id'].'"><span class="glyphicon glyphicon-plus"></span></a>';
			echo '</div>';
			echo '</div>';
		}
		if(is_array($value)){
		    print_menu($value['children'], $level+1);
		}
  }
}

$alert->get();

echo '<div class="container background-white"/>';

$new_id = $db->single('SELECT MAX(`id`) FROM `pages`')+1;
echo '<form id="new" name="new" method="post" action="'.$instance->href(NULL, $new_id).'#page'.$new_id.'" enctype="multipart/form-data">';
echo '<p class="text-right">';
echo '<button type="submit" name="submit" class="btn btn-default" form="new" value="new" aria-label="Left Align"><span class="glyphicon glyphicon-file" aria-hidden="true"></span>New Page</button>';
echo '</p>';
echo '</form>';

print_menu(buildTree($pages),0);

echo '</div>';
?>
