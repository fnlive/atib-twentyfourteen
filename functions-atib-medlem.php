<?php
// included from functions.php
// Innehåller funktioner för medlemsinnehåll

// Begränsa åtkomst till medlemsinnehåll
// Returnera sidan "ej_medlem_content.php" om användaren inte är behörig
// till att "visa_medlems_innehall" och sidan som vill se är skyddad (listad nedan).
// Skyddade sidor:
// - "Single post" med CPT 'slakt_handelser'
// - arkiv med CPT 'slakt_handelser'
// - taxonomi-arkiv 'slakt-gren', 'handelse-typ' som hör till CPT 'slakt_handelser'
// - sidan 'slakttrad'
//
// Kopierat efter:
// http://justintadlock.com/archives/2012/10/16/how-i-run-a-membership-site
// Kräver plugin Members
add_filter( 'template_include', 'atib_ej_medlem_template', 99 );

function atib_ej_medlem_template( $template ) {
	$kan_visa_medlems_innehall = current_user_can( 'visa_medlems_innehall' );
	if ( is_singular( array( 'slakt_handelser' ) ) && !$kan_visa_medlems_innehall ){
		$template = locate_template( array( 'ej_medlem_content.php' ) );
	}
	elseif ( is_post_type_archive( array( 'slakt_handelser' ) ) && !$kan_visa_medlems_innehall ){
		$template = locate_template( array( 'ej_medlem_content.php' ) );
	}
	elseif ( is_tax( array( 'slakt-gren', 'handelse-typ' ) ) && !$kan_visa_medlems_innehall ){
		$template = locate_template( array( 'ej_medlem_content.php' ) );
	}
	elseif ( is_page( 'slakttrad' ) && !$kan_visa_medlems_innehall ){
		$template = locate_template( array( 'ej_medlem_content.php' ) );
	}
	else {
	}
	return $template;
}


// Sök filter
// Ta bort sökresultat om ej betalande medlem, dvs  ej kan visa_medlems_innehall
// Ta bort CPT slakt_handalser (dvs. visa bara resultat från 'post','page'
function atib_search_filter($query) {
    if ( !$query->is_admin && $query->is_search && !current_user_can( 'visa_medlems_innehall' )) {
        $query->set('post_type',array('post','page'));    }

    return $query;
}
add_filter( 'pre_get_posts', 'atib_search_filter' );


// Visa bara verktygsraden för adminstrator,
// ej för andra inloggade användare.
add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
  if (!current_user_can('administrator') && !is_admin()) {
    show_admin_bar(false);
  }
}


// Registrera Widget Sidebar för sidan med medlemsinnehåll
// Denna kan sedan visas genom anrop i respektive template-fil
// http://wpgyan.com/how-to-create-a-widget-area-in-wordpress-theme/
add_action( 'widgets_init', 'atib_medlem_widgets_init' );

function atib_medlem_widgets_init() {

	$args = array(
		'id'            => 'atib-medlem-sidebar-1',
		'name'          => __( 'Sidofält Medlem', 'twentyfourteen' ),
		'description'   => __( 'Sidofält som kan visas på höger sida på medlems-sidor.', 'twentyfourteen' ),
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
	);
	register_sidebar( $args );
}



// Funktioner nedanför används till släktträdet.
//
// Add Shortcode. Shortcode skapar länk till personakt i släktträdet.
// Kan användas i inlägg och på sidor genom att sätta kort-koden runt ett namn.
// Ange namn på personaktsfil som parameter p. Namn finns i filen gendex.txt
// Användning: [atib_person p=p2592929b]Anna Moberg[/atib_person]
// Namne på personaktsfilen är då alltså p2592929b.html
// Öppnar personakt i nytt fönster eller tab (target="_blank")
// Säg till sökrobot att inte följa länk (rel="nofollow")
function atib_slakttrad_person( $atts , $content = null ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'p' => 'p2e222c41',
		), $atts )
	);
	$url = get_home_url();

	// Code
return '<a title="Personakt visas i nytt fönster" href="'.$url.'/slakttrad/?p='.$p.'" target="_blank" rel="nofollow">' . $content . '</a>';
}
add_shortcode( 'atib_person', 'atib_slakttrad_person' );

// Add Quicktag (knapp) till text-editorn
// skapar en shortcode med personakt i släktträdet. Se shortcode nedan.
// Markera en text i texteditorn och tryck sedan på knappen "person"
function person_quicktags() {
	if ( wp_script_is( 'quicktags' ) ) {
	?>
	<script type="text/javascript">
	QTags.addButton( 'atib_person', 'person', '[atib_person p=""]', '[/atib_person]', 'p', 'personakt', 1 );
	</script>
	<?php
	}
}
// Hook into the 'admin_print_footer_scripts' action
add_action( 'admin_print_footer_scripts', 'person_quicktags' );

//ToDo: Skapa knapp som ovan fast till TinyMCE
//

// Extra medlemsinformation i användarprofilen
// Lägg till släkt-gren som metadata under user profle
//
add_action( 'show_user_profile', 'atib_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'atib_show_extra_profile_fields' );

function atib_show_extra_profile_fields( $user ) { ?>

	<h3>Extra medlemsinformation</h3>

	<table class="form-table">
		<tr>
			<th><label for="slaktgren">Släktgren</label></th>
			<td>

			            <?php
            //get dropdown saved value
            $selected = get_the_author_meta( 'slaktgren', $user->ID );
            ?>
            <select name="slaktgren" id="slaktgren">
                <option value="" <?php echo ($selected == "")?  'selected="selected"' : ''; ?>>Ej vald</option>
                <option value="Anna-grenen" <?php echo ($selected == "Anna-grenen")?  'selected="selected"' : ''; ?>>Anna-grenen</option>
                <option value="Andreas-grenen" <?php echo ($selected == "Andreas-grenen")?  'selected="selected"' : ''; ?>>Andreas-grenen</option>
                <option value="Anders-grenen" <?php echo ($selected == "Anders-grenen")?  'selected="selected"' : ''; ?>>Anders-grenen</option>
                <option value="Maria-grenen" <?php echo ($selected == "Maria-grenen")?  'selected="selected"' : ''; ?>>Maria-grenen</option>
                <option value="Annika-grenen" <?php echo ($selected == "Annika-grenen")?  'selected="selected"' : ''; ?>>Annika-grenen</option>
                <option value="Anna Stina-grenen" <?php echo ($selected == "Anna Stina-grenen")?  'selected="selected"' : ''; ?>>Anna Stina-grenen</option>
                <option value="Ej ättling" <?php echo ($selected == "Ej ättling")?  'selected="selected"' : ''; ?>>Ej ättling</option>
            </select>
            <span class="description">Välj släktgren</span>

			</td>
		</tr>
	</table>
<?php }

add_action( 'personal_options_update', 'atib_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'atib_save_extra_profile_fields' );

function atib_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	/* Copy and paste this line for additional fields. */
	update_usermeta( $user_id, 'slaktgren', $_POST['slaktgren'] );
}
