<?php
/*
Plugin Name: OER@ourInstitution
Description: Let members present their Open Educational Resources created at your institution. Only works with user roles "editors" (regular institutional members / authors of OER) and "admins" (responsible staff members / OER managers). Alpha version, do not use in production.
Version: 0.1
Author: Matthias Andrasch
Author URI: https://oerhoernchen.de
*/

defined('ABSPATH') or die('Are you ok?');

$oerAtOurInstitution = new OerAtOurInstitution();

class OerAtOurInstitution
{
    public function __construct()
    {
        register_activation_hook(__FILE__, array( $this, 'plugin_activate' ));

        register_deactivation_hook(__FILE__, array( $this, 'plugin_deactivation' ));

        if (!is_plugin_active('meta-box/meta-box.php')) {
            add_action('admin_notices', array( $this, 'actionAddErrorPluginInactive' ));
            return;
        }

        // ADMIN
        add_action('init', array( $this, 'actionRegisterCustomPostTypeOerAuthor' ));
        // Permissions:
        // 2DO: better way? not always on admin_init?
        add_action('admin_init', array($this,'actionAllowEditorsAccessToOerAuthorsBackend'));
        add_action('init', array($this,'actionDisallowEditorsToPublish'));
        add_action('init', array($this,'actionDisallowEditorsToAccessAllPosts'));
        add_filter('map_meta_cap', array($this,'filterRestrictEditorsAccessToSpecificPosts'), 10, 4);
        add_action('pre_get_posts', array($this,'actionOnlyShowEditorsAssignedPosts'), 10, 1);
				// Media Library
        add_filter('ajax_query_attachments_args', array( $this, 'filterOnlyShowAttachmentsAttachedToPost' ));
				add_action('wp_before_admin_bar_render', array($this,'actionRemoveMediaMenuItemFromAdminbar'));
				// Meta boxes for posts and oerauthors
        add_filter('rwmb_meta_boxes', array( $this, 'filterCreateMetaBoxForOerPosts' ));
        add_filter('rwmb_meta_boxes', array( $this, 'filterCreateMetaBoxForOerAuthors' ));
				// tweak the admin menu:
				add_filter('user_contactmethods', array($this,'filterRemoveUserProfileContactInfo'));
        add_action('admin_head', array($this,'actionRemoveUserProfileBio'));
				add_action('init', array($this,'actionChangePostsTitle'));

        // PUBLIC
				// ad metadata to html of the posts/oers
        add_action('wp_head', array( $this, 'actionAddMetadataToHead' ));
    }

    public function plugin_activate()
    {
    }

    public function plugin_deactivation()
    {
    }

    /*
    * ADMIN AREA
    * filters, hooks and actions
    */

    // Permissions 1
    // permissions (we only use editor and admin roles, too complicated with wp user role author)
    public function actionAllowEditorsAccessToOerAuthorsBackend()
    {
        $editor = get_role('editor');
        $editor->add_cap('edit_oerauthor');
        $editor->add_cap('edit_oerauthors');
        $editor->remove_cap('publish_oerauthors'); // 2DO: option in backend wheter to allow that? 2DO: restrict this only for author role?
        $editor->add_cap('edit_published_oerauthors');
        $editor->add_cap('edit_others_oerauthors');

        /*'edit_post' => 'edit_course_document',
               'edit_posts' => 'edit_course_documents',
               'edit_others_posts' => 'edit_other_course_documents',
               'publish_posts' => 'publish_course_documents',
               'read_post' => 'read_course_document',
               'read_private_posts' => 'read_private_course_documents',
               'delete_post' => 'delete_course_document'*/

        // allow for admins:
        $editor = get_role('administrator');
        $editor->add_cap('edit_oerauthor');
        $editor->add_cap('edit_oerauthors');
        $editor->add_cap('publish_oerauthors');
        $editor->add_cap('edit_published_oerauthors');
        $editor->add_cap('edit_others_oerauthors');
    }

    // Permissions 2
    // for this proof-of-concept we use editors (e.g. regular institution member) and admins which can perform a last check before an OER is published
    public function actionDisallowEditorsToPublish()
    {
        // https://thewebtier.com/wordpress/prevent-authors-directly-publish-posts-wordpress/

        // $wp_roles is an instance of WP_Roles.
        global $wp_roles;
        $wp_roles->remove_cap('editor', 'publish_posts');
    }

    // Permissions 3
    // Our rights management works a little bit diffrent: Editors are only allowed to certain posts (created by them or where they have been added via metabox user field)
    public function actionDisallowEditorsToAccessAllPosts()
    {
        // $wp_roles is an instance of WP_Roles.
        global $wp_roles;
        // we also remove option to access Pages
        $wp_roles->remove_cap('editor', 'edit_pages');
    }

