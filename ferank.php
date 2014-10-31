<?php
/*
Plugin Name: FERank
Plugin URI: https://www.ferank.fr/
Description: Intégration des services FERank à votre blog
Version: 1.0.2
Author: Amauri CHAMPEAUX
Author URI: http://frnk.fr/a-propos
*/

require('ferankAdmin.php');

// Affichage d'un bloc responsive dans l'article
function ferank_ads_single($content) {
    if (get_option('ferank_id') == '') return $content;

	$haut = '';
	$bas = '';
	$custom = '<script type="text/javascript">
    var ferank_client = "'.get_option('ferank_id').'",
        ferank_taille = "660x250",
        ferank_couleur_titre = "'.substr(get_option('ferank_couleur_titre'), 1).'",
        ferank_couleur_texte = "'.substr(get_option('ferank_couleur_texte'), 1).'";
    </script>
    <script type="text/javascript" src="//static.ferank.fr/publicite.js"></script>';
	
	if(get_option('ferank_haut') == 'on' && is_single()) {
		$haut = '<div style="'.get_option('ferank_stylehaut').'">'.$custom.'</div>';
	}
	if(get_option('ferank_bas') == 'on' && is_single()) {
		$bas = '<div style="'.get_option('ferank_stylebas').'">'.$custom.'</div>';
	}
	return $haut.$content.$bas;
}

// Shortcode
function ferank_shortcode( $atts ) {
    if (get_option('ferank_id') == '') return '';

	extract( shortcode_atts( array(
		'taille' => '660x250',
		'couleur_titre' => substr(get_option('ferank_couleur_titre'), 1),
		'couleur_texte' => substr(get_option('ferank_couleur_texte'), 1)
	), $atts ) );
	
	return '<script type="text/javascript">
    var ferank_client = "'.get_option('ferank_id').'",
        ferank_taille = "'.$taille.'",
        ferank_couleur_titre = "'.$couleur_titre.'",
        ferank_couleur_texte = "'.$couleur_texte.'";
    </script>
    <script type="text/javascript" src="//static.ferank.fr/publicite.js"></script>';
}

// Injection du marqueur de mesure d'audience
function ferank_script() {	
    if (get_option('ferank_marker_type') == "citron") {
        echo "<script type=\"text/javascript\" src=\"//opt-out.ferank.eu/tarteaucitron.js\"></script>
        <script type=\"text/javascript\">
        tarteaucitron.job = ['ferank'];
        tarteaucitron.init();
        </script>";
    } elseif (get_option('ferank_marker_type') == "classique") {
        echo "<script type=\"text/javascript\">
        (function() {
            var ferank = document.createElement('script');
            ferank.type = 'text/javascript';
            ferank.async = true;
            ferank.src = ('https:' == document.location.protocol ? 'https://static' : 'http://static') + '.ferank.fr/pixel.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ferank, s);
        })();
        </script>";
    }
}

require_once('ferankWidget.php');

add_shortcode('ferank', 'ferank_shortcode');
add_filter('the_content', 'ferank_ads_single');
add_action('widgets_init', function() { register_widget( 'ferank_Widget' ); });
add_action('wp_head', 'ferank_script');
?>