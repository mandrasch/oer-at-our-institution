<?php
/**
* Plugin Name: OER showroom
* Plugin URI: https://oerhoernchen.de
* Description: Enpowers faculties or schools to publish their Open Educational resources in the open web (with schema.org metadata and machine readable license support)
* Version: 0.1
* Author: Matthias Andrasch
* Author URI: https://oerhoernchen.de
* License: CC0
*/

// please see information regarding user roles

// 2DO: add custom fields as column? http://localhost/wordpress/pods/wp-admin/upload.php
// 2DO: add files to sitemap.xml?
// 2DO: custom field: show_oer box at end of article content?
// 2DO: get h5p metadata?
// 2DO: check out http://wordpress.org/extend/plugins/image-source-control-isc/ as well?
// 2DO: checkout https://de.wordpress.org/plugins/featured-image-caption/
// 2DO: Perfomance: Don't set permissions on every page load, what is a better way?

/*
*
*
*/


include(plugin_dir_path(__FILE__).'oer-showroom_metabox_fields_oerauthor.php');















  // meta_box











  // https://wpvip.com/documentation/add-guest-bylines-to-your-content-with-co-authors-plus/#incorporating-new-profile-fields
  /**
  * Add a "Google Plus" field to Co-Authors Plus Guest Author
  */
  add_filter('coauthors_guest_author_fields', 'capx_filter_guest_author_fields', 10, 2);
  function capx_filter_guest_author_fields($fields_to_return, $groups)
  {


    // 2DO: why check this?
      if (in_array('all', $groups) || in_array('contact-info', $groups)) {
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


// 2DO: let contributors add/change guest authors
// box is only shown with edit_others_post permission
// this has to be only allowed in edit screen and based on their authorship
// e.g. with https://wordpress.stackexchange.com/questions/53230/temporary-capability-for-current-user-can/53234 ?
