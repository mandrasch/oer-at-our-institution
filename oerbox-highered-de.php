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

// 2DO: change everything to EDITOR USER ROLE, we don't work with author/contributor anymore (too complicated)

// 2DO: Perfomance: Don't set permissions on every page load, what is a better way?

//2DO: rename to OERwp?

// Restrict "Select media attachment" screen to attachments attached to current post id
// 2DO: RENAME!

// from https://wordpress.org/plugins/restrict-media-library-access/
// and https://wordpress.stackexchange.com/questions/351940/how-do-i-detect-in-which-page-ajax-query-attachments-args-is-loaded
add_filter( 'ajax_query_attachments_args', 'mrfx_show_current_user_attachments' );

function mrfx_show_current_user_attachments( $query ) {
  // 2DO: use try & catch to minimize errors?
  // 2DO: wp_get_referer() returns false if nonexistent

  // restrict media attachments only to media attached to this post
  $referer = parse_url(wp_get_referer());
  parse_str($referer['query'], $params);

  if(strpos($referer['path'],"post-new.php") > 0){
    // just use bogus value, new post does not have an id yet
    $query['post_parent'] = -111;
  }

  if (isset($params['post']) && isset($params['action']) && $params['action'] == 'edit'){
    $query['post_parent'] = (int)$params['post']; // filter by current post id
  }
  return $query;
}



