$(document).ready(function($) 
{



	//--->deletel single row > start
	function remove_curr_tbl_row(ele) 
	{	 
		ele.closest('tr').css('background-color', 'red');
		
		ele.closest('tr').fadeOut('slow', function()
		{
			$(this).remove();
		}); 	
	};

	$(document).on('click', '.btn_delete', function(event) 
	{
		event.preventDefault();

		remove_curr_tbl_row($(this));
	}); 		
	//--->deletel single row > start
	 

	//--->select/unselect all > start
	 function select_unselect_checkbox (this_el, select_el) 
	 {

		if(this_el.prop("checked"))
		{
			select_el.prop('checked', true);
		}
		else
		{ 
			select_el.prop('checked', false);				 
		}
	 };

	$(document).on('change', '.select_all_items', function(event) 
	{
		event.preventDefault();

		var ele = $(document).find('.item_id'); 

		select_unselect_checkbox($(this), ele); 
	});
	//--->select/unselect all > end



	//--->deletel selected rows > start
	function remove_all_checked_val(ele) 
	{	 
		ele.each(function(index, v1)
		{   
			if($(this).prop("checked")) 
			 {
				$(this).closest('tr').css('background-color', 'blue');
				
				$(this).closest('tr').fadeOut('slow', function()
				{
					$(this).remove();
				}); 
			} 
		});
	};
	$(document).on('click', '.btn_delete_val', function(event) 
	{
		event.preventDefault();

		var ele = $(document).find('.item_id'); 
		var v1 = remove_all_checked_val(ele);
	});
	//--->deletel selected rows > end

});