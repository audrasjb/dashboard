<?php
/**
 * @since      1.0
 *
 * @package    whodunit-dashboard
 * @subpackage whodunit-dashboard/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    whodunit-dashboard
 * @subpackage whodunit-dashboard/admin
 * @author     audrasjb <audrasjb@gmail.com>
 */

/**
 *
 * Enqueue styles and scripts
 *
 */
	add_action( 'admin_enqueue_scripts', 'who_enqueue_styles_admin' );
	function who_enqueue_styles_admin() {
		wp_enqueue_style( 'whodunit-dashboard-admin-styles', plugin_dir_url( __FILE__ ) . 'css/whodunit-dashboard-admin.css', array(), '', 'all' );
	}
	add_action( 'admin_enqueue_scripts', 'who_enqueue_scripts_admin' );
	function who_enqueue_scripts_admin() {
		wp_enqueue_script( 'whodunit-dashboard-admin-scripts', plugin_dir_url( __FILE__ ) . 'js/whodunit-dashboard-admin.js', array( 'jquery' ), '', true );
	}	

/**
 *
 * Remove useless stuff
 *
 */
 	function who_disable_default_dashboard_widgets() {
		remove_meta_box('dashboard_right_now', 'dashboard', 'core');
		remove_meta_box('dashboard_activity', 'dashboard', 'core');
		remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
		remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
		remove_meta_box('dashboard_plugins', 'dashboard', 'core');

		remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
		remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
		remove_meta_box('dashboard_primary', 'dashboard', 'core');
		remove_meta_box('dashboard_secondary', 'dashboard', 'core');
	}
	add_action('admin_menu', 'who_disable_default_dashboard_widgets');