    // Permissions 4
    // This is our improvised rights management for editors when they are in admin/posts and want to list posts and edit them
    public function filterRestrictEditorsAccessToSpecificPosts($caps, $cap, $user_id, $args)
    {
        // https://wordpress.stackexchange.com/questions/298982/can-i-create-users-that-have-access-to-some-other-users-posts-instead-of-all-o

        /*
        We're messing with capabilities only if 'edit_post'
        is currently checked and the current user has editor role
        but is not the administrator
        */

        if (! in_array($cap, [ 'edit_post' ], true) && ! in_array($cap, [ 'edit_oerauthors' ], true)) {
            return $caps;
        }

        // we only mess with editor user role permissions, not with admin permissions
        if (! user_can($user_id, 'editor') || user_can($user_id, 'administrator')) {
            return $caps;
        }

        /*
        $args[0] holds post ID. $args var is a bit enigmatic, it contains
        different stuff depending on the context and there's almost
        no documentation on that, you've got to trust me on this one :)
        Anyways, if no post ID is set, we bail out and return default capabilities
        */
        if (empty($args[0])) {
            return $caps;
        }

        /*
        You can also make sure that you're restricting access
        to posts only and not pages or other post types
        */
        $post_type = get_post_type($args[0]);
        if ('post' !== $post_type && 'oerauthor' !== $post_type) {
            return $caps;
        }

        // two possible ways for permissions
        // a) user is set as author of post (wordpress standard behaviour)
        // b) user is linked in metabox.io meta field (coeditor_users)

        // check if user is set as author
        $post_author_id = get_post_field('post_author', $args[0]);

        // metabox.io
        // check coeditor_users field
        $coeditor_users = rwmb_meta('coeditor_users', '', $args[0]);

        // if user is listed in coeditor_users or if user is original author of post, we'll allow editing
        if (in_array($user_id, $coeditor_users) || $user_id == $post_author_id) {
            return $caps;
        }

        // finally, in all other cases, we restrict access to this post
        $caps = [ 'do_not_allow' ];
        return $caps;
    }

