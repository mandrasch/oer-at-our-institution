<?php
defined('ABSPATH') or die('Are you ok?');

$oerAtOurInstitution_metabox_fields_oer = array(
  array(
    'name'        => esc_html__('Mitarbeitende', 'oeratourinstitution'),
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
  ),
  array(
    'type' => 'divider'
  ),
  array(
    'type' => 'heading',
    'name' => 'OER-Metadaten',
    'desc' => 'OER-Metadaten'
  ),
  array(
    'id' => $prefix . 'license_url',
    'name' => esc_html__('Lizenz', 'oeratourinstitution'),
    'type' => 'select',
    'desc' => esc_html__('Creative Commons Lizenz, unter welcher die aufgeführten Inhalte lizenziert sind.', 'oeratourinstitution'),
    'placeholder' => esc_html__('Lizenz auswählen', 'oeratourinstitution'),
    'options' => array(
      'https://creativecommons.org/publicdomain/zero/1.0/' => esc_html__('CC0', 'oeratourinstitution'),
      'https://creativecommons.org/licenses/by/4.0/' => esc_html__('CC BY 4.0', 'oeratourinstitution'),
      'https://creativecommons.org/licenses/by-sa/4.0/' => esc_html__('CC BY-ShareAlike 4.0', 'oeratourinstitution')
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
    'placeholder' => 'Select an author from authors directory'
  ),
  array(
    'id'      => $prefix . 'creator_additional_persons',
    'name'    => esc_html__('Urheber*innen: Weitere Person(en)', 'oeratourinstitution'),
    'desc' => esc_html__('Personen, welche bei Nachnutzung genannt werden sollen und hier nur einmalig eingetragen werden, d.h. nicht im Autor:innenverzeichnis mit Profilbild bereits erfasst sind. Bereits erfasste Personen bitte über die Auswahl "Autor:innen aus Autorenverzeichnis auswählen"', 'oeratourinstitution'),
    'type'    => 'fieldset_text',
    // Options: array of key => Label for text boxes
    // Note: key is used as key of array of values stored in the database
    'options' => array(
      'givenName'    => 'Vorname',
      'familyName' => 'Nachname',
      'URL'   => 'URL/Identifier'
    ),
    // Is field cloneable?
    'clone' => true
  ),
  array(
    'id'      => $prefix . 'creator_organizations',
    'name'    => esc_html__('Urheber: Organisation(en)', 'oeratourinstitution'),
    'desc' => esc_html__('Organisation, Institution, Projekt, welches bei Nachnutzung genannt werden soll.', 'oeratourinstitution'),
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
  array(
    'id' => $prefix . 'created_year',
    'type' => 'text',
    'name' => esc_html__('Erstellungsjahr', 'oeratourinstitution'),
    'placeholder' => esc_html__('2019', 'oeratourinstitution')
  ),
  array(
    'id' => $prefix . 'subject_area',
    'name' => esc_html__('Fachbereich', 'oeratourinstitution'),
    'type' => 'select',
    'placeholder' => esc_html__('Fachbereich auswählen', 'oeratourinstitution'),
    'options' => array(
      'agrar_forst' => esc_html__('Agrar-/Forstwissenschaften', 'oeratourinstitution'),
      'gesellschaft_sowi' => esc_html__('Gesellschafts- und Sozialwissenschaften', 'oeratourinstitution'),
      'ingenieur' => esc_html__('Ingenieurwissenschaften', 'oeratourinstitution'),
      'kunst_musik_design' => esc_html__('Kunst, Musik, Design', 'oeratourinstitution'),
      'lehramt' => esc_html__('Lehramt', 'oeratourinstitution'),
      'mathe_nawi' => esc_html__('Mathematik, Naturwissenschaften', 'oeratourinstitution'),
      'medizin_gesundheit' => esc_html__('Medizin, Gesundheitswissenschaften', 'oeratourinstitution'),
      'oeffentliche_verwaltung' => esc_html__('Öffentliche Verwaltung', 'oeratourinstitution'),
      'sprach_kultur' => esc_html__('Sprach- und Kulturwissenschaften', 'oeratourinstitution'),
      'wirtschaft_recht' => esc_html__('Wirtschaftswissenschaften, Rechtswissenschaften', 'oeratourinstitution'),
    ),
  ),
  array(
    'id' => $prefix . 'type',
    'name' => esc_html__('Material ist/enthält', 'oeratourinstitution'),
    'type' => 'checkbox_list',
    'options' => array(
      'Arbeitsblatt' => esc_html__('Arbeitsblatt', 'oeratourinstitution'),
      'Audio' => esc_html__('Audio', 'oeratourinstitution'),
      'Präsentationsfolien' => esc_html__('Präsentationsfolien', 'oeratourinstitution'),
      'Interaktiver Inhalt' => esc_html__('Interaktiver Inhalt', 'oeratourinstitution'),
      'Kurs - Einheit/Modul' => esc_html__('Kurs - Einheit/Modul', 'oeratourinstitution'),
      'Kurs - mehrwöchig' => esc_html__('Kurs - mehrwöchig', 'oeratourinstitution'),
      'Podcast' => esc_html__('Podcast', 'oeratourinstitution'),
      'Simulation' => esc_html__('Simulation', 'oeratourinstitution'),
      'Textbook/E-Book' => esc_html__('Textbook/E-Book', 'oeratourinstitution'),
      'Unterricht-/Seminarverlauf' => esc_html__('Unterricht-/Seminarverlauf', 'oeratourinstitution'),
      'Übungsaufgaben/Assessment' => esc_html__('Übungsaufgaben/Assessment', 'oeratourinstitution'),
      'Video' => esc_html__('Video', 'oeratourinstitution'),
    )
  ),
  array(
    'id' => $prefix . 'technical_tools_formats',
    'name' => esc_html__('Technisches Format', 'oeratourinstitution'),
    'type' => 'checkbox_list',
    'desc' => esc_html__('Welche technischen Formate werden bereitgestellt?', 'oeratourinstitution'),
    'options' => array(
      'h5p' => esc_html__('h5p', 'oeratourinstitution'),
      'Microsoft Office' => esc_html__('Microsoft Office', 'oeratourinstitution'),
      'Open/Libre Office' => esc_html__('Open/Libre Office', 'oeratourinstitution'),
      'Google Docs' => esc_html__('Google Docs', 'oeratourinstitution'),
      'PDF' => esc_html__('PDF', 'oeratourinstitution'),
      'Apple Keynote/Pages' => esc_html__('Apple Keynote/Pages', 'oeratourinstitution'),
      'Pressbooks' => esc_html__('Pressbooks', 'oeratourinstitution'),
      'Reveal-Präsentation' => esc_html__('Reveal-Präsentation', 'oeratourinstitution'),
      'Static Site Generator' => esc_html__('Static Site Generator', 'oeratourinstitution'),
      'Markdown' => esc_html__('Markdown', 'oeratourinstitution'),
      'Moodle (LMS)' => esc_html__('Moodle (LMS)', 'oeratourinstitution'),
      'ILIAS (LMS)' => esc_html__('ILIAS (LMS)', 'oeratourinstitution'),
      'Slidewiki' => esc_html__('Slidewiki', 'oeratourinstitution'),
      'SCORM' => esc_html__('SCORM', 'oeratourinstitution')
    ),
  )
);