/**
 *
 * Add main widget
 *
 */
 	add_action( 'admin_footer', 'who_main_dashboard_widget' );
 	function who_main_dashboard_widget() {
		// Kickout this if not viewing the main dashboard page
		if ( get_current_screen()->base !== 'dashboard' ) {
			return;
		}
		?>
		<div id="who_main_dashboard_widget" class="welcome-panel">
			<div class="welcome-panel-content">
				<div class="welcome-panel-column-container">
					<h2>Bienvenue sur votre tableau de bord</h2>
					<p class="about-description">Conçu et réalisé par Whodunit, votre agence WordPress !</p>
					<div class="welcome-panel-column">
						<h3>Guides d’utilisation</h3>
						<?php $options = get_option( 'whoadmin_options' ); ?>
						<ul>
						<?php if (isset($options['title_guide1']) && !empty($options['title_guide1']) && isset($options['url_guide1']) && !empty($options['url_guide1'])) : ?>
							<li>
								<a target="_blank" href="<?php echo $options['url_guide1']; ?>" class="welcome-icon welcome-learn-more" title="Télécharger le document">
									<?php echo $options['title_guide1']; ?>
								</a>
							</li>
						<?php endif; ?>
						<?php if (isset($options['title_guide2']) && !empty($options['title_guide2']) && isset($options['url_guide2']) && !empty($options['url_guide2'])) : ?>
							<li>
								<a target="_blank" href="<?php echo $options['url_guide2']; ?>" class="welcome-icon welcome-learn-more" title="Télécharger le document">
									<?php echo $options['title_guide2']; ?>
								</a>
							</li>
						<?php endif; ?>
						<?php if (isset($options['title_guide3']) && !empty($options['title_guide3']) && isset($options['url_guide3']) && !empty($options['url_guide3'])) : ?>
							<li>
								<a target="_blank" href="<?php echo $options['url_guide3']; ?>" class="welcome-icon welcome-learn-more" title="Télécharger le document">
									<?php echo $options['title_guide3']; ?>
								</a>
							</li>
						<?php endif; ?>
						<?php if (isset($options['title_guide4']) && !empty($options['title_guide4']) && isset($options['url_guide4']) && !empty($options['url_guide4'])) : ?>
							<li>
								<a target="_blank" href="<?php echo $options['url_guide4']; ?>" class="welcome-icon welcome-learn-more" title="Télécharger le document">
									<?php echo $options['title_guide4']; ?>
								</a>
							</li>
						<?php endif; ?>
						<?php if (isset($options['title_guide5']) && !empty($options['title_guide5']) && isset($options['url_guide5']) && !empty($options['url_guide5'])) : ?>
							<li>
								<a target="_blank" href="<?php echo $options['url_guide5']; ?>" class="welcome-icon welcome-learn-more" title="Télécharger le document">
									<?php echo $options['title_guide5']; ?>
								</a>
							</li>
						<?php endif; ?>
						</ul>
					</div>
					<div class="welcome-panel-column welcome-panel-last">
						<h3>Support</h3>
						<ul>
							<li><a target="_blank" href="http://ticket-wdt.fr/" class="welcome-icon dashicons-admin-tools">Outil de tickets Whodunit</a></li>
							<li><a target="_blank" href="mailto:support@whodunit.fr" class="welcome-icon dashicons-admin-users">Support : support@whodunit.fr</a></li>
						</ul>
					</div>
					<div class="welcome-panel-column whodunit_logo">
						<a href="http://whodunit.fr" target="_blank">
							<img src="http://preprod-site.fr/assets/logo_WDT.png" alt="Whodunit" />
						</a>
					</div>
				
						<div class="changelog">
			<h3>Contenus en attente de publication</h3>
			<div class="feature-section images-stagger-right">
			<?php
			$drafts_query = new WP_Query( array(
				'post_type' => 'any',
				'post_status' => array('draft', 'pending', 'auto-draft', 'future'),
				'posts_per_page' => -1,
				'orderby' => 'modified',
				'order' => 'DESC'
			) );
			$drafts =& $drafts_query->posts;
			if ( $drafts && is_array( $drafts ) ) {
				$list = array();		
				foreach ( $drafts as $draft ) {
					$url = get_edit_post_link( $draft->ID );
					$title = _draft_or_post_title( $draft->ID );
					$last_id = get_post_meta( $draft->ID, '_edit_last', true);
					$last_user = get_userdata($last_id);
					$obj = get_post_type_object(get_post_type($draft->ID));
					$postType =  $obj->labels->singular_name;
					if (get_post_status($draft->ID) == 'draft') {
						$post_status = 'Brouillon';
					} elseif (get_post_status($draft->ID) == 'pending') {
						$post_status = 'En attente de relecture';
					} elseif (get_post_status($draft->ID) == 'future') {
						$post_status = 'Planifié pour le ' . get_the_date(get_option('date_format'), $draft->ID);
					} elseif (get_post_status($draft->ID) == 'auto-draft') {
						$post_status = 'Brouillon automatique';
					}
					$last_modified = get_the_modified_date();
					$item = '<tr>';
					$item .= '<td><a href="' . $url . '" title="' . sprintf( __( 'Modifier ce contenu' ), esc_attr( $title ) ) . '">' . esc_html($title) . '</a></td>';
					$item .= '<td>' . $post_status . '</td>';
					$item .= '<td>' . $postType . '</td>';
					if ($last_user) {
						$item .= '<td>' . $last_user->display_name . '</td>';
					} else {
						$item .= '<td>Aucun</td>';
					}
					$item .= '<td>' . sprintf(__('Le %2$s à %3$s'), $last_modified, mysql2date(get_option('date_format'), $draft->post_modified), mysql2date(get_option('time_format'), $draft->post_modified)) . '</td>';
					$item .= '</tr>';
					$list[] = $item;
				}
				?>
				<table class="widefat">
					<thead>
    					<tr>
     					    <th>Titre / lien</th>
        					<th>Statut</th>       
        					<th>Type</th>       
        					<th>Auteur</th>       
        					<th>Dernière modification</th>
    					</tr>
					</thead>
					<tbody>
						<?php echo join( "\n", $list ); ?>
					</tbody>
				</table>
			<?php
			} else {
				echo 'Il n\'y a pas de brouillons enregistrés actuellement.';
			}
			?>
		</div>

				</div>
			</div>
		</div>
		<?php 
	}
	
/**
 *
 * Admin option page
 *
 */
