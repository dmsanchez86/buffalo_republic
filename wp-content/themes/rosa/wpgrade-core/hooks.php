<?php

$ds = DIRECTORY_SEPARATOR;

#
# This file assigns environment hooks.
#

// Include all theme specific classes and functions
// ------------------------------------------------------------------------

$themeincludepaths = wpgrade::confoption( 'include-paths', array() );

foreach ( $themeincludepaths as $path ) {
	$fullpath = wpgrade::themepath() . $ds . $path;
	if ( file_exists( $fullpath ) ) {
		wpgrade::require_all( $fullpath );
	}
}

$themeincludefiles = wpgrade::confoption( 'include-files', array() );
foreach ( $themeincludefiles as $file ) {

	if ( file_exists( wpgrade::childpath() . $file ) ) {
		require wpgrade::childpath() . $file;
	} else {
		require wpgrade::themepath() . $file;
	}
}


// Include core specific callbacks
// ------------------------------------------------------------------------

$callbackspath = dirname( __FILE__ ) . $ds . 'callbacks';
wpgrade::require_all( $callbackspath );


// Theme Setup
// ------------------------------------------------------------------------

/**
 * ...
 */
function wpgrade_callback_themesetup() {

	// General Purpose Resource Handling
	// ---------------------------------

	// register resources
	add_action( 'wp_enqueue_scripts', 'wpgrade_callback_register_theme_resources', 1 );

	// auto-enque based on configuration entries and callbacks
	add_action( 'wp_enqueue_scripts', 'wpgrade_callback_enqueue_theme_resources', 1 );

	$themeconfiguration = wpgrade::config();

	// Specialized Resource Handling
	// -----------------------------

	// extra script equeue handlers
	foreach ( $themeconfiguration['resources']['script-enqueue-handlers'] as $callback ) {
		if ( $callback !== null ) {
			if ( ! is_array( $callback ) ) {
				add_action( 'wp_enqueue_scripts', $callback, 10 );
			} else { // $callback is array
				if ( ! empty( $callback['handler'] ) ) {
					isset( $callback['priority'] ) or $callback['priority'] = 10;
					add_action( 'wp_enqueue_scripts', $callback['handler'], $callback['priority'] );
				}
			}
		}
	}

	// extra style equeue handlers
	foreach ( $themeconfiguration['resources']['style-enqueue-handlers'] as $callback ) {
		if ( $callback !== null ) {
			if ( ! is_array( $callback ) ) {
				add_action( 'wp_enqueue_scripts', $callback, 10 );
			} else { // $callback is array
				if ( ! empty( $callback['handler'] ) ) {
					isset( $callback['priority'] ) or $callback['priority'] = 10;
					add_action( 'wp_enqueue_scripts', $callback['handler'], $callback['priority'] );
				}
			}
		}
	}

	// some info
	add_action( 'after_switch_theme', 'wpgrade_callback_gtkywb' );

	// custom javascript handlers - make sure it is the last one added
	add_action( 'wp_head', 'wpgrade_callback_load_custom_js', 999 );
	add_action( 'wp_footer', 'wpgrade_callback_load_custom_js_footer', 999 );

	if ( wpgrade::option( 'inject_custom_css' ) == 'inline' ) {
		$handler = wpgrade::confoption( 'custom-css-handler', null );

		if ( empty( $handler ) ) {
			$handler = 'wpgrade_callback_inlined_custom_style';
		}

		add_action( 'wp_enqueue_scripts', $handler, 999999 );
	}
}

add_action( 'after_setup_theme', 'wpgrade_callback_themesetup', 16 );

// the callback wpgrade_callback_custom_theme_features should be placed
// in functions.php and contain theme specific settings
if ( function_exists( 'wpgrade_callback_custom_theme_features' ) ) {
	// register theme features
	add_action( 'after_setup_theme', 'wpgrade_callback_custom_theme_features' );
}

/**
 * ...
 */
function wpgrade_callbacks_setup_shortcodes_plugin() {
	$current_options = get_option( 'wpgrade_shortcodes_list' );

	$config     = wpgrade::config();
	$shortcodes = $config['shortcodes'];

	// create an array with shortcodes which are needed by the
	// current theme
	if ( $current_options ) {
		$diff_added   = array_diff( $shortcodes, $current_options );
		$diff_removed = array_diff( $current_options, $shortcodes );
		if ( ( ! empty( $diff_added ) || ! empty( $diff_removed ) ) && is_admin() ) {
			update_option( 'wpgrade_shortcodes_list', $shortcodes );
		}
	} else { // there is no current shortcodes list
		update_option( 'wpgrade_shortcodes_list', $shortcodes );
	}

	// we need to remember the prefix of the metaboxes so it can be used
	// by the shortcodes plugin
	$current_prefix = get_option( 'wpgrade_metaboxes_prefix' );
	if ( empty( $current_prefix ) ) {
		update_option( 'wpgrade_metaboxes_prefix', wpgrade::prefix() );
	}
}

