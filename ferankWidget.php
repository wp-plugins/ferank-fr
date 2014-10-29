<?php

class ferank_Widget extends WP_Widget {
    
	public function __construct() {
		parent::__construct(
	 		'ferank_widget',
			'FERank',
			array( 'description' => __( 'Bloc d\'annonce FERank', 'text_domain' ), )
		);
	}
	
    public function widget( $args, $instance ) {
        if (get_option('ferank_id') != '') {
            extract( $args );
            $title = apply_filters( 'widget_title', $instance['title'] );
		
            echo $before_widget;
            if ( ! empty( $title ) ) {
                echo $before_title . $title . $after_title;
            }
        
            echo '<script type="text/javascript">
            var ferank_client = "'.get_option('ferank_id').'",
                ferank_taille = "'.$instance['taille'].'",
                ferank_couleur_titre = "'.substr(get_option('ferank_couleur_titre'), 1).'",
                ferank_couleur_texte = "'.substr(get_option('ferank_couleur_texte'), 1).'";
            </script>
            <script type="text/javascript" src="//static.ferank.fr/publicite.js"></script>';
        
            echo $after_widget;
        }
	}
    
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '';
		}
		if ( isset( $instance[ 'taille' ] ) ) {
			$taille = $instance[ 'taille' ];
		}
		else {
			$taille = '660x250';
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Titre (optionnel) :' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_name( 'taille' ); ?>"><?php _e( 'Format :' ); ?></label>
		<select class="widefat" id="<?php echo $this->get_field_id( 'taille' ); ?>" name="<?php echo $this->get_field_name( 'taille' ); ?>">
		<optgroup label="Nouveau !">
		<option value="660x250"<?php if($taille=='660x250'){echo ' selected';}?>>Responsive (largeur auto)</option>
		</optgroup>
		<optgroup label="Autre : horizontal">
		<option value="728x90"<?php if($taille=='728x90'){echo ' selected';}?>>Leaderboard : 728x90</option>
		<option value="970x90"<?php if($taille=='970x90'){echo ' selected';}?>>Grand leaderboard : 970x90</option>
		<option value="468x60"<?php if($taille=='468x60'){echo ' selected';}?>>Bannière : 468x60</option>
		</optgroup>
		<optgroup label="Autre : carré">
		<option value="300x250"<?php if($taille=='300x250'){echo ' selected';}?>>Rectangle moyen : 300x250</option>
		<option value="250x250"<?php if($taille=='250x250'){echo ' selected';}?>>Carré : 250x250</option>
		<option value="336x280"<?php if($taille=='336x280'){echo ' selected';}?>>Grand rectangle : 336x280</option>
		</optgroup>
		<optgroup label="Autre : verticaux">
		<option value="120x600"<?php if($taille=='120x600'){echo ' selected';}?>>Skyscraper 120x600</option>
		<option value="160x600"<?php if($taille=='160x600'){echo ' selected';}?>>Skyscraper large 160x600</option>
		<option value="300x600"<?php if($taille=='300x600'){echo ' selected';}?>>Skyscraper extra large 300x600</option>
		</optgroup>
		</select>
		</p>
		<?php 
	}
    
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['taille'] = ( !empty( $new_instance['taille'] ) ) ? strip_tags( $new_instance['taille'] ) : '';

		return $instance;
	}
}