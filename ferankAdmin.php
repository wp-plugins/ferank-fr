<?php

// Admin
if(!class_exists('ferank_Admin'))
{
	class ferank_Admin {
		
		// Variables
		var $hook = 'ferank';
		var $longname = 'FERank';
		var $shortname = 'FERank';
		var $filename = 'ferank-fr/ferank.php';
		var $homepage = 'https://www.ferank.fr/';
		
		// Ajout de la page des réglages et test config
		function ferank_Admin() {
            add_action('admin_menu', array(&$this, 'register_settings_page'));
			add_filter('plugin_action_links', array(&$this,'add_action_link'), 10, 2);
			add_action('admin_init', array(&$this,'ferank_register'));
			if(get_option('ferank_id') == ''){add_action('admin_notices', array(&$this,'ferank_admin_notices'));}
		}
		
		// Enregistrement des options
		function ferank_register() {
			register_setting( 'ferank', 'ferank_marker_type' );            
			register_setting( 'ferank', 'ferank_id' );
			register_setting( 'ferank', 'ferank_couleur_titre' );
			register_setting( 'ferank', 'ferank_couleur_texte' );
			register_setting( 'ferank', 'ferank_haut' );
			register_setting( 'ferank', 'ferank_bas' );
			register_setting( 'ferank', 'ferank_stylehaut' );
			register_setting( 'ferank', 'ferank_stylebas' );
		}
		
		// Ajout de la page des réglages
		function register_settings_page() {
			$hook_suffix = add_options_page($this->longname, $this->shortname, 'manage_options', $this->hook, array(&$this,'ferank_config_page'));
			add_action('load-' . $hook_suffix , array(&$this,'ferank_load_function'));
		}
		
		// Suppression de l'alerte
		function ferank_load_function() {
			remove_action('admin_notices', array(&$this,'ferank_admin_notices'));
			add_action('admin_enqueue_scripts', array(&$this,'ferank_color_picker'));
		}
		
		// Colorpicker
		function ferank_color_picker( $hook_suffix ) {
			wp_enqueue_style('wp-color-picker');
			wp_enqueue_script('ferank', plugins_url('colorpicker.js', __FILE__ ), array('wp-color-picker'), false, true);
		}
		
		// Alerte
		function ferank_admin_notices() {
			echo "<div id='notice' class='updated fade'><p>Un compte sur <a href='https://www.ferank.fr/' target='_blank'>FERank</a> est requis. L'<a href='" . $this->plugin_options_url() . "'>ID éditeur de votre compte n'est pas encore configuré</a>.</p></div>\n";
		}
		
		// Page des réglages
		function ferank_config_page() {
			?>
			<div class="wrap" style="float:left">
                <h1>FERank</h1>
				<form method="post" action="options.php">
				<?php
				settings_fields( 'ferank' );
				?>
                <h2 style="margin-bottom:20px">Mesure d'audience</h2>
                <div class="ferankDiv">
                <p>Le marqueur de mesure d'audience classique est totalement invisible, le marqueur avec opposition utilise le script <a href="https://opt-out.ferank.eu/fr/" target="_blank">tarteaucitron.js</a></p>
				<table class="form-table">
				<tr valign="top">
				<th scope="row">Type de marqueur</th>
				<td>
                    <select name="ferank_marker_type">
                        <option value="">---</option>
                        <option value="classique" <?php if (get_option('ferank_marker_type') == "classique"){ echo ' selected';} ?>>Classique (invisible)</option>
                        <option value="citron" <?php if (get_option('ferank_marker_type') == "citron"){ echo ' selected';} ?>>Avec opposition (tarteaucitron.js)</option>
                    </select>
                </td>
				</tr>
                </table>
                </div>
                <h2 style="margin:30px 0 20px">Régie publicitaire</h2>
				<h3>Options générales</h3>
                <div class="ferankDiv">
                <p>Le service éditeur de FERank n'est accessible qu'aux professionnels ayant un numéro SIREN valide. N'hésitez pas à <a href="https://www.ferank.fr/contact/" target="_blank">me contacter</a> pour mettre à jour votre profil si besoin.</p>
				<table class="form-table">
				<tr valign="top">
				<th scope="row">Votre ID éditeur (<a href="https://www.ferank.fr/client/securite/" target="_blank">récuperer mon ID</a>)</th>
				<td><input type="text" name="ferank_id" value="<?php echo get_option('ferank_id'); ?>" /></td>
				</tr>
				<tr valign="top">
				<th scope="row">Couleur du titre des annonces</th>
				<td><input type="text" class="ferank_cp" data-default-color="#1b9fd7" name="ferank_couleur_titre" value="<?php echo get_option('ferank_couleur_titre'); ?>" /></td>
				</tr>
				<tr valign="top">
				<th scope="row">Couleur du texte des annonces</th>
				<td><input type="text" class="ferank_cp2" data-default-color="#333333" name="ferank_couleur_texte" value="<?php echo get_option('ferank_couleur_texte'); ?>" /></td>
				</tr>
				</table>
                </div>
				<h3>Annonces dans les articles</h3>
                <div class="ferankDiv">
                <p>Cette option vous permet d'afficher automatiquement un bloc d'annonce <a href="https://www.ferank.fr/responsive/" target="_blank">responsive</a> au début et/ou à la fin de vos articles.</p>
				<table class="form-table">
				<tr valign="top">
				<th scope="row">Affichage au dessus des articles ?</th>
				<td><input type="checkbox" name="ferank_haut" <?php if(get_option('ferank_haut')=='on'){echo 'checked';} ?> /></td>
				</tr>
				<tr valign="top">
				<th scope="row">CSS pour le bloc au dessus des articles</th>
				<td><textarea name="ferank_stylehaut"><?php echo get_option('ferank_stylehaut'); ?></textarea></td>
				</tr>
				<tr valign="top">
				<th scope="row">Affichage en dessous des articles ?</th>
				<td><input type="checkbox" name="ferank_bas" <?php if(get_option('ferank_bas')=='on'){echo 'checked';} ?> /></td>
				</tr>
				<tr valign="top">
				<th scope="row">CSS pour le bloc en dessous des articles</th>
				<td><textarea name="ferank_stylebas"><?php echo get_option('ferank_stylebas'); ?></textarea></td>
				</tr>
				</table>
                </div>
				<?php submit_button(); ?>
				</form>
			</div>
            <div style="float:left;padding:10px;margin-top:72px;width:250px;margin-left:30px;background:#fff;border:1px solid #eee;border-bottom:2px solid #ddd;">
                <h4>Widgets</h4>
                <p>Ajoutez des blocs d'annonces publicitaires via les widgets de votre thème.</p>
                <h4>Shortcodes</h4>
                <p>Ajoutez des blocs d'annonces publicitaires directement depuis vos articles ou votre thème en insérant un shortcode :</p>
                <p style="font-family:courrier">
                    [ferank taille=660x250] <-- responsive design<br/>
                    [ferank taille=336x280]<br/>
                    [ferank taille=300x250]<br/>
                    [ferank taille=250x250]<br/>
                    [ferank taille=300x600]<br/>
                    [ferank taille=160x600]<br/>
                    [ferank taille=120x600]<br/>
                    [ferank taille=970x90]<br/>
                    [ferank taille=728x90]<br/>
                    [ferank taille=468x60]
                </p>
            </div>
            <div style="clear:both"></div>
            <style type="text/css">.ferankDiv{background:#FFF;padding: 10px;border: 1px solid #eee;border-bottom: 2px solid #ddd;max-width: 500px;}</style>
			<?php		
		}
		
		// Liens vers les réglages
		function plugin_options_url() {
			return admin_url('options-general.php?page='.$this->hook);
		}
		
		// Liens vers les réglages depuis la page des extensions
		function add_action_link( $links, $file ) {
			static $this_plugin;
			if( empty($this_plugin) ) $this_plugin = $this->filename;
			if ( $file == $this_plugin ) {
				$settings_link = '<a href="' . $this->plugin_options_url() . '">' . __('Réglages') . '</a>';
				array_unshift( $links, $settings_link );
			}
			return $links;
		}
	}
    
	$ferank_admin = new ferank_Admin();
}