add_action( 'admin_head', 'wpgrade_callbacks_setup_shortcodes_plugin' );


/**
 * ...
 */
function wpgrade_callbacks_html5_shim() {
	global $is_IE;
	if ( $is_IE ) {
		include wpgrade::corepartial( 'ie-shim' . EXT );
	}
}



function addSlider(){
	
	echo do_shortcode("[slider]");
}

function formularioreservacion(){ ?>

<div class="revervation-form back-form" style="display:none">
	<div class="pixcode pixcode--grid grid thick-gutter">
		<form id="formulario_reserva">
			<div class="form-container">
				<h2 class="text-emphasis">Solo Falta un paso mas</h2>
				<div class="row">
					<div class="coll">
						Nombre:
					</div>
					<div class="coll">
						<input type="text" placeholder="Escribe tu nombre" name="nombre" id="name" required/>
					</div>
				</div>			
				<div class="row">
					<div class="coll">
						Email:
					</div>
					<div class="coll">
						<input type="email" name="email" placeholder="Escribe tu correo electronico" id="email" required/>
					</div>
				</div>			
				<div class="row">
					<div class="coll">
						Telefono:
					</div>
					<div class="coll">
						<input type="tel" name="tel" id="phone" placeholder="Escribe tu número" required/>
					</div>
				</div>		
				<div class="row">
					<div class="coll">
						Observaciones:
					</div>
					<div class="coll">
						<textarea placeholder="Escribe tus observaciones" required name="observaciones" id="observations"></textarea>
					</div>
				</div>
				<div style="text-align:center" class="otw-button-wrap">
					<input type="submit" name="btn_enviar" class="otreservations-submit" value="SOLICITAR RESERVACIÓN" id="btn_enviar">
				</div>
			</div>
		</form>
	</div>
</div>
<div class="pixcode  pixcode--otreservations  otreservations">
	<div class="otreservation-title-wrapper">
		<h4 class="otreservations-title">Reservar tu mesa en línea es fácil y solo toma unos minutos</h4>
	</div>
		<form method="get" class="otw-widget-form" action="http://www.opentable.com/restaurant-search.aspx" target="_blank">
		<div class="otw-wrapper">
			<div class="otw-date-li otw-input-wrap">
				<label for="date-otreservations"><i class="icon-calendar"></i></label>
				<input id="date-otreservationsFix" name="startDate" class="otw-reservation-date" type="date" value="" autocomplete="off">
			</div>
			<div class="otw-time-wrap otw-input-wrap">
				<label for="time-otreservations"><i class="icon-clock-o"></i></label>
				<select id="time-otreservations" name="ResTime" class="otw-reservation-time selectpicker">
					<option value="6:00am">6:00 am</option>
					<option value="6:30am">6:30 am</option>
					<option value="7:00am">7:00 am</option>
					<option value="7:30am">7:30 am</option>
					<option value="8:00am">8:00 am</option>
					<option value="8:30am">8:30 am</option>
					<option value="9:00am">9:00 am</option>
					<option value="9:30am">9:30 am</option>
					<option value="10:00am">10:00 am</option>
					<option value="10:30am">10:30 am</option>
					<option value="11:00am">11:00 am</option>
					<option value="11:30am">11:30 am</option>
					<option value="12:00pm">12:00 pm</option>
					<option value="12:30pm">12:30 pm</option>
					<option value="1:00pm">1:00 pm</option>
					<option value="1:30pm">1:30 pm</option>
					<option value="2:00pm">2:00 pm</option>
					<option value="2:30pm">2:30 pm</option>
					<option value="3:00pm">3:00 pm</option>
					<option value="3:30pm">3:30 pm</option>
					<option value="4:00pm">4:00 pm</option>
					<option value="4:30pm">4:30 pm</option>
					<option value="5:00pm">5:00 pm</option>
					<option value="5:30pm">5:30 pm</option>
					<option value="6:00pm">6:00 pm</option>
					<option value="6:30pm">6:30 pm</option>
					<option value="7:00pm" selected="selected">7:00 pm</option>
					<option value="7:30pm">7:30 pm</option>
					<option value="8:00pm">8:00 pm</option>
					<option value="8:30pm">8:30 pm</option>
					<option value="9:00pm">9:00 pm</option>
					<option value="9:30pm">9:30 pm</option>
					<option value="10:00pm">10:00 pm</option>
					<option value="10:30pm">10:30 pm</option>
					<option value="11:00pm">11:00 pm</option>
					<option value="11:30pm">11:30 pm</option>
				</select>

			</div>
			<div class="otw-party-size-wrap otw-input-wrap">
				<label for="party-otreservations"><i class="icon-user"></i></label>
				<select id="party-otreservations" name="partySize" class="otw-party-size-select selectpicker">
					<option value="1">1 Persona</option>
					<option value="2" selected="selected">2 Personas</option>
					<option value="3">3 Personas</option>
					<option value="4">4 Personas</option>
					<option value="5">5 Personas</option>
					<option value="6">6 Personas</option>
					<option value="7">7 Personas</option>
					<option value="8">8 Personas</option>
					<option value="9">9 Personas</option>
					<option value="10">10 Personas</option>
					<option value="30">30 Personas</option>
				</select>

			</div>

			<div class="otw-button-wrap">
				<input type="button" class="otreservations-submit" value="SOLICITAR RESERVACIÓN">
			</div>
		</div>
	</form>
	</div>
	
<?php	
}

