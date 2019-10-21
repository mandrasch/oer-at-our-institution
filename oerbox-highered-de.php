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
      'id'      => $prefix . 'creator_persons',
      'name'    => esc_html__( 'Urheber*innen: Person(en)', 'oerbox' ),
      'desc' => esc_html__( 'Personen, welche bei Nachnutzung genannt werden sollen. (siehe TULLU-Regel)', 'oerbox' ),
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
  'context' => 'advanced',
  'priority' => 'default',
  'autosave' => 'true',
  'fields' => $fields
);

return $meta_boxes;
}
add_filter( 'rwmb_meta_boxes', 'oerbox_get_meta_box' );


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