add_action( 'admin_menu', 'whoadmin_add_admin_menu' );
add_action( 'admin_init', 'whoadmin_settings_init' );


function whoadmin_add_admin_menu(  ) { 
	add_options_page('Réglages du tableau de bord', 'Whodunit dashboard', 'manage_options', 'whoadmin_options', 'whoadmin_options_page' );
}


function whoadmin_settings_init(  ) { 

	register_setting( 'whoadminPage', 'whoadmin_options' );

	add_settings_section(
		'whoadmin_options_page_pluginPage_section', 
		'Réglages du tableau de bord', 
		'whoadmin_options_page_settings_section_callback', 
		'whoadminPage'
	);

	add_settings_field( 
		'whoadmin_field_guide1_title', 
		'Titre guide d’utilisation 1', 
		'whoadmin_options_page_field_guide1_title_render', 
		'whoadminPage', 
		'whoadmin_options_page_pluginPage_section' 
	);
	add_settings_field( 
		'whoadmin_field_guide1_url', 
		'URL guide d’utilisation 1', 
		'whoadmin_options_page_field_guide1_url_render', 
		'whoadminPage', 
		'whoadmin_options_page_pluginPage_section' 
	);

	add_settings_field( 
		'whoadmin_field_guide2_title', 
		'Titre guide d’utilisation 2', 
		'whoadmin_options_page_field_guide2_title_render', 
		'whoadminPage', 
		'whoadmin_options_page_pluginPage_section' 
	);
	add_settings_field( 
		'whoadmin_field_guide2_url', 
		'URL guide d’utilisation 2', 
		'whoadmin_options_page_field_guide2_url_render', 
		'whoadminPage', 
		'whoadmin_options_page_pluginPage_section' 
	);

	add_settings_field( 
		'whoadmin_field_guide3_title', 
		'Titre guide d’utilisation 3', 
		'whoadmin_options_page_field_guide3_title_render', 
		'whoadminPage', 
		'whoadmin_options_page_pluginPage_section' 
	);
	add_settings_field( 
		'whoadmin_field_guide3_url', 
		'URL guide d’utilisation 3', 
		'whoadmin_options_page_field_guide3_url_render', 
		'whoadminPage', 
		'whoadmin_options_page_pluginPage_section' 
	);

	add_settings_field( 
		'whoadmin_field_guide4_title', 
		'Titre guide d’utilisation 4', 
		'whoadmin_options_page_field_guide4_title_render', 
		'whoadminPage', 
		'whoadmin_options_page_pluginPage_section' 
	);
	add_settings_field( 
		'whoadmin_field_guide4_url', 
		'URL guide d’utilisation 4', 
		'whoadmin_options_page_field_guide4_url_render', 
		'whoadminPage', 
		'whoadmin_options_page_pluginPage_section' 
	);

	add_settings_field( 
		'whoadmin_field_guide5_title', 
		'Titre guide d’utilisation 5', 
		'whoadmin_options_page_field_guide5_title_render', 
		'whoadminPage', 
		'whoadmin_options_page_pluginPage_section' 
	);
	add_settings_field( 
		'whoadmin_field_guide5_url', 
		'URL guide d’utilisation 5', 
		'whoadmin_options_page_field_guide5_url_render', 
		'whoadminPage', 
		'whoadmin_options_page_pluginPage_section' 
	);
}

// Guide 1
function whoadmin_options_page_field_guide1_title_render(  ) { 
	$options = get_option( 'whoadmin_options' );
	if (isset($options['title_guide1'])) {
		$optionTitleGuide1 = $options['title_guide1'];
	} else {
		$optionTitleGuide1 = '';		
	}
	?>
	<input type="text" name="whoadmin_options[title_guide1]" size="50" value="<?php echo $optionTitleGuide1; ?>">
	<?php
}
function whoadmin_options_page_field_guide1_url_render(  ) { 
	$options = get_option( 'whoadmin_options' );
	if (isset($options['url_guide1'])) {
		$optionUrlGuide1 = $options['url_guide1'];
	} else {
		$optionUrlGuide1 = '';		
	}
	?>
	<input type="text" name="whoadmin_options[url_guide1]" size="100" value="<?php echo $optionUrlGuide1; ?>">
	<?php
}

