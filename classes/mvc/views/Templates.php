<?php

namespace VideoAnchors;


use wpdb;

class Templates {

	private static $courses = [
		1 => 'Php',
		2 => 'C#',
		3 => 'C++'
	];

	public static function Admin_RegisterVideos() {
		require_once ABSPATH . 'wp-settings.php';
		global $wpdb;
		$http_method = $_SERVER['REQUEST_METHOD'];

		$controller = new VideoController($wpdb);

		if ( $http_method === 'POST' && isset( $_POST['action'] ) ) {
			if ( $_POST['action'] === 'add' ) {
				$controller->post();
			} elseif ( $_POST['action'] === 'Modifier' ) {
				$controller->update();
			} elseif ( $_POST['action'] === 'Supprimer' ) {
				$controller->delete();
			}
		}

		echo '<h2>Ajouter une vidéo à un cours</h2>
<input type="hidden" id="page" value="videos" />';
		echo self::Table('videos', $controller->get() );
		echo self::Admin_RegisterVideos_JS();
	}

	public static function Admin_RegisterTimeAnchors() {
		require_once ABSPATH . 'wp-settings.php';
		global $wpdb;
		$http_method = $_SERVER['REQUEST_METHOD'];

		$anchors_controller = new AnchorController($wpdb);
		$videos_controller = new VideoController($wpdb);

		if ( $http_method === 'POST' && isset( $_POST['action'] ) ) {
			if ( $_POST['action'] === 'add' ) {
				$anchors_controller->post();
			} elseif ( $_POST['action'] === 'Modifier' ) {
				$anchors_controller->update();
			} elseif ( $_POST['action'] === 'Supprimer' ) {
				$anchors_controller->delete();
			}
		}

		$times_for_video = $anchors_controller->get();

		$times = self::Table('times', $times_for_video);

		$video_selected = $_GET['video_id'];
		$videos = '';
		foreach ( $videos_controller->get() as $video ) {
			if((int)$video->id === (int)$video_selected) {
				$input = '<input type="radio" name="video" value="'.$video->id.'" style="position: absolute; margin-top: 70px;" checked />';
			}
			else {
				$input = '<input type="radio" name="video" value="'.$video->id.'" style="position: absolute; margin-top: 70px;" />';
			}
			$videos .= $input.'<iframe style="margin-left: 30px;" width="200" height="150" src="'.$video->video.'"></iframe><br>';
		}

		echo '<h2>Ajouter une ancre à une vidéo</h2>
<input type="hidden" id="page" value="anchors" />';
		echo '<div class="row">
	<div style="display: inline-block; width: 20%; min-height: 50px; position:absolute;">
		'.$videos.'
	</div>
	<div class="col-s8" style="display: inline-block; width: 70%; min-height: 50px; position:absolute; margin-left: 20%;">
		'.$times.'
	</div>
</div>';

		echo self::Admin_RegisterVideos_JS();
	}

