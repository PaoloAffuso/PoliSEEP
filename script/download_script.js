$(document).ready(function($) 
{
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

	//--->download function
	function downloadChecked( )
	{
		for( i = 0 ; i < document.downloadform.elements.length ; i++ )
		{
			foo = document.downloadform.elements[ i ] ;
			if( foo.type == "checkbox" && foo.checked == true )
			{
				document.location.href='somefile.do?command=download&fileid=' + foo.name ;
			}
		}
	}

	$("#downloadChecked").click(function(){
		
		var checkedVals = $('.item_id:checkbox:checked').map(function() {
			return this.value;
		}).get();

		for(i=0;i<checkedVals.length;i++) {
			idCorso=checkedVals[i].split("-")[0];
			nome=checkedVals[i].split("-")[1];
			$.ajax({
				url: "../student/download.php",
				type: "post",
				data : {'idCorso': idCorso, 'nome': nome},
				success: function (response) {
				  if(response!=="ERRORE") {
					const byteCharacters = atob(response);
					const byteNumbers = new Array(byteCharacters.length);
					for (let i = 0; i < byteCharacters.length; i++) {
						byteNumbers[i] = byteCharacters.charCodeAt(i);
					}

					const byteArray = new Uint8Array(byteNumbers);
					const blob = new Blob([byteArray], {type: 'application/pdf'});

					const blobUrl = URL.createObjectURL(blob);
					window.open(blobUrl)
				  }
				}
			  });
		}
	});   

});