<?php
/**
* Plugin Name: OERbox German Higher Education
* Plugin URI: https://oerhoernchen.de
* Description: Custom fields for OER metadata (German Higher Education)
* Version: 1.0
* Author: Matthias Andrasch
* Author URI: https://oerhoernchen.de
* License: CC0
*/

// 2DO: add custom fields as column? http://localhost/wordpress/pods/wp-admin/upload.php
// 2DO: add files to sitemap.xml?
// 2DO: custom field: show_oer box at end of article content?
// 2DO: get h5p metadata?
// 2DO: check out http://wordpress.org/extend/plugins/image-source-control-isc/ as well?
// 2DO: checkout https://de.wordpress.org/plugins/featured-image-caption/




//2DO: rename to OERwp?

// Restrict "Select media attachment" screen to attachments attached to current post id
// 2DO: RENAME!

// from https://wordpress.org/plugins/restrict-media-library-access/
// and https://wordpress.stackexchange.com/questions/351940/how-do-i-detect-in-which-page-ajax-query-attachments-args-is-loaded
add_filter( 'ajax_query_attachments_args', 'mrfx_show_current_user_attachments' );

function mrfx_show_current_user_attachments( $query ) {
    // 2DO: use try & catch to minimize errors?

    // restrict media attachments only to media attached to this post
    $referer = parse_url(wp_get_referer());
    parse_str($referer['query'], $params);

    if (isset($params['post']) && isset($params['action']) && $params['action'] == 'edit'){
      $query['post_parent'] = (int)$params['post']; // filter by current post id
    }
    return $query;
}

// Add relationship between oer authors (static directory) and posts (OERs)
// 2DO: RENAME!
add_action( 'mb_relationships_init', function () {
    MB_Relationships_API::register( [
        'id'   => 'posts_to_oerauthors',

        'from' => [
            'object_type' => 'post',
            'post_type'=> 'post',
            'meta_box'    => [
                'title' => 'Manages',
                'context' => 'after_title'
            ]
        ],
        'to'   => [
            'object_type' => 'post',
            'post_type'   => 'oer-author',
            'meta_box'    => [
                'title' => 'Managed By',
                'context'=>'after_title',
            ],
        ],
    ] );
} );