// Guide 2
function whoadmin_options_page_field_guide2_title_render(  ) { 
	$options = get_option( 'whoadmin_options' );
	if (isset($options['title_guide2'])) {
		$optionTitleGuide2 = $options['title_guide2'];
	} else {
		$optionTitleGuide2 = '';		
	}
	?>
	<input type="text" name="whoadmin_options[title_guide2]" size="50" value="<?php echo $optionTitleGuide2; ?>">
	<?php
}
function whoadmin_options_page_field_guide2_url_render(  ) { 
	$options = get_option( 'whoadmin_options' );
	if (isset($options['url_guide2'])) {
		$optionUrlGuide2 = $options['url_guide2'];
	} else {
		$optionUrlGuide2 = '';		
	}
	?>
	<input type="text" name="whoadmin_options[url_guide2]" size="100" value="<?php echo $optionUrlGuide2; ?>">
	<?php
}

// Guide 3
function whoadmin_options_page_field_guide3_title_render(  ) { 
	$options = get_option( 'whoadmin_options' );
	if (isset($options['title_guide3'])) {
		$optionTitleGuide3 = $options['title_guide3'];
	} else {
		$optionTitleGuide3 = '';		
	}
	?>
	<input type="text" name="whoadmin_options[title_guide3]" size="50" value="<?php echo $optionTitleGuide3; ?>">
	<?php
}
function whoadmin_options_page_field_guide3_url_render(  ) { 
	$options = get_option( 'whoadmin_options' );
	if (isset($options['url_guide3'])) {
		$optionUrlGuide3 = $options['url_guide3'];
	} else {
		$optionUrlGuide3 = '';		
	}
	?>
	<input type="text" name="whoadmin_options[url_guide3]" size="100" value="<?php echo $optionUrlGuide3; ?>">
	<?php
}

// Guide 4
function whoadmin_options_page_field_guide4_title_render(  ) { 
	$options = get_option( 'whoadmin_options' );
	if (isset($options['title_guide4'])) {
		$optionTitleGuide4 = $options['title_guide4'];
	} else {
		$optionTitleGuide4 = '';		
	}
	?>
	<input type="text" name="whoadmin_options[title_guide4]" size="50" value="<?php echo $optionTitleGuide4; ?>">
	<?php
}
function whoadmin_options_page_field_guide4_url_render(  ) { 
	$options = get_option( 'whoadmin_options' );
	if (isset($options['url_guide4'])) {
		$optionUrlGuide4 = $options['url_guide4'];
	} else {
		$optionUrlGuide4 = '';		
	}
	?>
	<input type="text" name="whoadmin_options[url_guide4]" size="100" value="<?php echo $optionUrlGuide4; ?>">
	<?php
}

// Guide 5
function whoadmin_options_page_field_guide5_title_render(  ) { 
	$options = get_option( 'whoadmin_options' );
	if (isset($options['title_guide5'])) {
		$optionTitleGuide5 = $options['title_guide5'];
	} else {
		$optionTitleGuide5 = '';		
	}
	?>
	<input type="text" name="whoadmin_options[title_guide5]" size="50" value="<?php echo $optionTitleGuide5; ?>">
	<?php
}
function whoadmin_options_page_field_guide5_url_render(  ) { 
	$options = get_option( 'whoadmin_options' );
	if (isset($options['url_guide5'])) {
		$optionUrlGuide5 = $options['url_guide5'];
	} else {
		$optionUrlGuide5 = '';		
	}
	?>
	<input type="text" name="whoadmin_options[url_guide5]" size="100" value="<?php echo $optionUrlGuide5; ?>">
	<?php
}

function whoadmin_options_page_settings_section_callback(  ) { 
	echo 'Modifier les réglages du tableau de bord Whodunit.';
}

function whoadmin_options_page(  ) { 
	?>
	<form action='options.php' method='post'>

		<?php
		settings_fields( 'whoadminPage' );
		do_settings_sections( 'whoadminPage' );
		submit_button();
		?>

	</form>
	<?php
}
