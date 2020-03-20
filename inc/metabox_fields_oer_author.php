<?php
defined('ABSPATH') or die('Are you ok?');

$oerAtOurInstitution_metabox_fields_oer_author = array(
// 2DO: how do we deal with author?
array(
  'name'        => esc_html__('Mitarbeitende', 'oerbox'),
  'desc' => esc_html__('Nutzer/innen, die diesen Eintrag mitbearbeiten können. Dies ist nicht die Liste der Urheber/innen, Autor/innen!'),
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