	public static function Admin_RegisterVideos_JS() {
		return "
<script src='https://code.jquery.com/jquery-3.3.1.js'
		integrity=\"sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=\"
		crossorigin=\"anonymous\"></script>
<script>
	window.onload = () => {
	    let page = document.querySelector('#page').value;
	    document.querySelectorAll('tbody tr').forEach(elem => {
	        let btns = elem.childNodes[6].childNodes;
	        let update_btn = btns[0];
	        let delete_btn = btns[1];
	        
	        update_btn.addEventListener('click', elem => {
	            if(page === 'videos') {
		            let video_id = elem.querySelector('input[type=\"hidden\"]');
		            let video_url = elem.querySelector('input[type=\"text\"]');
		            let course = elem.querySelector('select');
		            
		            $.ajax({
		                url: '',
		                method: 'post',
		                data: {
		                    action: 'Modifier',
		                    video_url: video_url.value,
		                    video_id: video_id.value,
		                    associated_course: course.value
		                }
		            });
	            }
	            else {
	                let id_video = '".$_GET['video_id']."';
	                let id_anchor = elem.target.parentElement.parentElement.querySelector('input[type=\"hidden\"]');
	                let anchor_time = elem.target.parentElement.parentElement.querySelector('input[type=\"time\"]');
	                let anchor_title = elem.target.parentElement.parentElement.querySelector('input[type=\"text\"]');
	                
	                $.ajax({
		                url: '',
		                method: 'post',
		                data: {
		                    action: 'Modifier',
		                    anchor_id: id_anchor.value,
		                    video_id: id_video,
		                    anchor_time: anchor_time.value,
		                    anchor_title: anchor_title.value
		                }
		            });
	            }
	        });
	        
	        delete_btn.addEventListener('click', elem => {
	            if(page === 'videos') {
		            let video_id = elem.querySelector('input[type=\"hidden\"]');
		            
		            $.ajax({
		                url: '',
		                method: 'post',
		                data: {
		                    action: 'Supprimer',
		                    video_id: video_id.value,
		                }
		            }).done(() => window.location.reload(true));
		        }
		        else {
	                let id_video = '".$_GET['video_id']."';
	                let id_anchor = elem.target.parentElement.parentElement.querySelector('input[type=\"hidden\"]');
	                
	                $.ajax({
		                url: '',
		                method: 'post',
		                data: {
		                    action: 'Supprimer',
		                    video_id: id_video,
		                    anchor_id: id_anchor.value
		                }
		            }).done(() => window.location.reload(true));
		        }
	        });
	    });
	    
	    if(page === 'videos') {
		    document.querySelector('#new_video input[value=\"Ajouter\"]').addEventListener('click', () => {
		        let new_associated_course = document.querySelector('#new_video select');
		        let new_video_url = document.querySelector('#new_video input[type=\"text\"]');
		        
		        $.ajax({
		            url: '',
		            method: 'post',
		            data: {
		                action: 'add',
		                video_url: new_video_url.value,
		                associated_course: new_associated_course.value
		            }
		        }).done(() => {
		            window.location.reload(true);
		        });
		    });
		}
	    
	    if(page === 'anchors') {
		    // changement de page video
			document.querySelectorAll(\"input[type='radio']\").forEach(elem => {
			    elem.addEventListener(\"click\", () => {
			        let id_video = elem.value;
			        window.location.href = window.location.href + \"&video_id=\" + id_video;
			    });
			});
			
			document.querySelector('#new_anchor input[value=\"Ajouter\"]').addEventListener('click', () => {
		        let new_anchor_time = document.querySelector(\"#new_anchor input[name='anchor_time']\");
		        let new_anchor_title = document.querySelector('#new_anchor input[type=\"text\"]');
		        let video_id = ".$_GET['video_id'] . ";
		        
		        $.ajax({
		            url: '',
		            method: 'post',
		            data: {
		                action: 'add',
		                video_id: video_id,
		                anchor_time: new_anchor_time.value,
		                anchor_title: new_anchor_title.value
		            }
		        }).done(() => {
		            window.location.reload(true);
		        });
		    });
			
			// TODO terminer le controlleur pour la partie ancres puis attaquer la partie client.
		}
	};
</script>";
	}

	public static function Table( $type, $table, $add = true, $update = true, $delete = true ) {
		if($type === 'videos') {
			$table_str = '<table>
		<thead>
			<tr>
				<th>Id de vidéo</th>
				<th>Cours</th>
				<th>Url de vidéo</th>';
			if ( $update || $delete || $add ) {
				$table_str .= '<th>Buttons</th>';
			}
			$table_str .= '</tr>
		</thead>
		<tbody>';
			foreach ( $table as $line ) {
				$line      = array_values( (array) $line );
				$table_str .= '<tr id="video_' . $line[0] . '">
	<td>
		' . $line[0] . '
	</td>
	<td>
		<input type="hidden" name="video_id" value="' . $line[0] . '" />
		' . self::get_courses( 'associated_course', $line[1] ) . '
	</td>
	<td>
		<input type="text" name="video_url" value="' . $line[2] . '" placeholder="Url de vidéo" />
	</td>';
				if ( $update || $delete ) {
					$table_str .= '<td>';
					if ( $update ) {
						$table_str .= '<input type="submit" name="action" value="Modifier" />';
					}
					if ( $delete ) {
						$table_str .= '<input type="submit" name="action" value="Supprimer" />';
					}
					$table_str .= '</td>';
				}
				$table_str .= '</tr>';
			}
			$table_str .= '</tbody>';
			if ( $add ) {
				$table_str .= '<tfoot>
		<tr id="new_video">
			<td></td>
			<td>
				<input type="hidden" name="action" value="add" />
				' . self::get_courses( 'associated_course' ) . '
			</td>
			<td>
				<input type="text" name="video_url" placeholder="url de la vidéo" />
			</td>
			<td>
				<input type="submit" value="Ajouter" />
			</td>
		</tr>
	</tfoot>';
			}
			$table_str .= '</table>';
		}
		else {
			$table_str = '<table>
		<thead>
			<tr>
				<th>Id de l\'ancre</th>
				<th>Temps de l\'ancre</th>
				<th>Titre</th>';
			if ( $update || $delete || $add ) {
				$table_str .= '<th>Buttons</th>';
			}
			$table_str .= '</tr>
		</thead>
		<tbody>';
			foreach ( $table as $line ) {
				$h = $line->time_h;
				$i = $line->time_i;
				$s = $line->time_s;

				if(strlen($h) === 1) {
					$h = '0'.$h;
				}
				if(strlen($i) === 1) {
					$i = '0'.$i;
				}
				if(strlen($s) === 1) {
					$s = '0'.$s;
				}

				$table_str .= '<tr id="anchor_' . $line->id . '">
	<td>
		<input type="hidden" name="anchor_id" value="' . $line->id . '" />
		' . $line->id . '
	</td>
	<td>
		<input type="time" step="2" value="'.$h.':'.$i.':'.$s.'" name="anchor_time" />
	</td>
	<td>
		<input type="text" name="anchor_title" value="' . $line->title . '" placeholder="Titre" />
	</td>';
				if ( $update || $delete ) {
					$table_str .= '<td>';
					if ( $update ) {
						$table_str .= '<input type="submit" name="action" value="Modifier" />';
					}
					if ( $delete ) {
						$table_str .= '<input type="submit" name="action" value="Supprimer" />';
					}
					$table_str .= '</td>';
				}
				$table_str .= '</tr>';
			}
			$table_str .= '</tbody>';
			if ( $add ) {
				$table_str .= '<tfoot>
		<tr id="new_anchor">
			<td>
				<input type="hidden" name="action" value="add" />
			</td>
			<td>
				<input type="time" step="2" name="anchor_time" placeholder="Temps de l\'ancre" />
			</td>
			<td>
				<input type="text" name="anchor_title" placeholder="Titre de l\'anchre" />
			</td>
			<td>
				<input type="submit" value="Ajouter" />
			</td>
		</tr>
	</tfoot>';
			}
			$table_str .= '</table>';
		}

		return $table_str;
	}

	public static function get_courses( $name, $selected_course = false ) {
		if($selected_course) {
			$selected_course = (int)$selected_course;
		}
		$select  = "<select name='${$name}'>";
		foreach (self::$courses as $id => $course) {
			$selected = !is_bool($selected_course) && $id === $selected_course;
			$select   .= '<option value="' . $id . '" ' . ( $selected ? 'selected' : '' ) . '>' . $course . '</option>';
		}
		$select .= '</select>';

		return $select;
	}
}