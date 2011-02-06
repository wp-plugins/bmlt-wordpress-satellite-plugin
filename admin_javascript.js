/****************************************************************************************//**
* \file admin_javascript.js																	*
* \brief The javascript for the BMLTPlugin class (Admin options).							*
* \version 1.0.0																			*
* \license Public Domain -No restrictions at all.											*
********************************************************************************************/

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
