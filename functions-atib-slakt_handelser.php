<?php
/*
* Funktioner för släkthändelser nedan
*
* Skapa custom post types för "Släkthändelser": slakt_handelser
* Lägg till taxonomy slakt-gren
* Lägg till taxonomy handelse-typ
* http://generatewp.com/snippet/bkdra1w/
* Skapa template för arkiv och single (template-filer i themes-folder)
* ((http://premium.wpmudev.org/blog/create-wordpress-custom-post-types/))
* http://www.wpbeginner.com/wp-tutorials/how-to-create-custom-post-types-in-wordpress/
* 
* CPT släkthändelser: 'slakt_handelser'
*
* CPT-taxonomies: 'handelse-typ': Födda, In Memoriam, Vigda, ...
* CPT-taxonomies: 'slakt-gren': Andreas-grenen, Anders-grenen, ...
*
* Följande mallar behövs i themes-folder:
* content-slakt_handelser.php	- visa innehållet i CPT
* single-slakt_handelser.php	- visa enskild/"Single" CPT
* archive-slakt_handelser.php	- visa arkiv med alla CPT'er
* taxonomy-handelse-typ.php	 	- visa arkiv med alla CPT'er med Händelsetyp x
* taxonomy-slakt-gren.php	 	- visa arkiv med alla CPT'er med släktgren x 
*
*/


// Register Custom Post Type
function atib_slakt_handelse_cpt() {

	$labels = array(
		'name'                => _x( 'Släkthändelser', 'Post Type General Name', 'twentyfourteen' ),
		'singular_name'       => _x( 'Släkthändelse', 'Post Type Singular Name', 'twentyfourteen' ),
		'menu_name'           => __( 'Släkthändelser', 'twentyfourteen' ),
		'parent_item_colon'   => __( 'Parent Item:', 'twentyfourteen' ),
		'all_items'           => __( 'Alla händelser', 'twentyfourteen' ),
		'view_item'           => __( 'Visa', 'twentyfourteen' ),
		'add_new_item'        => __( 'Skapa ny händelse', 'twentyfourteen' ),
		'add_new'             => __( 'Skapa ny', 'twentyfourteen' ),
		'edit_item'           => __( 'Redigera händelse', 'twentyfourteen' ),
		'update_item'         => __( 'Update Item', 'twentyfourteen' ),
		'search_items'        => __( 'Search Item', 'twentyfourteen' ),
		'not_found'           => __( 'Hittade inga händelser', 'twentyfourteen' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'twentyfourteen' ),
		);
	$rewrite = array(
		'slug'                => 'slakt-handelser',
		'with_front'          => true,
		'pages'               => true,
		'feeds'               => false,
	);
	$args = array(
		'label'               => __( 'slakt_handelser', 'twentyfourteen' ),
		'description'         => __( 'Post Type för Släkt händelser såsom födda, döda, vigda, födelsedag, etc.', 'twentyfourteen' ),
		'labels'              => $labels,
		'supports'            => array(  'title', 'editor', 'author', 'comments', ),
		//'taxonomies'          => array( 'handelse_typ', 'slakt-gren' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-star-filled',
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'page',
	);
	register_post_type( 'slakt_handelser', $args );

}

// Hook into the 'init' action
add_action( 'init', 'atib_slakt_handelse_cpt', 0 );


// Register Custom Taxonomy
function atib_slakt_gren_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Släktgrenar', 'Taxonomy General Name', 'twentyfourteen' ),
		'singular_name'              => _x( 'Släktgren', 'Taxonomy Singular Name', 'twentyfourteen' ),
		'menu_name'                  => __( 'Släktgrenar', 'twentyfourteen' ),
		'all_items'                  => __( 'All Items', 'twentyfourteen' ),
		'parent_item'                => __( 'Parent Item', 'twentyfourteen' ),
		'parent_item_colon'          => __( 'Parent Item:', 'twentyfourteen' ),
		'new_item_name'              => __( 'New Item Name', 'twentyfourteen' ),
		'add_new_item'               => __( 'Lägg till släktgren', 'twentyfourteen' ),
		'edit_item'                  => __( 'Redigera släkt-gren', 'twentyfourteen' ),
		'update_item'                => __( 'Update Item', 'twentyfourteen' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'twentyfourteen' ),
		'search_items'               => __( 'Search Items', 'twentyfourteen' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'twentyfourteen' ),
		'choose_from_most_used'      => __( 'Choose from the most used items', 'twentyfourteen' ),
		'not_found'                  => __( 'Not Found', 'twentyfourteen' ),
	);
	$rewrite = array(
		'slug'                       => 'handelser/gren',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'slakt-gren', array( 'slakt_handelser' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'atib_slakt_gren_taxonomy', 0 );


// Register Custom Taxonomy
function atib_slakt_handelse_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Händelsetyper', 'Taxonomy General Name', 'twentyfourteen' ),
		'singular_name'              => _x( 'Händelsetyp', 'Taxonomy Singular Name', 'twentyfourteen' ),
		'menu_name'                  => __( 'Händelsetyper', 'twentyfourteen' ),
		'all_items'                  => __( 'All Items', 'twentyfourteen' ),
		'parent_item'                => __( 'Parent Item', 'twentyfourteen' ),
		'parent_item_colon'          => __( 'Parent Item:', 'twentyfourteen' ),
		'new_item_name'              => __( 'New Item Name', 'twentyfourteen' ),
		'add_new_item'               => __( 'Lägg till händelsetyp', 'twentyfourteen' ),
		'edit_item'                  => __( 'Redigera händelsetyp', 'twentyfourteen' ),
		'update_item'                => __( 'Update Item', 'twentyfourteen' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'twentyfourteen' ),
		'search_items'               => __( 'Search Items', 'twentyfourteen' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'twentyfourteen' ),
		'choose_from_most_used'      => __( 'Choose from the most used items', 'twentyfourteen' ),
		'not_found'                  => __( 'Not Found', 'twentyfourteen' ),
	);
	$rewrite = array(
		'slug'                       => 'handelser',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'handelse-typ', array( 'slakt_handelser' ), $args );

}

// Hook into the 'init' action
add_action( 'init', 'atib_slakt_handelse_taxonomy', 0 );

