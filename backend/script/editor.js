(function($) {

	var new_module = 0;
	var icon_object = '';
	
	/*
	var g_basepath = 'http://localhost/laufendeProjekte/FF-BadSchwalbach/Relaunch_2015/_web/admin/'
	var basepath = 'http://localhost/laufendeProjekte/FF-BadSchwalbach/Relaunch_2015/_web/'
	*/
	var g_basepath = 'http://www.feuerwehr-badschwalbach.de/admin/'
	var basepath = 'http://www.feuerwehr-badschwalbach.de/'

	$(document).ready(function() {

	});
	
	// --- Open Upload-Window if clicking the area
	$(document).on('click', '[contenteditable="true"]', function(){ 
	   //alert("hallo");
	   close_all_panels();
	   $(this).before('<div class="editor_wrapper"></div>')
	});

	function close_all_panels() {
		$('.editor_wrapper').remove();
	}
	



})(jQuery);