// Generated with
function oerbox_get_meta_box( $meta_boxes ) {
  $prefix = 'oerbox_';

  // 2DO: check if pods is installed, get all page types of pods.io

  $fields = array(
    array(
      'id' => $prefix . 'license_url',
      'name' => esc_html__( 'Lizenz', 'oerbox' ),
      'type' => 'select',
      'desc' => esc_html__( 'Creative Commons Lizenz, unter welcher die aufgeführten Inhalte lizenziert sind.', 'oerbox' ),
      'placeholder' => esc_html__( 'Lizenz auswählen', 'oerbox' ),
      'options' => array(
        'https://creativecommons.org/publicdomain/zero/1.0/' => esc_html__( 'CC0', 'oerbox' ),
        'https://creativecommons.org/licenses/by/4.0/' => esc_html__( 'CC BY 4.0', 'oerbox' ),
        'https://creativecommons.org/licenses/by-sa/4.0/' => esc_html__( 'CC BY-ShareAlike 4.0', 'oerbox' ),
      ),
    ),

    array(
      'id'      => $prefix . 'creator_additional_persons',
      'name'    => esc_html__( 'Urheber*innen: Weitere Person(en)', 'oerbox' ),
      'desc' => esc_html__( 'Personen, welche bei Nachnutzung genannt werden sollen und hier nur einmalig eingetragen werden, d.h. nicht im Autor:innenverzeichnis mit Profilbild bereits erfasst sind. Bereits erfasste Personen bitte über die Auswahl "Autor:innen aus Autorenverzeichnis auswählen"', 'oerbox' ),
      'type'    => 'fieldset_text',
      // Options: array of key => Label for text boxes
      // Note: key is used as key of array of values stored in the database
      'options' => array(
          'givenName'    => 'Vorname',
          'familyName' => 'Nachname',
          'URL'   => 'URL/Identifier',
      ),
      // Is field cloneable?
      'clone' => true,
  ),
  array(
    'id'      => $prefix . 'creator_organizations',
    'name'    => esc_html__( 'Urheber: Organisation(en)', 'oerbox' ),
    'desc' => esc_html__( 'Organisation, Institution, Projekt, welches bei Nachnutzung genannt werden soll.', 'oerbox' ),
    'type'    => 'fieldset_text',
    // Options: array of key => Label for text boxes
    // Note: key is used as key of array of values stored in the database
    'options' => array(
        'name'    => 'Name',
        'URL'   => 'URL/Identifier',
    ),
    // Is field cloneable?
    'clone' => true,
),
    /*array(
    'id' => $prefix . 'license_attribution',
    'type' => 'textarea',
    'name' => esc_html__( 'Urheber*innen-Attribution', 'oerbox' ),
    'desc' => esc_html__( 'Wie sollen die Ersteller*innen bei einer Nachnutzung von Dritten angegeben werden?', 'oerbox' ),
    'placeholder' => esc_html__( 'Bspw. \"Getrude Blanch und Rózsa Péter\" oder \"Hedy Lamarr für Projekt42\"', 'oerbox' ),
    'rows' => 3,
  ),*/
  array(
    'id' => $prefix . 'created_year',
    'type' => 'text',
    'name' => esc_html__( 'Erstellungsjahr', 'oerbox' ),
    'placeholder' => esc_html__( '2019', 'oerbox' ),
  ),
  array(
    'id' => $prefix . 'subject_area',
    'name' => esc_html__( 'Fachbereich', 'oerbox' ),
    'type' => 'select',
    'placeholder' => esc_html__( 'Fachbereich auswählen', 'oerbox' ),
    'options' => array(
      'agrar_forst' => esc_html__( 'Agrar-/Forstwissenschaften', 'oerbox' ),
      'gesellschaft_sowi' => esc_html__( 'Gesellschafts- und Sozialwissenschaften', 'oerbox' ),
      'ingenieur' => esc_html__( 'Ingenieurwissenschaften', 'oerbox' ),
      'kunst_musik_design' => esc_html__( 'Kunst, Musik, Design', 'oerbox' ),
      'lehramt' => esc_html__( 'Lehramt', 'oerbox' ),
      'mathe_nawi' => esc_html__( 'Mathematik, Naturwissenschaften', 'oerbox' ),
      'medizin_gesundheit' => esc_html__( 'Medizin, Gesundheitswissenschaften', 'oerbox' ),
      'oeffentliche_verwaltung' => esc_html__( 'Öffentliche Verwaltung', 'oerbox' ),
      'sprach_kultur' => esc_html__( 'Sprach- und Kulturwissenschaften', 'oerbox' ),
      'wirtschaft_recht' => esc_html__( 'Wirtschaftswissenschaften, Rechtswissenschaften', 'oerbox' ),
    ),
  ),
  array(
    'id' => $prefix . 'type',
    'name' => esc_html__( 'Material ist/enthält', 'oerbox' ),
    'type' => 'checkbox_list',
    'options' => array(
      'Arbeitsblatt' => esc_html__( 'Arbeitsblatt', 'oerbox' ),
      'Audio' => esc_html__( 'Audio', 'oerbox' ),
      'Präsentationsfolien' => esc_html__( 'Präsentationsfolien', 'oerbox' ),
      'Interaktiver Inhalt' => esc_html__( 'Interaktiver Inhalt', 'oerbox' ),
      'Kurs - Einheit/Modul' => esc_html__( 'Kurs - Einheit/Modul', 'oerbox' ),
      'Kurs - mehrwöchig' => esc_html__( 'Kurs - mehrwöchig', 'oerbox' ),
      'Podcast' => esc_html__( 'Podcast', 'oerbox' ),
      'Simulation' => esc_html__( 'Simulation', 'oerbox' ),
      'Textbook/E-Book' => esc_html__( 'Textbook/E-Book', 'oerbox' ),
      'Unterricht-/Seminarverlauf' => esc_html__( 'Unterricht-/Seminarverlauf', 'oerbox' ),
      'Übungsaufgaben/Assessment' => esc_html__( 'Übungsaufgaben/Assessment', 'oerbox' ),
      'Video' => esc_html__( 'Video', 'oerbox' ),
    ),
  ),
  array(
    'id' => $prefix . 'technical_tools_formats',
    'name' => esc_html__( 'Technisches Format', 'oerbox' ),
    'type' => 'checkbox_list',
    'desc' => esc_html__( 'Welche technischen Formate werden bereitgestellt?', 'oerbox' ),
    'options' => array(
      'h5p' => esc_html__( 'h5p', 'oerbox' ),
      'Microsoft Office' => esc_html__( 'Microsoft Office', 'oerbox' ),
      'Open/Libre Office' => esc_html__( 'Open/Libre Office', 'oerbox' ),
      'Google Docs' => esc_html__( 'Google Docs', 'oerbox' ),
      'PDF' => esc_html__( 'PDF', 'oerbox' ),
      'Apple Keynote/Pages' => esc_html__( 'Apple Keynote/Pages', 'oerbox' ),
      'Pressbooks' => esc_html__( 'Pressbooks', 'oerbox' ),
      'Reveal-Präsentation' => esc_html__( 'Reveal-Präsentation', 'oerbox' ),
      'Static Site Generator' => esc_html__( 'Static Site Generator', 'oerbox' ),
      'Markdown' => esc_html__( 'Markdown', 'oerbox' ),
      'Moodle (LMS)' => esc_html__( 'Moodle (LMS)', 'oerbox' ),
      'ILIAS (LMS)' => esc_html__( 'ILIAS (LMS)', 'oerbox' ),
      'Slidewiki' => esc_html__( 'Slidewiki', 'oerbox' ),
      'SCORM' => esc_html__( 'SCORM', 'oerbox' ),
    ),
  ),
);

$meta_boxes[] = array(
  'id' => 'oerbox1',
  'title' => esc_html__( 'OERbox Metadaten für dieses Objekt/URL', 'oerbox' ),
  // 2DO: "materials" was hardcoded here, we need to solve this dynamically (post type by pods) -> global setting
  'post_types' => array('post', 'page' , 'material','attachment'),
  'context' => 'after_title',
  'priority' => 'default',
  'autosave' => 'true',
  'fields' => $fields
);

return $meta_boxes;
}
add_filter( 'rwmb_meta_boxes', 'oerbox_get_meta_box' );



      // add metadata to the head of HTML
      // Add scripts to wp_head()
      function oerbox_add_metadata_to_head() {
        // 2DO: generalize this!
        $prefix = 'oerbox_';

        //var_dump(get_post_types());

        // 2DO: will double definition of schema.org break the page? :/ (SEO yoast e.g.?)

        //var_dump(get_post_meta(get_the_ID()));

        // 2DO: use isset / do not cause page errors

        // 2DO: check if attachments have get_metadata_from_page Option activated

        $title = get_the_title(get_the_ID());
        $url = "";

        // 2DO: get creator_persons and creator_organizations, check if empty

        // this is important for search engines (e.g. Google uses it)
        $license_url = esc_html( get_post_meta( get_the_ID(), $prefix.'license_url', true ));
        echo "<link rel='license' href='{$license_url}' />";
        ?>
        <script type="application/ld+json">{
          "creator": {
            "@type": "Person",
            "givenName": "Anja",
            "familyName": "Schreiber"
          },
          "@type": [
            "CreativeWork",
            "MediaObject"
          ],
          "description": "Flyer (DinA5) zu OER an Hochschulen mit Kurzinformation zu den Vorteilen von OER, zu OER-Lizenzen und OER-Nutzung. Kontaktfeld zur Ergänzung der Ansprechpartner an der eigenen Einrichtung. ZOERR Flyer (http://hdl.handle.net/10900.3/OER_ZeGROxrH) kann gut als Einlage ergänzt werden.",
            "inLanguage": "de",
            "dateModified": "2019-09-26T18:12:12+02:00",
            "@context": "http://schema.org/",
            "version": "1.5",
            "url": "https://uni-tuebingen.oerbw.de/edu-sharing/components/render/286e4ce6-54b0-4c4f-aa90-d9004138d5d0",
            "datePublished": "2019-09-26T18:11:54+02:00",
            "license": "https://creativecommons.org/licenses/by-sa/4.0/deed.en",
            "dateCreated": "2019-09-25T11:34:09+02:00",
            "name": "OER an Hochschulen. Eine Übersicht.",
            "publisher": {
              "legalName": "OER digital@bw Projekt",
              "@type": "Organization"
            },
            "learningResourceType": "teaching_aids",
            "thumbnailUrl": "https://uni-tuebingen.oerbw.de/edu-sharing/preview?nodeId=286e4ce6-54b0-4c4f-aa90-d9004138d5d0&storeProtocol=workspace&storeId=SpacesStore&dontcache=1571228718151"
          }</script>

          <?php
        }
        add_action( 'wp_head', 'oerbox_add_metadata_to_head' );



