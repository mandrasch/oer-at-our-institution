<?php
$prefix = 'oershowroom_';
// 2DO: change namespace for translation (is oerbox right now);
$oershowroom_metabox_fields_oer = array(
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
    'name' => esc_html__('Lizenz', 'oerbox'),
    'type' => 'select',
    'desc' => esc_html__('Creative Commons Lizenz, unter welcher die aufgeführten Inhalte lizenziert sind.', 'oerbox'),
    'placeholder' => esc_html__('Lizenz auswählen', 'oerbox'),
    'options' => array(
      'https://creativecommons.org/publicdomain/zero/1.0/' => esc_html__('CC0','oerbox'),
      'https://creativecommons.org/licenses/by/4.0/' => esc_html__('CC BY 4.0', 'oerbox'),
      'https://creativecommons.org/licenses/by-sa/4.0/' => esc_html__('CC BY-ShareAlike 4.0', 'oerbox')
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
    'name'    => esc_html__('Urheber*innen: Weitere Person(en)', 'oerbox'),
    'desc' => esc_html__('Personen, welche bei Nachnutzung genannt werden sollen und hier nur einmalig eingetragen werden, d.h. nicht im Autor:innenverzeichnis mit Profilbild bereits erfasst sind. Bereits erfasste Personen bitte über die Auswahl "Autor:innen aus Autorenverzeichnis auswählen"', 'oerbox'),
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
    'name'    => esc_html__('Urheber: Organisation(en)', 'oerbox'),
    'desc' => esc_html__('Organisation, Institution, Projekt, welches bei Nachnutzung genannt werden soll.', 'oerbox'),
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
    'name' => esc_html__('Erstellungsjahr', 'oerbox'),
    'placeholder' => esc_html__('2019', 'oerbox')
  ),
  array(
    'id' => $prefix . 'subject_area',
    'name' => esc_html__('Fachbereich', 'oerbox'),
    'type' => 'select',
    'placeholder' => esc_html__('Fachbereich auswählen', 'oerbox'),
    'options' => array(
      'agrar_forst' => esc_html__('Agrar-/Forstwissenschaften', 'oerbox'),
      'gesellschaft_sowi' => esc_html__('Gesellschafts- und Sozialwissenschaften', 'oerbox'),
      'ingenieur' => esc_html__('Ingenieurwissenschaften', 'oerbox'),
      'kunst_musik_design' => esc_html__('Kunst, Musik, Design', 'oerbox'),
      'lehramt' => esc_html__('Lehramt', 'oerbox'),
      'mathe_nawi' => esc_html__('Mathematik, Naturwissenschaften', 'oerbox'),
      'medizin_gesundheit' => esc_html__('Medizin, Gesundheitswissenschaften', 'oerbox'),
      'oeffentliche_verwaltung' => esc_html__('Öffentliche Verwaltung', 'oerbox'),
      'sprach_kultur' => esc_html__('Sprach- und Kulturwissenschaften', 'oerbox'),
      'wirtschaft_recht' => esc_html__('Wirtschaftswissenschaften, Rechtswissenschaften', 'oerbox'),
    ),
  ),
  array(
    'id' => $prefix . 'type',
    'name' => esc_html__('Material ist/enthält', 'oerbox'),
    'type' => 'checkbox_list',
    'options' => array(
      'Arbeitsblatt' => esc_html__('Arbeitsblatt', 'oerbox'),
      'Audio' => esc_html__('Audio', 'oerbox'),
      'Präsentationsfolien' => esc_html__('Präsentationsfolien', 'oerbox'),
      'Interaktiver Inhalt' => esc_html__('Interaktiver Inhalt', 'oerbox'),
      'Kurs - Einheit/Modul' => esc_html__('Kurs - Einheit/Modul', 'oerbox'),
      'Kurs - mehrwöchig' => esc_html__('Kurs - mehrwöchig', 'oerbox'),
      'Podcast' => esc_html__('Podcast', 'oerbox'),
      'Simulation' => esc_html__('Simulation', 'oerbox'),
      'Textbook/E-Book' => esc_html__('Textbook/E-Book', 'oerbox'),
      'Unterricht-/Seminarverlauf' => esc_html__('Unterricht-/Seminarverlauf', 'oerbox'),
      'Übungsaufgaben/Assessment' => esc_html__('Übungsaufgaben/Assessment', 'oerbox'),
      'Video' => esc_html__('Video', 'oerbox'),
    )
  ),
  array(
    'id' => $prefix . 'technical_tools_formats',
    'name' => esc_html__('Technisches Format', 'oerbox'),
    'type' => 'checkbox_list',
    'desc' => esc_html__('Welche technischen Formate werden bereitgestellt?', 'oerbox'),
    'options' => array(
      'h5p' => esc_html__('h5p', 'oerbox'),
      'Microsoft Office' => esc_html__('Microsoft Office', 'oerbox'),
      'Open/Libre Office' => esc_html__('Open/Libre Office', 'oerbox'),
      'Google Docs' => esc_html__('Google Docs', 'oerbox'),
      'PDF' => esc_html__('PDF', 'oerbox'),
      'Apple Keynote/Pages' => esc_html__('Apple Keynote/Pages', 'oerbox'),
      'Pressbooks' => esc_html__('Pressbooks', 'oerbox'),
      'Reveal-Präsentation' => esc_html__('Reveal-Präsentation', 'oerbox'),
      'Static Site Generator' => esc_html__('Static Site Generator', 'oerbox'),
      'Markdown' => esc_html__('Markdown', 'oerbox'),
      'Moodle (LMS)' => esc_html__('Moodle (LMS)', 'oerbox'),
      'ILIAS (LMS)' => esc_html__('ILIAS (LMS)', 'oerbox'),
      'Slidewiki' => esc_html__('Slidewiki', 'oerbox'),
      'SCORM' => esc_html__('SCORM', 'oerbox')
    ),
  )
);
