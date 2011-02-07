/****************************************************************************************//**
* \file admin_javascript.js																	*
* \brief The javascript for the BMLTPlugin class (Admin options).							*
* \version 1.0.0																			*
* \license Public Domain -No restrictions at all.											*
********************************************************************************************/

var BMLTPlugin_TimeToFade = 2000.0;	///< The fade time (2 seconds).

/****************************************************************************************//**
*	\brief Switches the visibility of the property sheets -called when the select changes.	*
********************************************************************************************/
function BMLTPlugin_SelectOptionSheet ( in_value,		///< The current value of the select
										in_num_options	///< The number of available options.
										)
{
	for(var i=1;i<=in_num_options;i++)
		{
		var item_id = 'BMLTPlugin_option_sheet_'+i+'_div';
		var item = document.getElementById(item_id);
		if ( item )
			{
			item.style.display = ((i==in_value)?'block':'none');
			};
		};
};

/****************************************************************************************//**
*	\brief Deletes one of the options after a confirm.										*
********************************************************************************************/
function BMLTPlugin_DeleteOptionSheet()
{
	// The c_g_delete_confirm_message is actually set in the PHP file. It is a constant global.
	if ( confirm ( c_g_delete_confirm_message ) )
		{
		var option_select = document.getElementById ( 'BMLTPlugin_legend_select' );
		var option_index = 1;
		if ( option_select )
			{
			option_index = parseInt ( option_select.value );
			};
		
		var url = document.getElementById ( 'BMLTPlugin_sheet_form' ).action + '&BMLTPlugin_delete_option=' + option_index;

		window.location.replace ( url );
		};
};

/****************************************************************************************//**
*	\brief This actually saves the new options.												*
********************************************************************************************/
function BMLTPlugin_SaveOptionSheet()
{
	var option_select = document.getElementById ( 'BMLTPlugin_legend_select' );
	var option_index = 1;
	if ( option_select )
		{
		option_index = parseInt ( option_select.value );
		};
	
	var url = document.getElementById ( 'BMLTPlugin_sheet_form' ).action + '&BMLTPlugin_set_options='+option_index;
	var	root_server = document.getElementById ( 'BMLTPlugin_option_sheet_root_server_'+option_index ).value.toString();
	url += '&BMLTPlugin_option_sheet_root_server='+encodeURIComponent ( root_server );
	window.location.replace ( url );
};

/****************************************************************************************//**
*	\brief When a change is made to an option, this sets a "dirty" flag, by enabling the	*
*	"Save Changes" button.																	*
********************************************************************************************/
function BMLTPlugin_DirtifyOptionSheet(	in_disable	///< If this is true, then we "clean" the flag.
										)
{
	document.getElementById ( 'BMLTPlugin_toolbar_button_save' ).disabled = (in_disable == true);
	document.getElementById ( 'BMLTPlugin_toolbar_button_save' ).className = 'BMLTPlugin_toolbar_button_save_' + ((in_disable == true) ? 'disabled' : 'enabled').toString();
};

/****************************************************************************************//**
*	\brief Starts the message "fader."														*
*																							*
*	Simple fader, taken from here:															*
*		http://www.switchonthecode.com/tutorials/javascript-tutorial-simple-fade-animation	*
********************************************************************************************/
function BMLTPlugin_StartFader()
{
	var	eid = 'BMLTPlugin_Fader';
	var element = document.getElementById ( eid );
	
	if ( element )
		{
		if ( element.FadeState == null )
			{
			if ( element.style.opacity == null 
				|| element.style.opacity == '' 
				|| element.style.opacity == '1' )
				{
				element.FadeState = 2;
				}
			else
				{
				element.FadeState = -2;
				};
			};
		
		if ( element.FadeState == 1 || element.FadeState == -1 )
			{
			element.FadeState = element.FadeState == 1 ? -1 : 1;
			element.FadeTimeLeft = BMLTPlugin_TimeToFade - element.FadeTimeLeft;
			}
		else
			{
			element.FadeState = element.FadeState == 2 ? -1 : 1;
			element.FadeTimeLeft = BMLTPlugin_TimeToFade;
			setTimeout ( "BMLTPlugin_animateFade(" + new Date().getTime() + ",'" + eid + "')", 33);
			};
		};
};

/****************************************************************************************//**
*	\brief Animates the fade.																*
*																							*
*	Simple fader, taken from here:															*
*		http://www.switchonthecode.com/tutorials/javascript-tutorial-simple-fade-animation	*
********************************************************************************************/
function BMLTPlugin_animateFade (	lastTick,	///< The time of the last tick.
									eid			///< The element ID
								)
{  
	var curTick = new Date().getTime();
	var elapsedTicks = curTick - lastTick;
	
	var element = document.getElementById ( eid );
	
	if ( element.FadeTimeLeft <= elapsedTicks )
		{
		element.style.opacity = element.FadeState == 1 ? '1' : '0';
		element.style.filter = 'alpha(opacity = ' + (element.FadeState == 1 ? '100' : '0') + ')';
		element.FadeState = element.FadeState == 1 ? 2 : -2;
		return;
		};
	
	element.FadeTimeLeft -= elapsedTicks;
	
	var newOpVal = element.FadeTimeLeft/BMLTPlugin_TimeToFade;
	
	if ( element.FadeState == 1 )
		{
		newOpVal = 1 - newOpVal;
		};
	
	element.style.opacity = newOpVal;
	element.style.filter = 'alpha(opacity = ' + (newOpVal*100) + ')';
	
	setTimeout ( "BMLTPlugin_animateFade(" + curTick + ",'" + eid + "')", 33 );
};