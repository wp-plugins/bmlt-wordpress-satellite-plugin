<?php
// English
/****************************************************************************************//**
*   \file   lang_de.php                                                                     *
*                                                                                           *
*   \brief  This file contains English localizations.                                       *
*   \version 3.0.25                                                                         *
*                                                                                           *
*   This file is part of the BMLT Common Satellite Base Class Project. The project GitHub   *
*   page is available here: https://github.com/MAGSHARE/BMLT-Common-CMS-Plugin-Class        *
*                                                                                           *
*   This file is part of the Basic Meeting List Toolbox (BMLT).                             *
*                                                                                           *
*   Find out more at: http://bmlt.magshare.org                                              *
*                                                                                           *
*   BMLT is free software: you can redistribute it and/or modify                            *
*   it under the terms of the GNU General Public License as published by                    *
*   the Free Software Foundation, either version 3 of the License, or                       *
*   (at your option) any later version.                                                     *
*                                                                                           *
*   BMLT is distributed in the hope that it will be useful,                                 *
*   but WITHOUT ANY WARRANTY; without even the implied warranty of                          *
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                           *
*   GNU General Public License for more details.                                            *
*                                                                                           *
*   You should have received a copy of the GNU General Public License                       *
*   along with this code.  If not, see <http://www.gnu.org/licenses/>.                      *
********************************************************************************************/

