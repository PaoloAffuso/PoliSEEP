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
                $(this).closest('tr').css('background-color', 'red');
                
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



    //--->get selected rows values > start

    function get_all_checked_val(ele, attr_lookup) 
    {  
        var get_obj = [];
        ele.each(function(index, v1)
        {   
            if($(this).prop("checked")) 
             {
                get_obj.push($(this).attr(attr_lookup));
            } 
        });			
        return get_obj;
    };


    $(document).on('click', '.btn_get_val', function(event) 
    {
        event.preventDefault();

        var ele = $(document).find('.item_id'); 

        var v1 = get_all_checked_val(ele, 'option_id');

        var v2 = ''
        +'<pre class="bg-secondary">' 
        +JSON.stringify(v1, null, 5)
        +'</pre>';

        $(document).find('.post_msg').html(v2);

    });		
    //--->get selected rows values > end
    
    //--->upload button
    function upload_btn() {
        document.getElementById('buttonid').addEventListener('click', openDialog);
        function openDialog() {
            document.getElementById('fileid').click();
        }
        document.getElementById('fileid').addEventListener('change', submitForm);
        function submitForm() {
            document.getElementById('formid').submit();
        }
    }

    const actualBtn = document.getElementById('actual-btn');

        const fileChosen = document.getElementById('file-chosen');

        actualBtn.addEventListener('change', function(){
        fileChosen.textContent = this.files[0].name
})  

});