// Generated with
function oerbox_get_meta_box( $meta_boxes ) {
  $prefix = 'oerbox_';

  // 2DO: check if pods is installed, get all page types of pods.io

  $fields = array(
    // 2DO: how do we deal with author?
    array(
      'name'        => esc_html__( 'Mitarbeitende', 'oerbox' ),
      'desc' => esc_html__( 'Nutzer/innen, die diesen Eintrag mitbearbeiten können. Dies ist nicht die Liste der Urheber/innen, Autor/innen!'),
      'id'          => 'coeditor_users',
      'type'        => 'user',
      'clone'    => false,
      'multiple' => true,
      // Field type.
      'field_type'  => 'select_advanced',
      // Placeholder.
      'placeholder' => 'Wordpress-Nutzeraccount auswählen',
      // Query arguments (optional). No settings means get all published users.
      // @see https://codex.wordpress.org/Function_Reference/get_users
      'query_args'  => array(),
    ),
    array(
      'type' => 'divider',
    ),
    array(
      'type' => 'heading',
      'name' => 'OER-Metadaten',
      'desc' => 'OER-Metadaten',
    ),
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
      'name'        => 'Autor/innen',
      'id'          => 'oerauthors',
      'type'        => 'post',

      // Post type.
      'post_type'   => 'oerauthor',

      // Field type.
      'field_type'  => 'select_advanced',
      'multiple'=>true,

      // Placeholder, inherited from `select_advanced` field.
      'placeholder' => 'Select an author from authors directory',

      // Query arguments. See https://codex.wordpress.org/Class_Reference/WP_Query
      /*'query_args'  => array(
      'post_status'    => 'publish',
      'posts_per_page' => - 1,
    ),*/
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
  'post_types' => array('post'),
  'context' => 'normal',
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
        'oerauthor',
        'oerauthors',
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
      'rewrite' => array('slug' => "oerauthor", 'with_front' => TRUE)
    );

    register_post_type( 'oerauthor', $args );
  }
  add_action( 'init', 'your_prefix_register_post_type' );


  // meta_box


  // Generated with
  function oerbox_get_meta_box_oerauthor( $meta_boxes ) {
    $prefix = 'oerbox_';

    // 2DO: check if pods is installed, get all page types of pods.io

    $fields = array(
      // 2DO: how do we deal with author?
      array(
        'name'        => esc_html__( 'Mitarbeitende', 'oerbox' ),
        'desc' => esc_html__( 'Nutzer/innen, die diesen Eintrag mitbearbeiten können. Dies ist nicht die Liste der Urheber/innen, Autor/innen!'),
        'id'          => 'coeditor_users',
        'type'        => 'user',
        'clone'    => false,
        'multiple' => true,
        // Field type.
        'field_type'  => 'select_advanced',
        // Placeholder.
        'placeholder' => 'Wordpress-Nutzeraccount auswählen',
        // Query arguments (optional). No settings means get all published users.
        // @see https://codex.wordpress.org/Function_Reference/get_users
        'query_args'  => array(),
      )
  );

  $meta_boxes[] = array(
    'id' => 'oerbox1',
    'title' => esc_html__( 'OERbox', 'oerbox' ),
    'post_types' => array('oerauthor'),
    'context' => 'normal',
    'priority' => 'default',
    'autosave' => 'true',
    'fields' => $fields
  );

  return $meta_boxes;
  }
  add_filter( 'rwmb_meta_boxes', 'oerbox_get_meta_box_oerauthor' );


  // 2DO: better way? not always on admin_init?
  function allow_editors_access_to_oerauthors(){
    $editor = get_role('editor');
    $editor->add_cap('edit_oerauthor');
    $editor->add_cap('edit_oerauthors');
    $editor->remove_cap('publish_oerauthors'); // 2DO: option in backend wheter to allow that?
    $editor->add_cap('edit_published_oerauthors');
    $editor->add_cap('edit_others_oerauthors');

    /*'edit_post' => 'edit_course_document',
           'edit_posts' => 'edit_course_documents',
           'edit_others_posts' => 'edit_other_course_documents',
           'publish_posts' => 'publish_course_documents',
           'read_post' => 'read_course_document',
           'read_private_posts' => 'read_private_course_documents',
           'delete_post' => 'delete_course_document'*/
  }
  add_action ('admin_init', 'allow_editors_access_to_oerauthors');


  // https://wpmayor.com/how-to-remove-menu-items-in-admin-depending-on-user-role/
  add_action( 'admin_init', 'my_remove_menu_pages' );

  function my_remove_menu_pages() {
    global $user_ID;
    // only admins have this option
    if ( !current_user_can('administrator') ) {
      remove_menu_page('upload.php'); // Media
      remove_menu_page('tools.php'); // Media
    }
  }

  // we remove all contact info in "edit profile" because this is done in guest author field
  function filter_user_contact_methods( $methods ) {
    $methods = array();
    return $methods;
  }
  add_filter( 'user_contactmethods', 'filter_user_contact_methods' );

  // we remove biographical info as well with a little js trick
  // https://www.majas-lapu-izstrade.lv/how-to-remove-wordpress-admin-profile-page-fields-including-personal-options-biographical-info-website-etc-and-titles-without-js/
  //Remove fields from Admin profile page via JS to hide nickname field which is mandatory
  function remove_personal_options(){
    if ( ! current_user_can('manage_options') ) { // 'update_core' may be more appropriate
      echo '<script type="text/javascript">jQuery(document).ready(function($) {
        $(\'form#your-profile tr.user-description-wrap\').hide(); // Hide
        $(\'form#your-profile tr.user-url-wrap\').hide(); // Hide
        $(\'form#your-profile tr.user-profile-picture\').hide(); // Hideuser-profile-picture
      });</script>';
    }
  }
  add_action('admin_head','remove_personal_options');

  // https://wpvip.com/documentation/add-guest-bylines-to-your-content-with-co-authors-plus/#incorporating-new-profile-fields
  /**
  * Add a "Google Plus" field to Co-Authors Plus Guest Author
  */
  add_filter( 'coauthors_guest_author_fields', 'capx_filter_guest_author_fields', 10, 2 );
  function capx_filter_guest_author_fields( $fields_to_return, $groups ) {


    // 2DO: why check this?
    if ( in_array( 'all', $groups ) || in_array( 'contact-info', $groups ) ) {

      $fields_to_return[] = array(
        'key'      => 'orcid',
        'label'    => 'ORCID',
        'group'    => 'contact-info',
      );
      $fields_to_return[] = array(
        'key'      => 'twitter',
        'label'    => 'Twitter',
        'group'    => 'contact-info',
      );

      // does not work?
      // 2DO: ask support?
      /*$keys_to_remove = array('aim','jabber','yahooim');
      foreach($keys_to_remove as $key_to_remove){
      $index_found = array_search($key_to_remove, array_column($fields_to_return, 'key'));
      if($index_found !== FALSE){
      unset($fields_to_return[$index_found]);
    }
  }*/
}
return $fields_to_return;
}

// 2DO: move this option in admin menu? (Options framework?)
// https://thewebtier.com/wordpress/prevent-authors-directly-publish-posts-wordpress/
function remove_editor_publish_posts_and_edit_pages(){
  // $wp_roles is an instance of WP_Roles.
  global $wp_roles;
  $wp_roles->remove_cap( 'editor', 'publish_posts' );

  // we also remove option to access Pages
  $wp_roles->remove_cap( 'editor', 'edit_pages' );
}
add_action( 'init', 'remove_editor_publish_posts_and_edit_pages' );

// 2DO: let contributors add/change guest authors
// box is only shown with edit_others_post permission
// this has to be only allowed in edit screen and based on their authorship
// e.g. with https://wordpress.stackexchange.com/questions/53230/temporary-capability-for-current-user-can/53234 ?




// https://wordpress.stackexchange.com/questions/298982/can-i-create-users-that-have-access-to-some-other-users-posts-instead-of-all-o
function restrict_access_to_company_posts( $caps, $cap, $user_id, $args ) {

  /*
  We're messing with capabilities only if 'edit_post'
  is currently checked and the current user has editor role
  but is not the administrator
  */

  if ( ! in_array( $cap, [ 'edit_post' ], true ) && ! in_array( $cap, [ 'edit_oerauthors' ], true ) ) {
    return $caps;
  }

  // we only mess with editor user role permissions, not with admin permissions
  if ( ! user_can( $user_id, 'editor' ) || user_can( $user_id, 'administrator' ) ) {
    return $caps;
  }

  /*
  $args[0] holds post ID. $args var is a bit enigmatic, it contains
  different stuff depending on the context and there's almost
  no documentation on that, you've got to trust me on this one :)
  Anyways, if no post ID is set, we bail out and return default capabilities
  */
  if ( empty( $args[0] ) ) {
    return $caps;
  }

  /*
  You can also make sure that you're restricting access
  to posts only and not pages or other post types
  */
  $post_type = get_post_type( $args[0] );
  if ( 'post' !== $post_type && 'oerauthor' !== $post_type) {
    return $caps;
  }

  // two possible ways for permissions
  // a) user is set as author of post (wordpress standard behaviour)
  // b) user is linked in metabox.io meta field (coeditor_users)

  // check if user is set as author
  $post_author_id = get_post_field( 'post_author', $args[0] );

  // metabox.io
  // check coeditor_users field
  $coeditor_users = rwmb_meta('coeditor_users', '', $args[0] );

  // if user is listed in coeditor_users or if user is original author of post, we'll allow editing
  if(in_array($user_id, $coeditor_users) || $user_id == $post_author_id){
    return $caps;
  }

  // finally, in all other cases, we restrict access to this post
  $caps = [ 'do_not_allow' ];
  return $caps;
}

add_filter( 'map_meta_cap', 'restrict_access_to_company_posts', 10, 4 );

function query_company_posts_only( $query ) {



  if ( is_admin() || empty( get_current_user_id() ) ) {
    return $query;
  }
  // 2DO: this function needs more work!
  return $query;

  // 2DO: how to set OR / AND In query?

  // 2DO: If post type is different, bail out

  /*$editor_company = get_user_meta( get_current_user_id(), 'company', true );

  if ( empty( $editor_company ) ) {
  return $query;
}*/

/*  $args = array(
'relation' => 'OR',
array(
'key' =>  'author',
'value' => get_current_user_id()
//'compare' => '='
),
array(
'key' => 'coeditor_users',
'value' => get_current_user_id()
//'compare' => 'IN',

)
);

$query->set($args);*/

// 2DO: how to set OR??????????

$query->set( 'author', get_current_user_id() );

$query->set( 'meta_key', 'coeditor_users' );
$query->set( 'meta_value', get_current_user_id() );
}

add_action( 'pre_get_posts', 'query_company_posts_only', 10, 1 );

// https://wpbeaches.com/change-the-wordpress-post-type-name-to-something-else/
add_action( 'init', 'cp_change_post_object' );
// Change dashboard Posts to News
function cp_change_post_object() {
    $get_post_type = get_post_type_object('post');
    $labels = $get_post_type->labels;
        $labels->name = 'OERs';
        $labels->singular_name = 'OER';
        $labels->add_new = 'Add OER';
        $labels->add_new_item = 'Add OER';
        $labels->edit_item = 'Edit OER';
        $labels->new_item = 'OERs';
        $labels->view_item = 'View OERs';
        $labels->search_items = 'Search OER';
        $labels->not_found = 'No OERs found';
        $labels->not_found_in_trash = 'No OERs found in Trash';
        $labels->all_items = 'All OERs';
        $labels->menu_name = 'OERs';
        $labels->name_admin_bar = 'OER';
}

// we remove this, media should only be added when in post->add/edit screen
function remove_add_new_media_from_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu( 'new-media' );
}
add_action( 'wp_before_admin_bar_render', 'remove_add_new_media_from_admin_bar' );