// Custom post type for OER authors directory (static)

// 2DO: RENAME!


function your_prefix_register_post_type() {

	$args = array (
		'label' => esc_html__( 'OER authors', 'text-domain' ),
		'labels' => array(
			'menu_name' => esc_html__( 'OER authors', 'text-domain' ),
			'name_admin_bar' => esc_html__( 'OER author', 'text-domain' ),
			'add_new' => esc_html__( 'Add new', 'text-domain' ),
			'add_new_item' => esc_html__( 'Add new OER author', 'text-domain' ),
			'new_item' => esc_html__( 'New OER author', 'text-domain' ),
			'edit_item' => esc_html__( 'Edit OER author', 'text-domain' ),
			'view_item' => esc_html__( 'View OER author', 'text-domain' ),
			'update_item' => esc_html__( 'Update OER author', 'text-domain' ),
			'all_items' => esc_html__( 'All OER authors', 'text-domain' ),
			'search_items' => esc_html__( 'Search OER authors', 'text-domain' ),
			'parent_item_colon' => esc_html__( 'Parent OER author', 'text-domain' ),
			'not_found' => esc_html__( 'No OER authors found', 'text-domain' ),
			'not_found_in_trash' => esc_html__( 'No OER authors found in Trash', 'text-domain' ),
			'name' => esc_html__( 'OER authors', 'text-domain' ),
			'singular_name' => esc_html__( 'OER author', 'text-domain' ),
		),
		'public' => true,
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_nav_menus' => true,
		'show_in_admin_bar' => true,
		'show_in_rest' => true,
		'menu_position' => 5,
		'menu_icon' => 'dashicons-id-alt',
		'capability_type' => array(
			'oer author',
			'oer authors',
		),
		'hierarchical' => false,
		'has_archive' => true,
		'query_var' => true,
		'can_export' => true,
		'rewrite_no_front' => false,
		'supports' => array(
			'title',
			'editor',
			'thumbnail',
			'revisions',
		),
		'map_meta_cap' => true,
    // THIS LINE IS IMPORTANT, OTHERWISE IT WON'T WORK (rewrite=true created by MB Custom Types plugin)
    // 'rewrite'=> true,
		'rewrite' => array('slug' => "oer-author", 'with_front' => TRUE)
	);

	register_post_type( 'oer-author', $args );
}
add_action( 'init', 'your_prefix_register_post_type' );
