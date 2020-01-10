<?php


function oerbox_get_meta_box_attachments($meta_boxes){

  $prefix = 'oerbox_';

  // https://docs.metabox.io/fields/post/
  // 2DO: we can hide/show the rest with https://docs.metabox.io/extensions/meta-box-conditional-logic/ - this should only be visible in attachments
  $fields_attachment = array(
    array(
      'name'        => 'Seite/Eintrag',
      'id'          => $prefix .'get_metadata_from_page',
      'type'        => 'post',
      'desc' => esc_html__('Wenn diese Option aktiviert ist durch die Auswahl einer Seite/eines Beitrags, dann müssen die Metadaten (Lizenz, Urheber*innen, etc.) nicht mehr manuell für dieses Medienobjekt eingetragen werden. Sie werden automatisch übernommen wenn die Mediendatei aufgerufen wird.','oerbox'),
      // Post type.
      'post_type'   => array('page','post','material'), // 2DO: custom types!!! (see above, we need to automatically get the custom post types by pods)
      // Field type.
      'field_type'  => 'select_advanced',
      // Placeholder, inherited from `select_advanced` field.
      'placeholder' => 'Seite/Beitrag auswählen',
      // Query arguments. See https://codex.wordpress.org/Class_Reference/WP_Query
      'query_args'  => array(
        // we also want to allow drafts
        //'post_status'    => 'publish',
        'posts_per_page' => - 1,
      )),
    );


      $meta_boxes[] = array(
        'id' => 'oerbox-attachment',
        'title' => esc_html__( 'Metadaten von Seite/Beitrag/Eintrag übernehmen?', 'oerbox'),
        // 2DO: "materials" was hardcoded here, we need to solve this dynamically (post type by pods) -> global setting
        'post_types' => array('attachment'),
        'context' => 'advanced',
        'priority' => 'high',
        'autosave' => 'true',
        'fields' => $fields_attachment
      );

      return $meta_boxes;

      }

      add_filter( 'rwmb_meta_boxes', 'oerbox_get_meta_box_attachments' );


              // 2DO: custom link for media (attachment) metadata

              function oerbox_attachment_fields_to_edit( $fields, $post ) {


                // 2DO: check if ID is correct?

                $post_edit_link = "post.php?action=edit&post=".$post->ID."#oerbox";

                $fields['test-media-item'] = array(
                  'label' => 'OERbox',
                  'input' => 'html',
                  'html' => '<a href="'.$post_edit_link.'" target="_blank">OER-Metadaten bearbeiten</a>',
                  'show_in_edit' => false,
                );

                return $fields;
              }
              add_filter( 'attachment_fields_to_edit', 'oerbox_attachment_fields_to_edit', 10, 2 );


              // 2DO: Box at media attachment page?
              // 2DO: sitemap for attachment page

              // hack for file blocks, we need the id attached as class
              // https://github.com/zgordon/advanced-gutenberg-course/blob/master/lib/block-filters.php
              add_filter( 'render_block', 'oerbox_block_filters', 10, 3);
              function oerbox_block_filters( $block_content, $block ) {

                // 2DO: add is_single?

                // if block is core/file, we attach the id for media metadata handling
                if( in_array($block['blockName'], array("core/file","core/audio","core/video")) && isset($block['attrs']['id'])) {
                  $block_abbr = str_replace("core/","",$block['blockName']);
                  $output = '<!-- oerbox workaround --><span style="display:none;" class="wp-'.$block_abbr.'-'.$block['attrs']['id'].'">';
                  $output .= '</span><!-- eo oerbox workaround -->';
                  $output .= $block_content;
                  return $output;
                }

                return $block_content;
              }


              // 2DO: box at the end of wordpress article

              function oerbox_after_content($content) {

                $oerbox_html = "";


                // find all image blocks (e.g. class wp-image-27)
                // only works with gutenberg editor
                $pattern = '/wp-image-(\d{1,12})/';
                preg_match_all($pattern, $content, $matches);
                // $matches[1] > array of the extracted results
                if(count($matches[1])>0){
                  // add image license info to box
                  foreach($matches[1] as $image_ID){
                    // 2DO: add to list
                    //print_r(get_post_meta($image_ID));
                  }
                }
                //print_r($matches); // 3333

                // 2DO: match all media files
                $pattern = '/wp-file-(\d{1,12})/';
                $pattern = '/wp-audio-(\d{1,12})/';
                $pattern = '/wp-video-(\d{1,12})/';


                // 2DO: include it all in schema.org?
                // (automatically append h5p or image subtypes for general URL?)


                // find all file blocks div.wp-block-file
                // 2DO: unfortunately not with id?
                // 2DO: get url and find out ID?
                //https://github.com/WordPress/gutenberg/issues/6356
                // 2 WATCH: https://javascriptforwp.com/extending-wordpress-blocks/


                // find all file blocks

                // this only gets attachments uploaded while editing the post :(
                //$media_attachments = get_attached_media('');
                // more solutions: https://wordpress.stackexchange.com/questions/288416/how-to-get-all-files-inserted-but-not-attached-to-a-post

                // new gutenberg block option?
                //print_r(parse_blocks($content ));

                $media_attachments = get_posts( array(
                  'post_type' => 'attachment',
                  'posts_per_page' => -1,
                  'post_parent' => get_the_ID(),
                  'exclude'     => get_post_thumbnail_id()
                ) );

                if(count($media_attachments) > 0){
                  $oerbox_html .= "Medieninhalte: ";
                  //var_dump($media_attachments);
                  $attachments_debug = "<pre>".print_r($media_attachments,true)."</pre>";
                  $oerbox_html .= $attachments_debug;
                  $oerbox_html .= "<ul>";
                  foreach($media_attachments as $attachment){
                    $oerbox_html .= "<li>TITLE VON AUTHOR/ORGS, LICENSE/URL, QUELLE</li>";
                  }
                  $oerbox_html .= "</ul>";
                }

                $fullcontent = $content.$oerbox_html;
                return $fullcontent;


                /*if(is_page() || is_single()) {
                $beforecontent = 'This goes before the content. Isn\'t that awesome!';
                $aftercontent = 'And this will come after, so that you can remind them of something, like following you on Facebook for instance.';
                $fullcontent = $beforecontent . $content . $aftercontent;
              } else {
              $fullcontent = $content;
            }
            return $fullcontent;*/
          }
          add_filter('the_content', 'oerbox_after_content');

          // gett all attached media, show license information (we don't want to mess with the caption?)


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
