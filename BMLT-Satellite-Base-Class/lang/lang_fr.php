<?php
// English
/****************************************************************************************//**
*   \file   lang_en.php                                                                     *
*                                                                                           *
*   \brief  This file contains English localizations.                                       *
*   \version 3.0.21                                                                         *
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
    static  $local_options_title = "Options de l'outil de localisation de réunion (BMLT)";    ///< This is the title that is displayed over the options.
    static  $local_menu_string = 'Options de BMLT';                            ///< The name of the menu item.
    static  $local_options_prefix = 'Sélection du paramêtre';                      ///< The string displayed before each number in the options popup.
    static  $local_options_add_new = "Ajout d'un nouveau paramêtre";                   ///< The string displayed in the "Add New Option" button.
    static  $local_options_save = 'Sauvegarder les changements';                           ///< The string displayed in the "Save Changes" button.
    static  $local_options_delete_option = 'Supprimer le paramêtre';           ///< The string displayed in the "Delete Option" button.
    static  $local_options_delete_failure = 'La suppréssion du paramêtre a échoué.'; ///< The string displayed upon unsuccessful deletion of an option page.
    static  $local_options_create_failure = 'La création du paramêtre a échoué.'; ///< The string displayed upon unsuccessful creation of an option page.
    static  $local_options_delete_option_confirm = 'Êtes-vous sûr(e) de supprimer ce paramètre.';    ///< The string displayed in the "Are you sure?" confirm.
    static  $local_options_delete_success = 'Le paramêtre a été supprimer avec succès.';                        ///< The string displayed upon successful deletion of an option page.
    static  $local_options_create_success = 'Le paramêtre a été créer avec succès.';                        ///< The string displayed upon successful creation of an option page.
    static  $local_options_save_success = 'Le paramêtre a été mise à jour avec succès.';                        ///< The string displayed upon successful update of an option page.
    static  $local_options_save_failure = "Le paramêtre n\'a pas été mis à jour.";                                 ///< The string displayed upon unsuccessful update of an option page.
    static  $local_options_url_bad = "L\'URL racine du serveur ne fonctionne pas avec ce plugin.";                 ///< The string displayed if a root server URI fails to point to a valid root server.
    static  $local_options_access_failure = "Vous n\'avez pas l\'authorisation de performer cette opération.";               ///< This is displayed if a user attempts a no-no.
    static  $local_options_unsaved_message = "Vous n\'avez pas sauvegarder les changements. Êtes-vous sûr(e) de quitter sans sauvegarder?";   ///< This is displayed if a user attempts to leave a page without saving the options.
    static  $local_options_settings_id_prompt = 'Le ID de ce paramêtre est ';                              ///< This is so that users can see the ID for the setting.
    static  $local_options_settings_location_checkbox_label = 'La recherche par texte est désactivée si la "Location" est coché.';                              ///< This is so that users can see the ID for the setting.
    
    /// These are all for the admin page option sheets.
    static  $local_options_name_label = 'Nom du paramêtre: ';                    ///< The Label for the setting name item.
    static  $local_options_rootserver_label = 'Racine du serveur: ';               ///< The Label for the root server item.
    static  $local_options_new_search_label = 'Nouvelle recherche URL:';            ///< The Label for the new search item.
    static  $local_options_gkey_label = 'Clé API de Google Map';             ///< The Label for the Google Maps API Key item.
    static  $local_options_no_name_string = 'Entrer le nom du paramètre';           ///< The Value to use for a name field for a setting with no name.
    static  $local_options_no_root_server_string = "Entrer l\'URL racine du serveur";                               ///< The Value to use for a root with no URL.
    static  $local_options_no_new_search_string = "Entrer la nouvelle recherche d\'URL"; ///< The Value to use for a new search with no URL.
    static  $local_options_no_gkey_string = 'Entrer la nouvelle clé API';          ///< The Value to use for a new search with no URL.
    static  $local_options_test_server = 'Test';                            ///< This is the title for the "test server" button.
    static  $local_options_test_server_success = 'Version ';                ///< This is a prefix for the version, on success.
    static  $local_options_test_server_failure = "Cet URL racine du serveur n\'est pas valide.";                       ///< This is a prefix for the version, on failure.
    static  $local_options_test_server_tooltip = 'Ces tests de racine du serveur, voir si tout semble correct.';         ///< This is the tooltip text for the "test server" button.
    static  $local_options_map_label = "Pointer le marqueur dans une zone sur la carte, ensuite se servir du zoom pour obtenir le niveau de l'affichage de la désiré sur la carte.";             ///< The Label for the map.
    static  $local_options_mobile_legend = 'Ceux-ci affectent les diverses recherches interactives (comme la carte, mobile et avancée)';  ///< This indicates that the enclosed settings are for the fast mobile lookup.
    static  $local_options_mobile_grace_period_label = 'Délais:';     ///< When you do a "later today" search, you get a "Grace Period."
    static  $local_options_mobile_default_duration_label = 'Durée de réunions par défaut: ';     ///< If the meeting has no duration, use this as a default.
    static  $local_options_mobile_time_offset_label = 'Écart de temps: ';       ///< This may have an offset (time zone difference) from the main server.
    static  $local_options_initial_view = array (                           ///< The list of choices for presentation in the popup.
                                                'map' => 'Carte', 'text' => 'Texte', 'advanced_map' => 'Carte Avancée', 'advanced_text' => 'Texte Avancé'
                        						);
    static  $local_options_initial_view_prompt = 'Type de recherche innitial: ';    ///< The label for the initial view popup.
    static  $local_options_theme_prompt = 'Choix du thème de couleur';          ///< The label for the theme selection popup.
    static  $local_options_more_styles_label = "Ajout d'une feuille de style CSS au plugin:";                             ///< The label for the Additional CSS textarea.
    static  $local_options_distance_prompt = 'Unités de distance:';             ///< This is for the distance units select.
    static  $local_options_distance_disclaimer = "Ceci n\'affectera pas toutes les affichages.";               ///< This tells the admin that only some stuff will be affected.
    static  $local_options_grace_period_disclaimer = 'Minutes restantes avant sa fin condéré "finie" (pour une recherche rapide).';      ///< This explains what the grace period means.
    static  $local_options_time_offset_disclaimer = 'Heure de différence du serveur principale (Ordinairement non requis).';            ///< This explains what the time offset means.
    static  $local_options_miles = 'Miles';                                 ///< The string for miles.
    static  $local_options_kilometers = 'Kilomètres';                       ///< The string for kilometers.
    static  $local_options_selectLocation_checkbox_text = 'Affichage de localisation disponible pour mobile seulement';  ///< The label for the location services checkbox.

    static  $local_options_time_format_prompt = "Format d'heure:";             ///< The label for the time format selection popup.
    static  $local_options_time_format_ampm = 'Antéméridien (HH:MM AM/PM)';    ///< Ante Meridian Format Option
    static  $local_options_time_format_military = 'Militaire (HH:MM)';           ///< Military Time Format Option

    static  $local_options_week_begins_on_prompt = 'Semaines débutants les:';       ///< This is the label for the week start popup menu.

    static  $local_no_root_server = 'Vous devez vous procurer une URL racine du serveur pour ce service.';    ///< Displayed if there was no root server provided.

    /// These are for the actual search displays
    static  $local_select_search = 'Sélection de recherche rapide';                 ///< Used for the "filler" in the quick search popup.
    static  $local_clear_search = 'Nettoyer les résultats de recherche';                   ///< Used for the "Clear" item in the quick search popup.
    static  $local_menu_new_search_text = 'Nouvelle recherche';                     ///< For the new search menu in the old-style BMLT search.
    static  $local_cant_find_meetings_display = 'Aucune réunion trouvée de cette requête'; ///< When the new map search cannot find any meetings.
    static  $local_single_meeting_tooltip = 'Suivre ce lien pour de plus amples détail à propos de cette réunion.'; ///< The tooltip shown for a single meeting.
    static  $local_gm_link_tooltip = 'Suivre ce lien pour se diriger sur Google Map pour cette réunion.';    ///< The tooltip shown for the Google Maps link.

    /// These are for the change display
    static  $local_change_label_date =  'Changer la date: ';                     ///< The date when the change was made.
    static  $local_change_label_meeting_name =  'Nom de la réunion:';            ///< The name of the changed meeting.
    static  $local_change_label_service_body_name =  'Échelon de structure de service :';       ///< The name of the meeting's Service body.
    static  $local_change_label_admin_name =  'Changer par:';                ///< The name of the Service Body Admin that made the change.
    static  $local_change_label_description =  'Description:';              ///< The description of the change.
    static  $local_change_date_format = 'F j Y, \a\t g:i A';                ///< The format in which the change date/time is displayed.

    /// A simple message for most <noscript> elements. We have a different one for the older interactive search (below).
    static  $local_noscript = 'Ceci ne fonctionne pas, votre console JavaScript de votre navigateur est désactivée.';             ///< The string displayed in a <noscript> element.
    
    /************************************************************************************//**
    *                   NEW SHORTCODE STATIC DATA MEMBERS (LOCALIZABLE)                     *
    ****************************************************************************************/
    
    /// These are all for the [[bmlt_nouveau]] shortcode.
    static  $local_nouveau_advanced_button = "Plus d\'options";                ///< The button name for the advanced search in the nouveau search.
    static  $local_nouveau_map_button = "Recherche en mode carte plutôt qu\' en mode texte";    ///< The button name for the map search in the nouveau search.
    static  $local_nouveau_text_button = "Recherche en mode texte plutôt qu\'en mode carte.";   ///< The button name for the text search in the nouveau search.
    static  $local_nouveau_text_go_button = 'Allez-y!';                           ///< The button name for the "GO" button in the text search in the nouveau search.
    static  $local_nouveau_text_item_default_text = 'Entrer le texte recherché';    ///< The text that fills an empty text item.
    static  $local_nouveau_text_location_label_text = 'Ceci est une localisation ou un code postal';         ///< The label text for the location checkbox.
    static  $local_nouveau_advanced_map_radius_label_1 = 'Recherche de réunions aux alentours';                ///< The label text for the radius popup.
    static  $local_nouveau_advanced_map_radius_label_2 = 'du pointeur de localisation.';             ///< The second part of the label.
    static  $local_nouveau_advanced_map_radius_value_auto = 'Un rayon choisi automatiquement';   ///< The second part of the label, if Miles
    static  $local_nouveau_advanced_map_radius_value_km = 'Km';                                 ///< The second part of the popup value, if Kilometers
    static  $local_nouveau_advanced_map_radius_value_mi = 'Miles';                              ///< The second part of the popup value, if Miles
    static  $local_nouveau_advanced_weekdays_disclosure_text = 'Sélection par journée(s) de semaine';             ///< The text that is used for the weekdays disclosure link.
    static  $local_nouveau_advanced_formats_disclosure_text = 'Sélection par format';               ///< The text that is used for the formats disclosure link.
    static  $local_nouveau_advanced_service_bodies_disclosure_text = 'Sélection par échelons de strucure de services'; ///< The text that is used for the service bodies disclosure link.
    static  $local_nouveau_select_search_spec_text = 'Nouvelle recherche';                    ///< The text that is used for the link that tells you to select the search specification.
    static  $local_nouveau_select_search_results_text = 'Retourner aux résultats de votre dernière recherche';  ///< The text that is used for the link that tells you to select the search results.
    static  $local_nouveau_cant_find_meetings_display = 'Aucune réunion trouvé pour cette recherche';     ///< When the new map search cannot find any meetings.
    static  $local_nouveau_cant_lookup_display = 'Imposible de repérer votre localisation.';          ///< Displayed if the app is unable to determine the location.
    static  $local_nouveau_display_map_results_text = 'Affichage des résultats mode carte.';    ///< The text for the display map results disclosure link.
    static  $local_nouveau_display_list_results_text = 'Affichage des résultats mode liste.';  ///< The text for the display list results disclosure link.
    static  $local_nouveau_table_header_array = array ( 'Nation', 'Province', 'Région', 'Ville', 'Nom de la réunion', 'Jour de semaine', 'Début (heure)', 'Endroit', 'Format', ' ' );
    static  $local_nouveau_weekday_long_array = array ( 'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi' );
    static  $local_nouveau_weekday_short_array = array ( 'Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa' );
    
    static  $local_nouveau_meeting_results_count_sprintf_format = '%s réunions trouvées';
    static  $local_nouveau_meeting_results_selection_count_sprintf_format = '%s réunions sélectionnées, sur %s réunions trouvées';
    static  $local_nouveau_meeting_results_single_selection_count_sprintf_format = 'sélectionnées, sur %s réunions trouvées';
    static  $local_nouveau_single_time_sprintf_format = "La réunion a lieu à toute les %s, à %s, et d\'une durée de %s.";
    static  $local_nouveau_single_duration_sprintf_format_1_hr = '1 heure';
    static  $local_nouveau_single_duration_sprintf_format_mins = '%s minutes';
    static  $local_nouveau_single_duration_sprintf_format_hrs = '%s heures';
    static  $local_nouveau_single_duration_sprintf_format_hr_mins = '1 heure et %s minutes';
    static  $local_nouveau_single_duration_sprintf_format_hrs_mins = '%s heures et %s minutes';
    
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

    static  $local_nouveau_location_sprintf_format_wtf = 'Aucun endroit trouvé';
    
    static  $local_nouveau_location_services_set_my_location_advanced_button = "Déplacer le pointeur pour l\'endroit actuel";
    static  $local_nouveau_location_services_find_all_meetings_nearby_button = 'Trouver une réunion près de chez moi';
    static  $local_nouveau_location_services_find_all_meetings_nearby_later_today_button = 'Trouver une réunion qui se déroulera plus tard près chez de moi';
    static  $local_nouveau_location_services_find_all_meetings_nearby_tomorrow_button = 'Trouver une réunion qui aura lieu demain prés de chez moi';

    static  $local_nouveau_location_sprintf_format_duration_title = "Cette réunion est d\'une durée de %s heure et % minutes.";
    static  $local_nouveau_location_sprintf_format_duration_hour_only_title = "Cette réunion est d\'une durée d\'une heure.";
    static  $local_nouveau_location_sprintf_format_duration_hour_only_and_minutes_title = "Cette réunion est d\'une durée de 1 heure et %s minutes.";
    static  $local_nouveau_location_sprintf_format_duration_hours_only_title = "Cette réunion est d\'une durée de %s heures.";
    static  $local_nouveau_lookup_location_failed = "La recherche d\'adresse ne s\'est pas réalisée avec succès.";
    static  $local_nouveau_lookup_location_server_error = "La recherche d\'adresse ne s\'est pas réalisée avec succès, une erreur du serveur s\'est produite.";
    static  $local_nouveau_time_sprintf_format = '%d:%02d %s';
    static  $local_nouveau_am = 'AM';
    static  $local_nouveau_pm = 'PM';
    static  $local_nouveau_noon = 'Midi';
    static  $local_nouveau_midnight = 'Minuit';
    static  $local_nouveau_advanced_map_radius_value_array = "0.25, 0.5, 1.0, 2.0, 5.0, 10.0, 15.0, 20.0, 50.0, 100.0, 200.0";
    static  $local_nouveau_meeting_details_link_title = 'Obtenir plus de renseignements à propos de cette réunion.';
    static  $local_nouveau_meeting_details_map_link_uri_format = 'https://maps.google.com/maps?q=%f,%f';
    static  $local_nouveau_meeting_details_map_link_text = 'Coordonnées de la réunion';

    static  $local_nouveau_single_formats_label = 'Formats de réunion:';
    static  $local_nouveau_single_service_body_label = 'Échelon de structure de service:';

    static  $local_nouveau_prompt_array = array (
                                                'weekday_tinyint' => 'Jour de semaine',
                                                'start_time' => 'Début (heure)',
                                                'duration_time' => 'Durée',
                                                'formats' => 'Format',
                                                'distance_in_miles' => 'Distance en miles',
                                                'distance_in_km' => 'Distance en kilomètres',
                                                'meeting_name' => 'Meeting Name',
                                                'location_text' => 'Nom de l\'emplacement',
                                                'location_street' => 'Adresse',
                                                'location_city_subsection' => 'Quartier',
                                                'location_neighborhood' => 'Secteur',
                                                'location_municipality' => 'Ville',
                                                'location_sub_province' => 'Région',
                                                'location_province' => 'Province',
                                                'location_nation' => 'Pays',
                                                'location_postal_code_1' => 'Code Postal',
                                                'location_info' => 'Autres Informations'
                                                );
                                                
    /************************************************************************************//**
    *                      STATIC DATA MEMBERS (SPECIAL LOCALIZABLE)                        *
    ****************************************************************************************/
    
    /// This is the only localizable string that is not processed. This is because it contains HTML. However, it is also a "hidden" string that is only displayed when the browser does not support JS.
    static  $local_no_js_warning = "<noscript class=\"no_js\">La recherche de réunion ne pourra s\'effectuer car votre navigateur ne supporte pas JavaScript. Toutefois, vous pouvez essayer <a rel=\"external nofollow\" href=\"###ROOT_SERVER###\">main server</a> pour tenter une recherche.</noscript>"; ///< This is the noscript presented for the old-style meeting search. It directs the user to the root server, which will support non-JS browsers.
                                    
    /************************************************************************************//**
    *                       STATIC DATA MEMBERS (NEW MAP LOCALIZABLE)                       *
    ****************************************************************************************/
                                    
    static  $local_new_map_option_1_label = "Options de recherche (ne s'applique qu'à moins si cette est activé):";
    static  $local_new_map_weekdays = 'Réunions qui se trouvent durant ces jours de semaine:';
    static  $local_new_map_all_weekdays = 'Tous';
    static  $local_new_map_all_weekdays_title = 'Trouver des réunions pour chaque jours.';
    static  $local_new_map_weekdays_title = 'Trouvé des réunion qui occure le(s) ';
    static  $local_new_map_formats = 'Réunions aux formats suivants:';
    static  $local_new_map_all_formats = 'Tous';
    static  $local_new_map_all_formats_title = 'Trouver réunion peu importe son format.';
    static  $local_new_map_js_center_marker_current_radius_1 = 'Ce rayon couvre ';
    static  $local_new_map_js_center_marker_current_radius_2_km = ' kilomètres à la ronde.';
    static  $local_new_map_js_center_marker_current_radius_2_mi = ' miles à la ronde.';
    static  $local_new_map_js_diameter_choices = array ( 0.25, 0.5, 1.0, 1.5, 2.0, 3.0, 5.0, 10.0, 15.0, 20.0, 25.0, 30.0, 50.0, 100.0 );
    static  $local_new_map_js_new_search = 'Nouvelle recherche';
    static  $local_new_map_option_loc_label = 'Entrer une localisation::';
    static  $local_new_map_option_loc_popup_label_1 = 'Rechercher une réunion à proximité de';
    static  $local_new_map_option_loc_popup_label_2 = 'cette endroit.';
    static  $local_new_map_option_loc_popup_km = 'Km';
    static  $local_new_map_option_loc_popup_mi = 'Miles';
    static  $local_new_map_option_loc_popup_auto = 'un choix automatique de cette endroit';
    static  $local_new_map_center_marker_distance_suffix = " centré à l\'aide du pointeur.";
    static  $local_new_map_center_marker_description = "Ceci est l\'endroit choisi.";
    static  $local_new_map_text_entry_fieldset_label = 'Entrer une adresse, une code postal ou un endroit';
    static  $local_new_map_text_entry_default_text = 'Entrer une adresse, une code postal ou un endroit';
    static  $local_new_map_location_submit_button_text = 'Recherche de réunion à proximité de cette endroit.';
    
    /************************************************************************************//**
    *                       STATIC DATA MEMBERS (MOBILE LOCALIZABLE)                        *
    ****************************************************************************************/
    
    /// The units for distance.
    static  $local_mobile_kilometers = 'Kilomêtres';
    static  $local_mobile_miles = 'Miles';
    static  $local_mobile_distance = 'Distance';  ///< Distance (the string)

    /// The page titles.
    static  $local_mobile_results_page_title = 'Résultats de recherche rapide de réunions';
    static  $local_mobile_results_form_title = 'Trouver rapidement une réunion à proximté';

    /// The fast GPS lookup links.
    static  $local_GPS_banner = 'Sélection rapide de réunions';
    static  $local_GPS_banner_subtext = "Ajouter à vos favoris ces liens pour d\'eventuelles recherches rapides.";
    static  $local_search_all = 'Trouver toutes les réunion à proximité de votre endroit actuel.';
    static  $local_search_today = "Aujourd\'hui, mais plus tard";
    static  $local_search_tomorrow = 'Dwmain';

    /// The search for an address form.
    static  $local_list_check = 'Si vous éprouvez des difficultés avec la carte interactive, ou vous préférrez vous procurrer des résultats sur une liste, coché cette case et entrer une adresse.';
    static  $local_search_address_single = "Trouver une réunion à proximité d\'une adresse.";

    /// Used instead of "near my present location."
    static  $local_search_all_address = 'Trouver toutes les réunions à proximité de cette adresse.';
    static  $local_search_submit_button = 'Recherche de réunions';

    /// This is what is entered into the text box.
    static  $local_enter_an_address = 'Entrez une Addesse';

    /// Error messages.
    static  $local_mobile_fail_no_meetings = 'Aucune réunion trouvé!';
    static  $local_server_fail = 'La recherche a échoué car le serveur a obtenu une erreur';
    static  $local_cant_find_address = 'Ne peut repérer sa localisation à partir de cette adresse envoyée!';
    static  $local_cannot_determine_location = 'Ne peut repérer votre localisation actuelle!';
    static  $local_enter_address_alert = 'SVP, veuillez entrer une adresse!';

    /// The text for the "Map to Meeting" links
    static  $local_map_link = 'Map to Meeting';

    /// Only used for WML pages
    static  $local_next_card = 'Réunion Suivante >>';
    static  $local_prev_card = '<< Précedante Réunion';

    /// Used for the info and list windows.
    static  $local_formats = 'Formats';
    static  $local_noon = 'Midi';
    static  $local_midnight = 'Minuit';

    /// This array has the weekdays, spelled out. Since weekdays start at 1 (Sunday), we consider 0 to be an error.
    static	$local_weekdays = array ( 'ERREUR', 'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi' );
    static	$local_weekdays_short = array ( 'ERR', 'Di', 'Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa' );
    };
?>