// Función que imprime el mapa didactico en nosotros
function mapa(){ ?>
	<div class="pixcode pixcode--grid grid">
		<div class="grid__item one-whole palm-one-whole texto-title-map-desc">
			<div class="map_content">
				<img src="/map.jpg" style="max-width: 100%; height: auto; width: 100%;">
				<div class="pointers">
					<div class="pointer tae" data-content='{"title":"Thomas Alba Edison","text": "Estadounidense inventor y empresario. Entre sus creaciones tenemos: el fonógrafo, la cámara de cine, y la bombilla."}'>
						<img src="/pin.png">
					</div>
					<div class="pointer hg" data-content='{"title":"Itzamná","text": "Parte superior de Dios. Cultura Azteca."}'>
						<img src="/pin.png">
					</div>
					<div class="pointer sf" data-content='{"title":"San Fermin","text": "Festival y celebración muy arraigada en la ciudad de Pamplona."}'>
						<img src="/pin.png">
					</div>
					<div class="pointer ja" data-content='{"title":"Juana de Arco","text": "Heroína, militar y santa francesa."}'>
						<img src="/pin.png">
					</div>
					<div class="pointer ok" data-content='{"title":"Oktoberfest","text": "La feria de cerveza más grande del mundo, Alemania."}'>
						<img src="/pin.png">
					</div>
					<div class="pointer ga" data-content='{"title":"Ganesha","text": "Uno de los dioses más conocidos y adorados del panteón hindú. Tiene cuerpo humano y cabeza de elefante."}'>
						<img src="/pin.png">
					</div>
					<div class="pointer dx" data-content='{"title":"Dinastía Xía","text": "Primera Dinastia de China."}'>
						<img src="/pin.png">
					</div>
					<div class="pointer fa" data-content='{"title":"Faoba","text": "Cielo - Mitología Muisca."}'>
						<img src="/pin.png">
					</div>
					<div class="pointer hp" data-content='{"title":"Hanan Pacha","text": "Lugar donde se encuentran los dioses - Lengua Quechua."}'>
						<img src="/pin.png">
					</div>
					<div class="pointer cg" data-content='{"title":"Carlos Gardel","text": "Cantante, compositor y actor de cine Argentino. Es el más conocido representante del género en la historia del tango."}'>
						<img src="/pin.png">
					</div>
					<div class="pointer ox" data-content='{"title":"Oxalá","text": "Dios creador del mundo - Mitología Afrobrasilera."}'>
						<img src="/pin.png">
					</div>
					<div class="pointer myn" data-content='{"title":"Mil y una noches","text": "Recopilación medieval en lengua árabe de cuentos tradicionales del Oriente Medio."}'>
						<img src="/pin.png">
					</div>
					<div class="pointer iip" data-content='{"title":"Il Padrino","text": "Novela de género criminal, creada por el escritor italoestadounidense Mario Puzo."}'>
						<img src="/pin.png">
					</div>
				</div>
				<div class="information">
					<div class="content_information">
						<h3></h3>
						<p></p>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<?php echo do_shortcode('[hr type="line-flower"]'); ?>
	<?php echo do_shortcode('[heading subtitle="Somos" title="Buffalo Republic"]'); ?>

	<div class="wrap_video">
		<div class="framevideo" >
			<iframe class="video_promo" style="height: 260px !important;" height="300" src="https://www.youtube.com/embed/RX_RpeOkPAM" frameborder="0" allowfullscreen>
			</iframe>
		</div>
	</div>
	
	<?php echo do_shortcode('[hr type="line-flower"]'); ?>
	
<?php } ?>


<?php 

function sliderParallax(){
	
} 

?>

<?php
add_shortcode( 'sliderp', 'addSlider' );
add_shortcode( 'formularioreservacion', 'formularioreservacion' );
add_shortcode( 'mapa', 'mapa' );