class BMLT_Localized_BaseClass
    {
    /************************************************************************************//**
    *                           STATIC DATA MEMBERS (LOCALIZABLE)                           *
    ****************************************************************************************/
    
    /// These are all for the admin pages.
    static  $local_options_title = 'Basic Meeting List Toolbox Einstellungen';    ///< This is the title that is displayed over the options.
    static  $local_menu_string = 'BMLT Einstellungen';                            ///< The name of the menu item.
    static  $local_options_prefix = 'Einstellungen auswählen ';                      ///< The string displayed before each number in the options popup.
    static  $local_options_add_new = 'Neue Einstellung hinzufügen';                   ///< The string displayed in the "Add New Option" button.
    static  $local_options_save = 'Speichern';                           ///< The string displayed in the "Save Changes" button.
    static  $local_options_delete_option = 'Diese Einstellungen löschen';           ///< The string displayed in the "Delete Option" button.
    static  $local_options_delete_failure = 'Löschen dieser Einstellungen fehlgeschlagen.'; ///< The string displayed upon unsuccessful deletion of an option page.
    static  $local_options_create_failure = 'Erstellen dieser Einstellungen fehlgeschlagen.'; ///< The string displayed upon unsuccessful creation of an option page.
    static  $local_options_delete_option_confirm = 'Sicher Einstellungen löschen?';    ///< The string displayed in the "Are you sure?" confirm.
    static  $local_options_delete_success = 'Löschen dieser Einstellungen erfolgreich.';                        ///< The string displayed upon successful deletion of an option page.
    static  $local_options_create_success = 'Erstellen dieser Einstellungen erfolgreich.';                        ///< The string displayed upon successful creation of an option page.
    static  $local_options_save_success = 'Ändern dieser Einstellungen erfolgreich.';                        ///< The string displayed upon successful update of an option page.
    static  $local_options_save_failure = 'Ändern dieser Einstellungen nicht erfolgt.';                                 ///< The string displayed upon unsuccessful update of an option page.
    static  $local_options_url_bad = 'This root server URL will not work for this plugin.';                 ///< The string displayed if a root server URI fails to point to a valid root server.
    static  $local_options_access_failure = 'Keine Berechtigung zu dieser Operation.';               ///< This is displayed if a user attempts a no-no.
    static  $local_options_unsaved_message = 'Es gibt ungespeicherte Änderungen. Verlassen ohne Speichern?';   ///< This is displayed if a user attempts to leave a page without saving the options.
    static  $local_options_settings_id_prompt = 'Die ID dieser Einstellungen ist ';                              ///< This is so that users can see the ID for the setting.
    static  $local_options_settings_location_checkbox_label = 'Die Textsuche beginnt mit "Ort" Checkbox an.';                              ///< This is so that users can see the ID for the setting.
    
    /// These are all for the admin page option sheets.
    static  $local_options_name_label = 'Name der Einstellungen:';                    ///< The Label for the setting name item.
    static  $local_options_rootserver_label = 'Root Server:';               ///< The Label for the root server item.
    static  $local_options_new_search_label = 'URL für neue Suche:';            ///< The Label for the new search item.
    static  $local_options_gkey_label = 'Google Maps API Key:';             ///< The Label for the Google Maps API Key item.
    static  $local_options_no_name_string = 'Name der Einstellungen hinzufügen';           ///< The Value to use for a name field for a setting with no name.
    static  $local_options_no_root_server_string = 'Root Server URL eintragen';                               ///< The Value to use for a root with no URL.
    static  $local_options_no_new_search_string = 'URL für neue Suche eintragen'; ///< The Value to use for a new search with no URL.
    static  $local_options_no_gkey_string = 'Enter a New API Key';          ///< The Value to use for a new search with no URL.
    static  $local_options_test_server = 'Test';                            ///< This is the title for the "test server" button.
    static  $local_options_test_server_success = 'Version ';                ///< This is a prefix for the version, on success.
    static  $local_options_test_server_failure = 'Diese Root Server URL ist unültig.';                       ///< This is a prefix for the version, on failure.
    static  $local_options_test_server_tooltip = 'Dieses testet den root server, um zu sehen ob er OK ist.';         ///< This is the tooltip text for the "test server" button.
    static  $local_options_map_label = 'Wähle einen Mittelpunkt und Zoom Level der Kartenanzeige';             ///< The Label for the map.
    static  $local_options_mobile_legend = 'Dies beeinflusst die Various Interactive Searches (wie Map, Mobile und Advanced)';  ///< This indicates that the enclosed settings are for the fast mobile lookup.
    static  $local_options_mobile_grace_period_label = 'Frist:';     ///< When you do a "later today" search, you get a "Grace Period."
    static  $local_options_mobile_default_duration_label = 'Standart Meetingsdauer:';     ///< If the meeting has no duration, use this as a default.
    static  $local_options_mobile_time_offset_label = 'Zeitverschiebung:';       ///< This may have an offset (time zone difference) from the main server.
    static  $local_options_initial_view = array (                           ///< The list of choices for presentation in the popup.
                                                'map' => 'karte', 'text' => 'Text', 'advanced_map' => 'Erweiterte karte', 'advanced_text' => 'Erweiterter Text'
                                                );
    static  $local_options_initial_view_prompt = 'Anfänglicher Suchtyp:';    ///< The label for the initial view popup.
    static  $local_options_theme_prompt = 'Wähle ein Farbschema:';          ///< The label for the theme selection popup.
    static  $local_options_more_styles_label = 'Füge CSS Styles zum Plugin hinzu:';                             ///< The label for the Additional CSS textarea.
    static  $local_options_distance_prompt = 'Entfernungseinheit:';             ///< This is for the distance units select.
    static  $local_options_distance_disclaimer = 'Dies wird nicht alle Anzeigen beeinflussen.';               ///< This tells the admin that only some stuff will be affected.
    static  $local_options_grace_period_disclaimer = 'Verstrichene Minuten, bevor ein Meeting als "vergangen" angesehen wird (Für schnelle Suche nach Begriffen).';      ///< This explains what the grace period means.
    static  $local_options_time_offset_disclaimer = 'Stunden an Unterschied zum Main Server (Dies ist meistens nicht erforderlich).';            ///< This explains what the time offset means.
    static  $local_options_miles = 'Milen';                                 ///< The string for miles.
    static  $local_options_kilometers = 'Kilometer';                       ///< The string for kilometers.
    static  $local_options_selectLocation_checkbox_text = 'Only Display Location Services for Mobile Devices';  ///< The label for the location services checkbox.
    
    static  $local_options_time_format_prompt = 'Zeit Format:';             ///< The label for the time format selection popup.
    static  $local_options_time_format_ampm = 'Ante Meridian (HH:MM AM/PM)';    ///< Ante Meridian Format Option
    static  $local_options_time_format_military = 'Military (HH:MM)';           ///< Military Time Format Option
    
    static  $local_options_week_begins_on_prompt = 'Wochen beginnen am:';       ///< This is the label for the week start popup menu.

    static  $local_no_root_server = 'Damit das funktioniert, muss man eine root server URI angeben.';    ///< Displayed if there was no root server provided.

    /// These are for the actual search displays
    static  $local_select_search = 'Eine achnelle Suche auswählen';                 ///< Used for the "filler" in the quick search popup.
    static  $local_clear_search = 'Suchergebnisse zurücksetzen';                   ///< Used for the "Clear" item in the quick search popup.
    static  $local_menu_new_search_text = 'Neue Suche';                     ///< For the new search menu in the old-style BMLT search.
    static  $local_cant_find_meetings_display = 'Keine Meetings in dieser Suche gefunden'; ///< When the new map search cannot find any meetings.
    static  $local_single_meeting_tooltip = 'Folge diesem Link zu Details zu diesem Meeting.'; ///< The tooltip shown for a single meeting.
    static  $local_gm_link_tooltip = 'Folge diesem Link um zu einer Google Maps Location dieses Meetings zu gelangen.';    ///< The tooltip shown for the Google Maps link.
    
    /// These are for the change display
    static  $local_change_label_date =  'Datum Ändern:';                     ///< The date when the change was made.
    static  $local_change_label_meeting_name =  'Meetingsname:';            ///< The name of the changed meeting.
    static  $local_change_label_service_body_name =  'Service Body:';       ///< The name of the meeting's Service body.
    static  $local_change_label_admin_name =  'Geändert von:';                ///< The name of the Service Body Admin that made the change.
    static  $local_change_label_description =  'Beschreibung:';              ///< The description of the change.
    static  $local_change_date_format = 'G:i, j.n.Y';                ///< The format in which the change date/time is displayed.
    
    /// A simple message for most <noscript> elements. We have a different one for the older interactive search (below).
    static  $local_noscript = 'Dies funktioniert nicht, denn JavaScript ist nicht aktiviert.';             ///< The string displayed in a <noscript> element.
    
    /************************************************************************************//**
    *                   NEW SHORTCODE STATIC DATA MEMBERS (LOCALIZABLE)                     *
    ****************************************************************************************/
    
    /// These are all for the [[bmlt_nouveau]] shortcode.
    static  $local_nouveau_advanced_button = 'Erweitert';                ///< The button name for the advanced search in the nouveau search.
    static  $local_nouveau_map_button = 'Kartensuche anstatt Textsuche';    ///< The button name for the map search in the nouveau search.
    static  $local_nouveau_text_button = 'Textsuche anstatt Kartensuche';   ///< The button name for the text search in the nouveau search.
    static  $local_nouveau_text_go_button = 'Los';                           ///< The button name for the "GO" button in the text search in the nouveau search.
    static  $local_nouveau_text_item_default_text = 'Text zum Suchen eingeben';    ///< The text that fills an empty text item.
    static  $local_nouveau_text_location_label_text = 'Dies ist ein Ort oder eine PLZ';         ///< The label text for the location checkbox.
    static  $local_nouveau_advanced_map_radius_label_1 = 'Zeige Meetings innerhalb von';                ///< The label text for the radius popup.
    static  $local_nouveau_advanced_map_radius_label_2 = 'der Marker-Position.';             ///< The second part of the label.
    static  $local_nouveau_advanced_map_radius_value_auto = 'Ein automatisch gewählter Radius';   ///< The second part of the label, if Miles
    static  $local_nouveau_advanced_map_radius_value_km = 'Km';                                 ///< The second part of the popup value, if Kilometers
    static  $local_nouveau_advanced_map_radius_value_mi = 'Milen';                              ///< The second part of the popup value, if Miles
    static  $local_nouveau_advanced_weekdays_disclosure_text = 'Gewählte Wochentage';             ///< The text that is used for the weekdays disclosure link.
    static  $local_nouveau_advanced_formats_disclosure_text = 'Gewählte Formate';               ///< The text that is used for the formats disclosure link.
    static  $local_nouveau_advanced_service_bodies_disclosure_text = 'Gewählte Service Bodies'; ///< The text that is used for the service bodies disclosure link.
    static  $local_nouveau_select_search_spec_text = 'Neue Suche definieren';                    ///< The text that is used for the link that tells you to select the search specification.
    static  $local_nouveau_select_search_results_text = 'Zeige Ergebnisse der letzten Suche';  ///< The text that is used for the link that tells you to select the search results.
    static  $local_nouveau_cant_find_meetings_display = 'Keine Meetings in dieser Suche gefunden';     ///< When the new map search cannot find any meetings.
    static  $local_nouveau_cant_lookup_display = 'Ort nicht bestimmbar.';          ///< Displayed if the app is unable to determine the location.
    static  $local_nouveau_display_map_results_text = 'Zeige Suchergebnisse in Karte';    ///< The text for the display map results disclosure link.
    static  $local_nouveau_display_list_results_text = 'Zeige Suchergebnisse als Liste';  ///< The text for the display list results disclosure link.
    static  $local_nouveau_table_header_array = array ( 'Nation', 'Land', 'Bundesland', 'Stadt', 'Meetingsname', 'Wochentag', 'Start', 'Institution', 'Format', ' ' );
    static  $local_nouveau_weekday_long_array = array ( 'Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag' );
    static  $local_nouveau_weekday_short_array = array ( 'So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa' );
    
    static  $local_nouveau_meeting_results_count_sprintf_format = '%s Meetings gefunden';
    static  $local_nouveau_meeting_results_selection_count_sprintf_format = '%s Meetings ausgewählt, von %s gefundenen Meetings';
    static  $local_nouveau_meeting_results_single_selection_count_sprintf_format = '1 Meetings ausgewählt, von %s gefundenen Meetings';
    static  $local_nouveau_single_time_sprintf_format = 'Meeting findet jeden %s, um %s, und dauert %s.';
    static  $local_nouveau_single_duration_sprintf_format_1_hr = '1 Stunde';
    static  $local_nouveau_single_duration_sprintf_format_mins = '%s Minuten';
    static  $local_nouveau_single_duration_sprintf_format_hrs = '%s Stunden';
    static  $local_nouveau_single_duration_sprintf_format_hr_mins = '1 Stunde und %s Minuten';
    static  $local_nouveau_single_duration_sprintf_format_hrs_mins = '%s Stunden und %s minuten';
    
    /// These are all variants of the text that explains the location of a single meeting (Details View).
    static  $local_nouveau_location_sprintf_format_loc_street_info = '%s, %s (%s)';
    static  $local_nouveau_location_sprintf_format_loc_street = '%s, %s';
    static  $local_nouveau_location_sprintf_format_street_info = '%s (%s)';
    static  $local_nouveau_location_sprintf_format_loc_info = '%s (%s)';
    static  $local_nouveau_location_sprintf_format_street = '%s';
    static  $local_nouveau_location_sprintf_format_loc = '%s';
    
    static  $local_nouveau_location_sprintf_format_single_loc_street_info_town_province_zip = '%s, %s (%s), %s, %s %s';
    static  $local_nouveau_location_sprintf_format_single_loc_street_town_province_zip = '%s, %s, %s, %s %s';
    static  $local_nouveau_location_sprintf_format_single_street_info_town_province_zip = '%s (%s), %s, %s %s';
    static  $local_nouveau_location_sprintf_format_single_loc_info_town_province_zip = '%s (%s), %s, %s %s';
    static  $local_nouveau_location_sprintf_format_single_street_town_province_zip = '%s, %s, %s %s';
    static  $local_nouveau_location_sprintf_format_single_loc_town_province_zip = '%s, %s, %s %s';
    
    static  $local_nouveau_location_sprintf_format_single_loc_street_info_town_province = '%s, %s (%s), %s %s';
    static  $local_nouveau_location_sprintf_format_single_loc_street_town_province = '%s, %s, %s, %s';
    static  $local_nouveau_location_sprintf_format_single_street_info_town_province = '%s (%s), %s %s';
    static  $local_nouveau_location_sprintf_format_single_loc_info_town_province = '%s (%s), %s %s';
    static  $local_nouveau_location_sprintf_format_single_street_town_province = '%s, %s %s';
    static  $local_nouveau_location_sprintf_format_single_loc_town_province = '%s, %s %s';
    
    static  $local_nouveau_location_sprintf_format_single_loc_street_info_town_zip = '%s, %s (%s), %s %s';
    static  $local_nouveau_location_sprintf_format_single_loc_street_town_zip = '%s, %s, %s %s';
    static  $local_nouveau_location_sprintf_format_single_street_info_town_zip = '%s (%s), %s %s';
    static  $local_nouveau_location_sprintf_format_single_loc_info_town_zip = '%s (%s), %s %s';
    static  $local_nouveau_location_sprintf_format_single_street_town_zip = '%s, %s %s';
    static  $local_nouveau_location_sprintf_format_single_loc_town_zip = '%s, %s %s';
    
    static  $local_nouveau_location_sprintf_format_single_loc_street_info_province_zip = '%s, %s (%s), %s, %s';
    static  $local_nouveau_location_sprintf_format_single_loc_street_province_zip = '%s, %s, %s, %s';
    static  $local_nouveau_location_sprintf_format_single_street_info_province_zip = '%s (%s), %s, %s';
    static  $local_nouveau_location_sprintf_format_single_loc_info_province_zip = '%s (%s), %s, %s';
    static  $local_nouveau_location_sprintf_format_single_street_province_zip = '%s, %s, %s';
    static  $local_nouveau_location_sprintf_format_single_loc_province_zip = '%s, %s, %s';
    
    static  $local_nouveau_location_sprintf_format_single_loc_street_info_province = '%s, %s (%s), %s';
    static  $local_nouveau_location_sprintf_format_single_loc_street_province = '%s, %s, %s';
    static  $local_nouveau_location_sprintf_format_single_street_info_province = '%s (%s), %s';
    static  $local_nouveau_location_sprintf_format_single_loc_info_province = '%s (%s), %s';
    static  $local_nouveau_location_sprintf_format_single_street_province = '%s, %s';
    static  $local_nouveau_location_sprintf_format_single_loc_province = '%s, %s';
    
    static  $local_nouveau_location_sprintf_format_single_loc_street_info_zip = '%s, %s (%s), %s';
    static  $local_nouveau_location_sprintf_format_single_loc_street_zip = '%s, %s, %s';
    static  $local_nouveau_location_sprintf_format_single_street_info_zip = '%s (%s), %s';
    static  $local_nouveau_location_sprintf_format_single_loc_info_zip = '%s (%s), %s';
    static  $local_nouveau_location_sprintf_format_single_street_zip = '%s, %s';
    static  $local_nouveau_location_sprintf_format_single_loc_zip = '%s, %s';
    
    static  $local_nouveau_location_sprintf_format_single_loc_street_info = '%s, %s (%s)';
    static  $local_nouveau_location_sprintf_format_single_loc_street = '%s, %s,';
    static  $local_nouveau_location_sprintf_format_single_street_info = '%s (%s)';
    static  $local_nouveau_location_sprintf_format_single_loc_info = '%s (%s)';
    static  $local_nouveau_location_sprintf_format_single_street = '%s';
    static  $local_nouveau_location_sprintf_format_single_loc = '%s';
    
    static  $local_nouveau_location_sprintf_format_wtf = 'Keine Position angegeben';
    
    static  $local_nouveau_location_services_set_my_location_advanced_button = 'Setze den Marker auf meine aktuelle Position';
    static  $local_nouveau_location_services_find_all_meetings_nearby_button = 'Finde Meetings in meiner Nähe';
    static  $local_nouveau_location_services_find_all_meetings_nearby_later_today_button = 'Finde Meetings später heute';
    static  $local_nouveau_location_services_find_all_meetings_nearby_tomorrow_button = 'Finde Meetings in meiner Nähe morgen';
    
    static  $local_nouveau_location_sprintf_format_duration_title = 'Dieses Meeting dauert %s Stunden und %s Minuten.';
    static  $local_nouveau_location_sprintf_format_duration_hour_only_title = 'Dieses Meeting dauert 1 Stunde.';
    static  $local_nouveau_location_sprintf_format_duration_hour_only_and_minutes_title = 'Dieses Meeting dauert 1 Stunde und %s Minuten.';
    static  $local_nouveau_location_sprintf_format_duration_hours_only_title = 'Dieses Meeting dauert %s Stunden.';
    static  $local_nouveau_lookup_location_failed = "Die Suche nach Adresse wurde nicht erfolgreich durchgeführt.";
    static  $local_nouveau_lookup_location_server_error = "Die Suche nach Adresse wurde wegen eines Serverfehlers nicht erfolgreich durchgeführt.";
    static  $local_nouveau_time_sprintf_format = '%d:%02d %s';
    static  $local_nouveau_am = 'AM';
    static  $local_nouveau_pm = 'PM';
    static  $local_nouveau_noon = '12:00';
    static  $local_nouveau_midnight = '00:00';
    static  $local_nouveau_advanced_map_radius_value_array = "0.25, 0.5, 1.0, 2.0, 5.0, 10.0, 15.0, 20.0, 50.0, 100.0, 200.0";
    static  $local_nouveau_meeting_details_link_title = 'Mehr Details zu diesem Meeting.';
    static  $local_nouveau_meeting_details_map_link_uri_format = 'https://maps.google.com/maps?q=%f,%f';
    static  $local_nouveau_meeting_details_map_link_text = 'Karte zum Meeting';

    static  $local_nouveau_single_formats_label = 'Meetings-Formate:';
    static  $local_nouveau_single_service_body_label = 'Service Body:';

    static  $local_nouveau_prompt_array = array (
                                                'weekday_tinyint' => 'Wochentag',
                                                'start_time' => 'Anfangszeit',
                                                'duration_time' => 'Dauer des Meetings',
                                                'formats' => 'Format',
                                                'distance_in_miles' => 'Entfernung in Meilen',
                                                'distance_in_km' => 'Entfernung In Kilometern',
                                                'meeting_name' => 'Meetingsname',
                                                'location_text' => 'Institution',
                                                'location_street' => 'Straße, Nr',
                                                'location_city_subsection' => 'Stadtteil',
                                                'location_neighborhood' => 'Nachbarschaft',
                                                'location_municipality' => 'Stadt',
                                                'location_sub_province' => 'Landkreis',
                                                'location_province' => 'Bundesland',
                                                'location_nation' => 'Nation',
                                                'location_postal_code_1' => 'PLZ',
                                                'location_info' => 'Zusätzliche Informationen'
                                                );
                                                
    /************************************************************************************//**
    *                      STATIC DATA MEMBERS (SPECIAL LOCALIZABLE)                        *
    ****************************************************************************************/
    
    /// This is the only localizable string that is not processed. This is because it contains HTML. However, it is also a "hidden" string that is only displayed when the browser does not support JS.
    static  $local_no_js_warning = '<noscript class="no_js">Diese Meetingssuche wird nicht funktionieren, da Ihr Browser kein Javascript unterstützt. Dennoch können Sie <a rel="external nofollow" href="###ROOT_SERVER###">main server</a> zur Suche verwenden.</noscript>'; ///< This is the noscript presented for the old-style meeting search. It directs the user to the root server, which will support non-JS browsers.
                                    
    /************************************************************************************//**
    *                       STATIC DATA MEMBERS (NEW MAP LOCALIZABLE)                       *
    ****************************************************************************************/
                                    
    static  $local_new_map_option_1_label = 'Suchoptionen (Nicht angewandt, wenn dieser Bereich nicht geöffnet ist):';
    static  $local_new_map_weekdays = 'Meetings finden an diesen Wochentagen stadt:';
    static  $local_new_map_all_weekdays = 'Alle';
    static  $local_new_map_all_weekdays_title = 'Finde Meetings an jedem Tag.';
    static  $local_new_map_weekdays_title = 'Finde Meetings, die stattfinden an ';
    static  $local_new_map_formats = 'Meetings haben dieses Format:';
    static  $local_new_map_all_formats = 'Alle';
    static  $local_new_map_all_formats_title = 'Finde Meetings für jedes Format.';
    static  $local_new_map_js_center_marker_current_radius_1 = 'Der Kreis ist etwa ';
    static  $local_new_map_js_center_marker_current_radius_2_km = ' Kilometer weit.';
    static  $local_new_map_js_center_marker_current_radius_2_mi = ' Milen weit.';
    static  $local_new_map_js_diameter_choices = array ( 0.25, 0.5, 1.0, 1.5, 2.0, 3.0, 5.0, 10.0, 15.0, 20.0, 25.0, 30.0, 50.0, 100.0 );
    static  $local_new_map_js_new_search = 'Neue Suche';
    static  $local_new_map_option_loc_label = 'Trage einen Ort ein:';
    static  $local_new_map_option_loc_popup_label_1 = 'Suche nach Meetings im Umkreis von';
    static  $local_new_map_option_loc_popup_label_2 = 'um diese Position.';
    static  $local_new_map_option_loc_popup_km = 'Km';
    static  $local_new_map_option_loc_popup_mi = 'Milen';
    static  $local_new_map_option_loc_popup_auto = 'eine automatisch gewählte Entfernung';
    static  $local_new_map_center_marker_distance_suffix = ' von dem Mittelpunkt-Marker.';
    static  $local_new_map_center_marker_description = 'Dies ist Ihre gewählte Position.';
    static  $local_new_map_text_entry_fieldset_label = 'Füge eine Adresse, PLZ oder Ort ein';
    static  $local_new_map_text_entry_default_text = 'Füge eine Adresse, PLZ oder Ort ein';
    static  $local_new_map_location_submit_button_text = 'Suche nach Meetings in der Nähe dieser Position';
    
    /************************************************************************************//**
    *                       STATIC DATA MEMBERS (MOBILE LOCALIZABLE)                        *
    ****************************************************************************************/
    
    /// The units for distance.
    static  $local_mobile_kilometers = 'Kilometer';
    static  $local_mobile_miles = 'Milen';
    static  $local_mobile_distance = 'Distanz';  ///< Distance (the string)
    
    /// The page titles.
    static  $local_mobile_results_page_title = 'Schnelle Meetingssuche';
    static  $local_mobile_results_form_title = 'Finde schnell Meetings in der Nähe';
    
    /// The fast GPS lookup links.
    static  $local_GPS_banner = 'Wähle eine schnelle Meetingssuche zum Nachschlagen';
    static  $local_GPS_banner_subtext = 'Diese Links für noch schnellere zuküpnftige Suchen merken.';
    static  $local_search_all = 'Suche nach allen Meetings nahe meiner aktuellen Position.';
    static  $local_search_today = 'Später heute';
    static  $local_search_tomorrow = 'Morgen';
    
    /// The search for an address form.
    static  $local_list_check = 'Wenn Sie Probleme mit der Interaktiven Karte haben oder die Ergebnisse als Liste wünschen, haken Sie diese Box an und geben Sie eine Adresse ein.';
    static  $local_search_address_single = 'Suche nach Meetings in der Nähe einer Adresse';
    
    /// Used instead of "near my present location."
    static  $local_search_all_address = 'Suche nach allen Meetings in der Nähe einer Adresse.';
    static  $local_search_submit_button = 'Suche nach Meetings';
    
    /// This is what is entered into the text box.
    static  $local_enter_an_address = 'Geben Sie eine Adresse ein';
    
    /// Error messages.
    static  $local_mobile_fail_no_meetings = 'Keine Meetings gefunden!';
    static  $local_server_fail = 'Die Meetingssuche war nicht erfolgreich wegen eines Serverfehlers!';
    static  $local_cant_find_address = 'Kann die Position der Adressinformation nicht bestimmen!';
    static  $local_cannot_determine_location = 'Kann Ihre Position nicht bestimmen!';
    static  $local_enter_address_alert = 'Geben Sie eine Adresse ein!';
    
    /// The text for the "Map to Meeting" links
    static  $local_map_link = 'Karte zum Meeting';
    
    /// Only used for WML pages
    static  $local_next_card = 'Nächstes Meeting >>';
    static  $local_prev_card = '<< Vorheriges Meeting';
    
    /// Used for the info and list windows.
    static  $local_formats = 'Format';
    static  $local_noon = '12:00';
    static  $local_midnight = '00:00';
    
    /// This array has the weekdays, spelled out. Since weekdays start at 1 (Sunday), we consider 0 to be an error.
    static	$local_weekdays = array ( 'ERROR', 'Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag' );
    static	$local_weekdays_short = array ( 'ERR', 'So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa' );
    };
?>