    // Permissions 5
    // Another part of our improvised rights management: Editors in backend should only get a list of posts/OERs which they are assigned todo
    public function actionOnlyShowEditorsAssignedPosts($query)
    {
        if (is_admin() || empty(get_current_user_id())) {
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

        $query->set('author', get_current_user_id());

        $query->set('meta_key', 'coeditor_users');
        $query->set('meta_value', get_current_user_id());
    }


    // Media Library 1
    // don't allow uploading directly to media library to prevent chaos  (see below), uploading only attached to post allowed in Gutenberg directly
    // https://wpmayor.com/how-to-remove-menu-items-in-admin-depending-on-user-role/
    public function actionRemoveUploadOptionInMediaLibrary()
    {
        global $user_ID;
        // only admins have this option
        if (!current_user_can('administrator')) {
            remove_menu_page('upload.php'); // Media
                remove_menu_page('tools.php'); // Media
        }
    }

    // Media Library 2
    // restrict media uploads/inserting of media to current post (id) to prevent media library chaos
    public function filterOnlyShowAttachmentsAttachedToPost($query)
    {
        /*
        * First small hack: Restrict media library selection to current post to avoid
        * media file chaos, we hook into the ajax loading action when media library
        * is opened by   user. Wordpress has a built-in "attached to post" function already.
        *
        * Principle: 1 wordpress post = 1 license
        *
        *  Source code used, thanks:
        * - https://wordpress.org/plugins/restrict-media-library-access/
        * - https://wordpress.stackexchange.com/questions/351940/how-do-i-detect-in-which-page-ajax-query-attachments-args-is-loaded
        */

        // 2DO: use try & catch to minimize errors?
        // 2DO: wp_get_referer() returns false if nonexistent

        // restrict media attachments only to media attached to this post (id)
        $referer = parse_url(wp_get_referer());
        parse_str($referer['query'], $params);

        if (strpos($referer['path'], "post-new.php") > 0) {
            // just use bogus value, new post does not have an id yet
            $query['post_parent'] = -111;
        }

        if (isset($params['post']) && isset($params['action']) && $params['action'] == 'edit') {
            $query['post_parent'] = (int)$params['post']; // filter by current post id
        }
        return $query;
    }

		// Media Library 3


		// we remove this, media should only be added when in post->add/edit screen
		function actionRemoveMediaMenuItemFromAdminbar()
		{
		    global $wp_admin_bar;
		    $wp_admin_bar->remove_menu('new-media');
		}

    // metabox for OERs
    /* this is the important metabox for posts (below posts) */
    public function filterCreateMetaBoxForOerPosts($meta_boxes)
    {
        // 2DO: better way of doing this?
        include(plugin_dir_path(__FILE__) . 'inc/metabox_fields_oer.php');

        if (!isset($oerAtOurInstitution_metabox_fields_oer)) {
            // 2DO: show error, var comes from include file
        }

        $meta_boxes[] = array(
        'id' => 'oeratourinstitution-metabox-oer1',
        'title' => esc_html__('OERbox Metadaten für dieses Objekt/URL', 'oeratourinstitution'),
        'post_types' => array('post'),
        'context' => 'normal',
        'priority' => 'default',
        'autosave' => 'true',
        // fields retrieved from another file
        'fields' => $oerAtOurInstitution_metabox_fields_oer
        );
        return $meta_boxes;
    }

    // Custom post type for OER authors directory (static)
    public function actionRegisterCustomPostTypeOerAuthor()
    {
        $args = array(
          'label' => esc_html__('OER authors', 'oeratourinstitution'),
          'labels' => array(
            'menu_name' => esc_html__('OER authors', 'oeratourinstitution'),
            'name_admin_bar' => esc_html__('OER author', 'oeratourinstitution'),
            'add_new' => esc_html__('Add new', 'oeratourinstitution'),
            'add_new_item' => esc_html__('Add new OER author', 'oeratourinstitution'),
            'new_item' => esc_html__('New OER author', 'oeratourinstitution'),
            'edit_item' => esc_html__('Edit OER author', 'oeratourinstitution'),
            'view_item' => esc_html__('View OER author', 'oeratourinstitution'),
            'update_item' => esc_html__('Update OER author', 'oeratourinstitution'),
            'all_items' => esc_html__('All OER authors', 'oeratourinstitution'),
            'search_items' => esc_html__('Search OER authors', 'oeratourinstitution'),
            'parent_item_colon' => esc_html__('Parent OER author', 'oeratourinstitution'),
            'not_found' => esc_html__('No OER authors found', 'oeratourinstitution'),
            'not_found_in_trash' => esc_html__('No OER authors found in Trash', 'oeratourinstitution'),
            'name' => esc_html__('OER authors', 'oeratourinstitution'),
            'singular_name' => esc_html__('OER author', 'oeratourinstitution'),
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
          'rewrite' => array('slug' => "oerauthor", 'with_front' => true)
        );
        register_post_type('oerauthor', $args);
    }

    // Metabox for custom post type OERauthors
    public function filterCreateMetaBoxForOerAuthors($meta_boxes)
    {
        include(plugin_dir_path(__FILE__) . 'inc/metabox_fields_oer_author.php');

        if (!isset($oerAtOurInstitution_metabox_fields_oer_author)) {
            // 2DO: show error, var comes from include file
        }



        $meta_boxes[] = array(
                'id' => 'oeratourinstitution-metabox-oer-author1',
                    'title' => esc_html__('Angaben zum/zur OER-Autor/in', 'oeratourinstitution'),
                    'post_types' => array('oerauthor'),
                    'context' => 'normal',
                    'priority' => 'default',
                    'autosave' => 'true',
                    'fields' => $oerAtOurInstitution_metabox_fields_oer_author);

        return $meta_boxes;
    }

    // little tweak: we remove all contact info in admin/user/"edit profile" because this is done in guest author field
    public function filterRemoveUserProfileContactInfo($methods)
    {
        $methods = array();
        return $methods;
    }

    // we remove biographical info as well with a little js trick
    public function actionRemoveUserProfileBio()
    {
        // https://www.majas-lapu-izstrade.lv/how-to-remove-wordpress-admin-profile-page-fields-including-personal-options-biographical-info-website-etc-and-titles-without-js/
        //Remove fields from Admin profile page via JS to hide nickname field which is mandatory

        if (! current_user_can('manage_options')) { // 'update_core' may be more appropriate
            echo '<script type="text/javascript">jQuery(document).ready(function($) {
	        $(\'form#your-profile tr.user-description-wrap\').hide(); // Hide
	        $(\'form#your-profile tr.user-url-wrap\').hide(); // Hide
	        $(\'form#your-profile tr.user-profile-picture\').hide(); // Hideuser-profile-picture
	      });</script>';
        }
    }

		// Change dashboard title "Posts" to "OER"
		function actionChangePostsTitle()
		{

		  // https://wpbeaches.com/change-the-wordpress-post-type-name-to-something-else/
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



    /*
    * PUBLIC DISPLAY
    * filters, hooks and actions
    */

    // add metadata to the head of HTML
    // Add scripts to wp_head()
    public function actionAddMetadataToHead()
    {
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
        $license_url = esc_html(get_post_meta(get_the_ID(), $prefix.'license_url', true));
        echo "<link rel='license' href='{$license_url}' />"; ?>
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


    public function actionAddErrorPluginInactive()
    {
        $class = 'notice notice-error';
        $message = __('Oopsie, OER@our Institution needs another plugin. Please install and activate: ', 'oeratourinstitution');

        printf('<div class="%1$s"><p>%2$s %3$s</p></div>', esc_attr($class), esc_html($message), '<a href="https://wordpress.org/plugins/meta-box/" target="_blank">metabox.io-Plugin</a>');
    }
}
