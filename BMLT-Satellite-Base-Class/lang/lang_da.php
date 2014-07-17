<?php
// Danmark
/****************************************************************************************//**
*   \file   lang_da.php                                                                     *
*                                                                                           *
*   \brief  This file contains Danish localizations.                                       *
*   \version 3.0.23                                                                         *
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
    static  $local_options_title = 'Indstillinger BMLT';    ///< This is the title that is displayed over the options.
    static  $local_menu_string = 'BMLT Indstillinger';                            ///< The name of the menu item.
    static  $local_options_prefix = 'Vælg instilling';                      ///< The string displayed before each number in the options popup.
    static  $local_options_add_new = 'Tilføj Ny Indstilling';                   ///< The string displayed in the "Add New Option" button.
    static  $local_options_save = 'Gem Ændring';                           ///< The string displayed in the "Save Changes" button.
    static  $local_options_delete_option = 'Slet Indstilling';           ///< The string displayed in the "Delete Option" button.
    static  $local_options_delete_failure = 'Sletning Af Indstilling Mislykkes.'; ///< The string displayed upon unsuccessful deletion of an option page.
    static  $local_options_create_failure = 'Ny Indstilling Mislykket.'; ///< The string displayed upon unsuccessful creation of an option page.
    static  $local_options_delete_option_confirm = 'Vil du slette denne indstilling ?';    ///< The string displayed in the "Are you sure?" confirm.
    static  $local_options_delete_success = 'Indstilling slettet.';                        ///< The string displayed upon successful deletion of an option page.
    static  $local_options_create_success = 'Indstilling Oprettet.';                        ///< The string displayed upon successful creation of an option page.
    static  $local_options_save_success = 'Indstilling Opdateret.';                        ///< The string displayed upon successful update of an option page.
    static  $local_options_save_failure = 'Indstilling Blev Ikke Opdateret.';                                 ///< The string displayed upon unsuccessful update of an option page.
    static  $local_options_url_bad = 'Denne Rootserver URL Virker Ikke.';                 ///< The string displayed if a root server URI fails to point to a valid root server.
    static  $local_options_access_failure = 'Dette Må Du Ikke...';               ///< This is displayed if a user attempts a no-no.
    static  $local_options_unsaved_message = 'Vil Du Ikke Gemme Først?';   ///< This is displayed if a user attempts to leave a page without saving the options.
    static  $local_options_settings_id_prompt = 'ID For Denne Indstilling ';                              ///< This is so that users can see the ID for the setting.
    static  $local_options_settings_location_checkbox_label = 'Tekstsøgning Instilling med "placering" Afkrydsningsfeltet Til.';                              ///< This is so that users can see the ID for the setting.
    
    /// These are all for the admin page option sheets.
    static  $local_options_name_label = 'Indstillings Navn:';                    ///< The Label for the setting name item.
    static  $local_options_rootserver_label = 'Root Server:';               ///< The Label for the root server item.
    static  $local_options_new_search_label = 'Ny Søgnings URL:';            ///< The Label for the new search item.
    static  $local_options_gkey_label = 'Google Maps API Key:';             ///< The Label for the Google Maps API Key item.
    static  $local_options_no_name_string = 'Indstillings Navn';           ///< The Value to use for a name field for a setting with no name.
    static  $local_options_no_root_server_string = 'Indtast Root Server URL';                               ///< The Value to use for a root with no URL.
    static  $local_options_no_new_search_string = 'Indtast Ny Søge URL'; ///< The Value to use for a new search with no URL.
    static  $local_options_no_gkey_string = 'Indtast Ny API Key';          ///< The Value to use for a new search with no URL.
    static  $local_options_test_server = 'Test';                            ///< This is the title for the "test server" button.
    static  $local_options_test_server_success = 'Version ';                ///< This is a prefix for the version, on success.
    static  $local_options_test_server_failure = 'Denne Root Server URL Er Ikke Gyldig';                       ///< This is a prefix for the version, on failure.
    static  $local_options_test_server_tooltip = 'Test Root Server.';         ///< This is the tooltip text for the "test server" button.
    static  $local_options_map_label = 'Vælg Et Midterpunkt og Zoom Niveau';             ///< The Label for the map.
    static  $local_options_mobile_legend = 'Dette Påvirker Forskellige Visninger (som Kort, Mobil og Avanceret)';  ///< This indicates that the enclosed settings are for the fast mobile lookup.
    static  $local_options_mobile_grace_period_label = 'Tidsfrist inden møde:';     ///< When you do a "later today" search, you get a "Grace Period."
    static  $local_options_mobile_default_duration_label = 'Standard Møde Længde:';     ///< If the meeting has no duration, use this as a default.
    static  $local_options_mobile_time_offset_label = 'Time Offset:';       ///< This may have an offset (time zone difference) from the main server.
    static  $local_options_initial_view = array (                           ///< The list of choices for presentation in the popup.
                                                'kort' => 'Kort', 'tekst' => 'Tekst', 'advanceret_kort' => 'Advanceret Kort', 'advanceret_tekst' => 'Advanceret Tekst'
                                                );
    static  $local_options_initial_view_prompt = 'Standard Søgning:';    ///< The label for the initial view popup.
    static  $local_options_theme_prompt = 'Vælg Farve Tema:';          ///< The label for the theme selection popup.
    static  $local_options_more_styles_label = 'Tilføj CSS Styles til Plugin:';                             ///< The label for the Additional CSS textarea.
    static  $local_options_distance_prompt = 'Distance enheder:';             ///< This is for the distance units select.
    static  $local_options_distance_disclaimer = 'Dette Påvirker Ikke Alle Visninger.';               ///< This tells the admin that only some stuff will be affected.
    static  $local_options_grace_period_disclaimer = 'Minutter Inden Mødet Anses Som Afholdt (For Hurtigsøgning).';      ///< This explains what the grace period means.
    static  $local_options_time_offset_disclaimer = 'Tidsforskel fra Main Server (Sjældent nødvendigt).';            ///< This explains what the time offset means.
    static  $local_options_miles = 'Miles';                                 ///< The string for miles.
    static  $local_options_kilometers = 'Kilometer';                       ///< The string for kilometers.
    static  $local_options_selectLocation_checkbox_text = 'Vis Kun Placeringsfunktioner for Mobil enheder';  ///< The label for the location services checkbox.
    
    static  $local_options_time_format_prompt = 'Tidsformat:';             ///< The label for the time format selection popup.
    static  $local_options_time_format_ampm = 'HH:MM AM/PM';    ///< Ante Meridian Format Option
    static  $local_options_time_format_military = 'HH:MM';           ///< Military Time Format Option
    
    static  $local_options_week_begins_on_prompt = 'Ugen Starter på:';       ///< This is the label for the week start popup menu.

    static  $local_no_root_server = 'Du mangler en root server URL før dette virker.';    ///< Displayed if there was no root server provided.

    /// These are for the actual search displays
    static  $local_select_search = 'Vælg Hurtigsøgning';                 ///< Used for the "filler" in the quick search popup.
    static  $local_clear_search = 'Ryd Søgeresultat';                   ///< Used for the "Clear" item in the quick search popup.
    static  $local_menu_new_search_text = 'Ny Søgning';                     ///< For the new search menu in the old-style BMLT search.
    static  $local_cant_find_meetings_display = 'Ingen Møder Fundet'; ///< When the new map search cannot find any meetings.
    static  $local_single_meeting_tooltip = 'Tryk Her For Mere Info Om Dette Møde.'; ///< The tooltip shown for a single meeting.
    static  $local_gm_link_tooltip = 'Tryk Her For At Se Kort.';    ///< The tooltip shown for the Google Maps link.
    
    /// These are for the change display
    static  $local_change_label_date =  'Ændre Dato:';                     ///< The date when the change was made.
    static  $local_change_label_meeting_name =  'Gruppe Navn:';            ///< The name of the changed meeting.
    static  $local_change_label_service_body_name =  'Område:';       ///< The name of the meeting's Service body.
    static  $local_change_label_admin_name =  'Ændret Af:';                ///< The name of the Service Body Admin that made the change.
    static  $local_change_label_description =  'Beskrivelse:';              ///< The description of the change.
    static  $local_change_date_format = 'F j Y, \a\t g:i A';                ///< The format in which the change date/time is displayed.
    
    /// A simple message for most <noscript> elements. We have a different one for the older interactive search (below).
    static  $local_noscript = 'Uden JAVE Aktiveret virker dette ikke.';             ///< The string displayed in a <noscript> element.
    
    /************************************************************************************//**
    *                   NEW SHORTCODE STATIC DATA MEMBERS (LOCALIZABLE)                     *
    ****************************************************************************************/
    
    /// These are all for the [[bmlt_nouveau]] shortcode.
    static  $local_nouveau_advanced_button = 'Flere Valgmuligheder';                ///< The button name for the advanced search in the nouveau search.
    static  $local_nouveau_map_button = 'Søg Via Kort';    ///< The button name for the map search in the nouveau search.
    static  $local_nouveau_text_button = 'Søg Via Tekst';   ///< The button name for the text search in the nouveau search.
    static  $local_nouveau_text_go_button = 'Søg';                           ///< The button name for the "GO" button in the text search in the nouveau search.
    static  $local_nouveau_text_item_default_text = 'Søge Tekst';    ///< The text that fills an empty text item.
    static  $local_nouveau_text_location_label_text = 'Dette Er Et Sted Eller Postnr';         ///< The label text for the location checkbox.
    static  $local_nouveau_advanced_map_radius_label_1 = 'Find Et Møde Inden for';                ///< The label text for the radius popup.
    static  $local_nouveau_advanced_map_radius_label_2 = 'Fra Markøren.';             ///< The second part of the label.
    static  $local_nouveau_advanced_map_radius_value_auto = 'Automatisk Valgt Radius';   ///< The second part of the label, if Miles
    static  $local_nouveau_advanced_map_radius_value_km = 'Km';                                 ///< The second part of the popup value, if Kilometers
    static  $local_nouveau_advanced_map_radius_value_mi = 'Miles';                              ///< The second part of the popup value, if Miles
    static  $local_nouveau_advanced_weekdays_disclosure_text = 'Udvalgte Hverdage';             ///< The text that is used for the weekdays disclosure link.
    static  $local_nouveau_advanced_formats_disclosure_text = 'Udvalgte Mødetype';               ///< The text that is used for the formats disclosure link.
    static  $local_nouveau_advanced_service_bodies_disclosure_text = 'Vælg Område'; ///< The text that is used for the service bodies disclosure link.
    static  $local_nouveau_select_search_spec_text = 'Ny Søgning';                    ///< The text that is used for the link that tells you to select the search specification.
    static  $local_nouveau_select_search_results_text = 'Se Resultat For Ny Søgning';  ///< The text that is used for the link that tells you to select the search results.
    static  $local_nouveau_cant_find_meetings_display = 'Ingen Møder Fundet';     ///< When the new map search cannot find any meetings.
    static  $local_nouveau_cant_lookup_display = 'Kunne Ikke Beregne Din Placering.';          ///< Displayed if the app is unable to determine the location.
    static  $local_nouveau_display_map_results_text = 'Vis Søgning På Kort';    ///< The text for the display map results disclosure link.
    static  $local_nouveau_display_list_results_text = 'Vis Søgning I Listeform';  ///< The text for the display list results disclosure link.
    static  $local_nouveau_table_header_array = array ( 'Nation', 'State', 'County', 'By', 'Gruppe', 'Dag', 'Starter', 'Sted', 'Mødetype', ' ' );
    static  $local_nouveau_weekday_long_array = array ( 'Søndag', 'Mandag', 'Tirsdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lørdag' );
    static  $local_nouveau_weekday_short_array = array ( 'Søn', 'Man', 'Tirs', 'Ons', 'Tors', 'Fre', 'Lør' );
    
    static  $local_nouveau_meeting_results_count_sprintf_format = '%s Møder Fundet';
    static  $local_nouveau_meeting_results_selection_count_sprintf_format = '%s Udvaglte Møder, Fra %s Møder Fundet';
    static  $local_nouveau_meeting_results_single_selection_count_sprintf_format = '1 Møde Valgt, Fra %s Møder Fundet';
    static  $local_nouveau_single_time_sprintf_format = 'Møder hver %s, Klokken %s, Og Varer %s.';
    static  $local_nouveau_single_duration_sprintf_format_1_hr = '1 Time';
    static  $local_nouveau_single_duration_sprintf_format_mins = '%s Minutter';
    static  $local_nouveau_single_duration_sprintf_format_hrs = '%s Timer';
    static  $local_nouveau_single_duration_sprintf_format_hr_mins = '1 Time Og %s Minutter';
    static  $local_nouveau_single_duration_sprintf_format_hrs_mins = '%s Timer and %s Minutter';
    
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
    
    static  $local_nouveau_location_sprintf_format_wtf = 'Ingen Placering Angivet';
    
    static  $local_nouveau_location_services_set_my_location_advanced_button = 'Sæt Markøren På Din Nuværende Placering';
    static  $local_nouveau_location_services_find_all_meetings_nearby_button = 'Find Et Møde Nær Mig';
    static  $local_nouveau_location_services_find_all_meetings_nearby_later_today_button = 'Find Et Møde Nær Mig Senere Idag';
    static  $local_nouveau_location_services_find_all_meetings_nearby_tomorrow_button = 'Find Et Møde Nær Mig Imorgen ';
    
    static  $local_nouveau_location_sprintf_format_duration_title = 'Dette møde er %s timer Og %s minutter langt.';
    static  $local_nouveau_location_sprintf_format_duration_hour_only_title = 'Dette møde er 1 time langt.';
    static  $local_nouveau_location_sprintf_format_duration_hour_only_and_minutes_title = 'Dette møde er 1 time og %s minutter langt.';
    static  $local_nouveau_location_sprintf_format_duration_hours_only_title = 'Dette møde er %s timer langt.';
    static  $local_nouveau_lookup_location_failed = "Adressesøgning Mislykkedes.";
    static  $local_nouveau_lookup_location_server_error = "Adressesøgning Lykkes Ikke pga Serverfejl .";
    static  $local_nouveau_time_sprintf_format = '%d:%02d %s';
    static  $local_nouveau_am = 'AM';
    static  $local_nouveau_pm = 'PM';
    static  $local_nouveau_noon = 'Middag';
    static  $local_nouveau_midnight = 'Midnat';
    static  $local_nouveau_advanced_map_radius_value_array = "0.25, 0.5, 1.0, 2.0, 5.0, 10.0, 15.0, 20.0, 50.0, 100.0, 200.0";
    static  $local_nouveau_meeting_details_link_title = 'Få mere information om mødet.';
    static  $local_nouveau_meeting_details_map_link_uri_format = 'https://maps.google.com/maps?q=%f,%f';
    static  $local_nouveau_meeting_details_map_link_text = 'Kort Til Mødet';

    static  $local_nouveau_single_formats_label = 'Mødetype:';
    static  $local_nouveau_single_service_body_label = 'Område:';

    static  $local_nouveau_prompt_array = array (
                                                'weekday_tinyint' => 'Dag',
                                                'start_time' => 'Starter',
                                                'duration_time' => 'varighed',
                                                'formats' => 'Mødetype',
                                                'distance_in_miles' => 'Afstand i Miles',
                                                'distance_in_km' => 'Afstand i Kilometer',
                                                'meeting_name' => 'Gruppe',
                                                'location_text' => 'Sted',
                                                'location_street' => 'Adresse',
                                                'location_city_subsection' => 'kommune',
                                                'location_neighborhood' => 'Kvarter',
                                                'location_municipality' => 'By',
                                                'location_sub_province' => 'Land',
                                                'location_province' => 'State',
                                                'location_nation' => 'Nation',
                                                'location_postal_code_1' => 'Postnummer',
                                                'location_info' => 'Yderlige Information'
                                                );
                                                
    /************************************************************************************//**
    *                      STATIC DATA MEMBERS (SPECIAL LOCALIZABLE)                        *
    ****************************************************************************************/
    
    /// This is the only localizable string that is not processed. This is because it contains HTML. However, it is also a "hidden" string that is only displayed when the browser does not support JS.
    static  $local_no_js_warning = '<noscript class="no_js">Denne Funktion Kræver JAVA. Du kan bruge en tilpasset søgning uden JAVA her <a rel="external nofollow" href="###ROOT_SERVER###">main server</a>.</noscript>'; ///< This is the noscript presented for the old-style meeting search. It directs the user to the root server, which will support non-JS browsers.
                                    
    /************************************************************************************//**
    *                       STATIC DATA MEMBERS (NEW MAP LOCALIZABLE)                       *
    ****************************************************************************************/
                                    
    static  $local_new_map_option_1_label = 'Indstillinger til søgning (Ikke Aktiv, Hvis Fanen Ikke Er Åbnet):';
    static  $local_new_map_weekdays = 'Møder er på disse dage:';
    static  $local_new_map_all_weekdays = 'Alle';
    static  $local_new_map_all_weekdays_title = 'Find møder for alle dage.';
    static  $local_new_map_weekdays_title = 'Find møder, som er på ';
    static  $local_new_map_formats = 'Møder med disse mødetyper:';
    static  $local_new_map_all_formats = 'Alle';
    static  $local_new_map_all_formats_title = 'Find møde med alle mødetyper.';
    static  $local_new_map_js_center_marker_current_radius_1 = 'Cirklen er ca ';
    static  $local_new_map_js_center_marker_current_radius_2_km = ' kilometer bred.';
    static  $local_new_map_js_center_marker_current_radius_2_mi = ' miles bred.';
    static  $local_new_map_js_diameter_choices = array ( 0.25, 0.5, 1.0, 1.5, 2.0, 3.0, 5.0, 10.0, 15.0, 20.0, 25.0, 30.0, 50.0, 100.0 );
    static  $local_new_map_js_new_search = 'Ny Søgning';
    static  $local_new_map_option_loc_label = 'Indtast Ny Placering:';
    static  $local_new_map_option_loc_popup_label_1 = 'Søg Møde I';
    static  $local_new_map_option_loc_popup_label_2 = 'Fra Placeringen.';
    static  $local_new_map_option_loc_popup_km = 'Km';
    static  $local_new_map_option_loc_popup_mi = 'Miles';
    static  $local_new_map_option_loc_popup_auto = 'En Automatisk valgt Distance ';
    static  $local_new_map_center_marker_distance_suffix = 'Fra Markøren.';
    static  $local_new_map_center_marker_description = 'Dette Er Din Placering.';
    static  $local_new_map_text_entry_fieldset_label = 'Indtast En Adresse, Postnr eller sted';
    static  $local_new_map_text_entry_default_text = 'Indtast En Adresse, Postnr eller sted';
    static  $local_new_map_location_submit_button_text = 'Søg Møder I Nærheden Af Dette Sted';
    
    /************************************************************************************//**
    *                       STATIC DATA MEMBERS (MOBILE LOCALIZABLE)                        *
    ****************************************************************************************/
    
    /// The units for distance.
    static  $local_mobile_kilometers = 'Kilometer';
    static  $local_mobile_miles = 'Miles';
    static  $local_mobile_distance = 'Afstand';  ///< Distance (the string)
    
    /// The page titles.
    static  $local_mobile_results_page_title = 'Resultater - Hurtigsøgning';
    static  $local_mobile_results_form_title = 'Hurtigsøgning - Nærliggende Møde';
    
    /// The fast GPS lookup links.
    static  $local_GPS_banner = 'Vælg Hurtigsøgning';
    static  $local_GPS_banner_subtext = 'Bookmark disse links, for at finde os hurtigt igen.';
    static  $local_search_all = 'Søg Efter Alle Møder I Nærheden Af Mig.';
    static  $local_search_today = 'Senere Idag';
    static  $local_search_tomorrow = 'I Morgen';
    
    /// The search for an address form.
    static  $local_list_check = 'Hvis du oplever problemer med det interaktive kort, Eller ønsker resultatet i en liste, afkryds dette felt og indtast en adresse.';
    static  $local_search_address_single = 'Søg møder nær en adresse';
    
    /// Used instead of "near my present location."
    static  $local_search_all_address = 'Søg møder nær denne adresse.';
    static  $local_search_submit_button = 'Søg Møde';
    
    /// This is what is entered into the text box.
    static  $local_enter_an_address = 'Indtast en adresse';
    
    /// Error messages.
    static  $local_mobile_fail_no_meetings = 'Ingen Møder Fundet!';
    static  $local_server_fail = 'Serverfejl! Kontakt webmaster';
    static  $local_cant_find_address = 'Kan Ikke Beregne Din Placering Ud Fra Adresseoplysningerne!';
    static  $local_cannot_determine_location = 'Kan Ikke Beregne Din Placering!';
    static  $local_enter_address_alert = 'Intast En Adresse!';
    
    /// The text for the "Map to Meeting" links
    static  $local_map_link = 'Kort til Mødet ';
    
    /// Only used for WML pages
    static  $local_next_card = 'Næste Møde >>';
    static  $local_prev_card = '<< Foregående Møde';
    
    /// Used for the info and list windows.
    static  $local_formats = 'Type';
    static  $local_noon = 'Middag';
    static  $local_midnight = 'Midnat';
    
    /// This array has the weekdays, spelled out. Since weekdays start at 1 (Sunday), we consider 0 to be an error.
    static	$local_weekdays = array ( 'ERROR', 'Søndag', 'Mandag', 'Tirsdag', 'Onsdag', 'Torsdag', 'Fredag', 'Lørdag' );
    static	$local_weekdays_short = array ( 'ERR', 'Søn', 'Man', 'Tirs', 'Ons', 'Tors', 'Fre', 'Lør' );
    };
?>