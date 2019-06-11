(function($) {

	var new_module = 0;
	var icon_object = '';
	var editor_selRange;	
	
	
	var g_basepath = 'http://localhost/laufendeProjekte/FF-BadSchwalbach/_web/admin/'
	var basepath = 'http://localhost/laufendeProjekte/FF-BadSchwalbach/_web/'
		
	/*
	var g_basepath = 'http://www.feuerwehr-badschwalbach.de/admin/'
	var basepath = 'http://www.feuerwehr-badschwalbach.de/'
	*/

	$(document).ready(function() {

 		$('.js_countsigns').change(updateCount);
 		$('.js_countsigns').keyup(updateCount);
		$( "#globalmessage" ).delay(2000).fadeOut(1000, "swing");

		tinymce.init({
			selector: '.js_textoptions',
			height: 200,
			plugins: [
				'advlist autolink lists link image charmap print preview anchor',
				'searchreplace visualblocks code fullscreen',
				'insertdatetime media table contextmenu paste code'
			],
			toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link',
			content_css: [
			]
		});

	});
	
	// --- SCROLLING EVENTS
	$(window).scroll(function (event) {
		
		var y = $(this).scrollTop();
		var top = 75;
		var savebarpos = $('#admin_pageheadline').position();

		if($(window).width()>490) { 
			if (y >= top) {
				$('#admin_pageheadline').addClass('admin_pageheadline_fix');
				$('#admin_siteeditbar').addClass('admin_pageheadline_fix');
				$('#admin_pageheadline_placeholder').removeClass('hide');
				$('#admin_pagemetaform').css('position', 'fixed');
				$('#admin_pagemetaform').css('margin-top', '-75px');
			} else {
				$('#admin_pageheadline').removeClass('admin_pageheadline_fix');
				$('#admin_siteeditbar').removeClass('admin_pageheadline_fix');
				$('#admin_pageheadline_placeholder').addClass('hide');
				$('#admin_pagemetaform').css('position', 'absolute');
				$('#admin_pagemetaform').css('margin-top', '0px');
			}
		} else {
			$('#contentnavbar').removeClass('fixed');
			$('.jsplatzhalter').css('display', 'none');
			$('.filter').css('margin', '0 0 20px 0');
		}
		
	});


	$('.js_adminusersettings').click(function() {
		if($('.adminusermenu').hasClass('hide')) {	
			$('.adminusermenu').removeClass('hide');
		} else {
			$('.adminusermenu').addClass('hide');
		}
	});


	// --- Initial Media Upload Count-Vars
	var media_upload_count = 2;
	var max_media_upload_count = 10;


	$(document).on("mouseover", '.admin_layoutmodul', function() {

		$(this).children('.admin_layoutmodul_panel').removeClass('admin_hide');
		$(this).children('.admin_layoutmodul_panel_delete').removeClass('admin_hide');
		$(this).children('.admin_layoutmodul_panel_edit').removeClass('admin_hide');
		$(this).children('.admin_layoutmodul_panel_addnewcell').removeClass('admin_hide');
		$(this).children('.admin_layoutmodul_panel_addnewteaser').removeClass('admin_hide');

	});
	$(document).on("mouseleave", '.admin_layoutmodul', function() {

		$(this).children('.admin_layoutmodul_panel').addClass('admin_hide');
		$(this).children('.admin_layoutmodul_panel_delete').addClass('admin_hide');
		$(this).children('.admin_layoutmodul_panel_edit').addClass('admin_hide');
		$(this).children('.admin_layoutmodul_panel_addnewcell').addClass('admin_hide');
		$(this).children('.admin_layoutmodul_panel_addnewteaser').addClass('admin_hide');

	});
	$(document).on("mouseover", '.cell', function() {

		$(this).children('.admin_table_edit').removeClass('admin_hide');
		$(this).children('.admin_table_delete').removeClass('admin_hide');

	});
	$(document).on("mouseleave", '.cell', function() {

		$(this).children('.admin_table_edit').addClass('admin_hide');
		$(this).children('.admin_table_delete').addClass('admin_hide');

	});
	$(document).on("click", '[contenteditable="true"]', function() {
		var text = $(this).parent().parent().parent('.admin_layoutmodul').attr("data-pagemodule-type");;
		if(text=="editorial") {
			$(this).parent().parent().parent('.admin_layoutmodul').children('.admin_layoutmodul_panel_editor').removeClass('admin_hide');
			
		}
	});
	
	$(document).on("blur", '[contenteditable="true"]', function() {
		if(!$(this).parent().parent().parent('.admin_layoutmodul').children('.admin_layoutmodul_panel_editor').hasClass('admin_hide')) {
			$(this).parent().parent().parent('.admin_layoutmodul').children('.admin_layoutmodul_panel_editor').addClass('admin_hide');
		}
	});	
	

	/*
	$(document).on("mouseover", '#stage', function() {
		$(this).prev('.admin_layoutmodul_panel_edit').removeClass('admin_hide');
	});
	$(document).on("mouseleave", '#stage', function() {
		$(this).prev('.admin_layoutmodul_panel_edit').addClass('admin_hide');
	});
	*/

	$('#js-send-form').click(function() {
		
		if($('#js-send-form').attr("checkform")=="yes") {
			
			var myForm = document.getElementById('admin_form');
			formData = new FormData(myForm);
			checkform(formData);
		} else {
			$( "#admin_form" ).submit();
		}

	});


	function updateCount() {
    	var len = this.value.length;
    	var max = $(this).attr("data-maxsign");

    	if (len > max) {
    		this.value = this.value.substring(0, max);
    	} else {
        	$('.js_charNum').text(max - len);
       	}

	};

	function checkform(formData) {
		
		// Bestehende Messages zurücksetzen
		$('[data-error-msg="js_form_errormsg"]').remove();
		$('.input_error').removeClass("input_error")

		//--- Modul per AJAX laden
		$.ajax({
			type:'POST',
			url: g_basepath+'Load_checkform',
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(msg){
				if(msg=="yes") {
					$( "#admin_form" ).submit();
				} else {
					
					error = msg.split(":");
					for (i = 0; i < error.length; i++) { 
						error_details = error[i].split("|");
						
						if(error_details[2]!='group') {
							if(error_details[2]!='array') {
								// Set Field Highlight
								$('[name="'+error_details[0]+'"]').addClass("input_error");
								// Write Error Description
								html = '<div class="error" data-error-msg="js_form_errormsg">'+error_details[1]+'</div>';
									
								if(parseInt(error_details[2])==0) {
									$(html).insertAfter('[name="'+error_details[0]+'"]');
								} else {
									//$(html).parent().insertAfter('[name="'+error_details[0]+'"]');
									$('[name="'+error_details[0]+'"]').parent().parent().parent().after(html);
								}
							} else {
								html = '<div class="error" data-error-msg="js_form_errormsg">'+error_details[1]+'</div>';
								$('[data-group="'+error_details[0]+'"]').after(html);
							}
						} else {
							$('[name="'+error_details[0]+'"]').parent().addClass("input_error");
							html = '<div class="error" data-error-msg="js_form_errormsg">'+error_details[1]+'</div>';
							$('[name="'+error_details[0]+'"]').parent().after(html);
						}
					}
					
					// Globale Fehlermeldung ausspielen
					var message_0="error";
					var message_1="Es sind einge Fehler Aufgetreten bitte kontrollieren Sie Ihre Eingaben!";
					$('<div id="globalmessage"><p class="'+message_0+'">'+message_1+'</p></div>').insertBefore("#admin_header");
					$( "#globalmessage" ).delay(2000).fadeOut(1000, "swing");
					$('body,html').animate({ scrollTop: 0 }, 50);
				}
            },
            error: function(formData){
                console.log("error");
                console.log(formData);
            }
		});	
		
	}

	/*--------------------------------------------------------------*/
	//  Image upload
	/*--------------------------------------------------------------*/

	$('.js_admin_addImage').click(function() {
		
		$('#js_media_upload').removeClass('admin_hide');
		$(document.body).addClass('admin_noscroll');

	});
	$('#js_admin_uploadbox_close').click(function(e) {
		e.preventDefault();
		
		$('#js_media_upload').addClass('admin_hide');
		$(document.body).removeClass('admin_noscroll');

	});

	$('#js_admin_savefile').click(function() {
		
		html = '<div><p>Upload läuft. Bitte warten ...</p><div class="admin_showbox"><div class="admin_loader"><svg class="admin_circular" viewBox="25 25 50 50"><circle class="admin_path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg></div></div></div>'
		$('.js_adminimageupload_box').addClass('admin_hide');
		$(html).insertAfter('.admin_uploadbutton');
		$('#js_admin_savefile').addClass('admin_hide');
		$('#js_admin_fileuploadform').submit();

	});

	$('#js_admin_savefiless').click(function() {

		//e.preventDefault();
		$( "#js_admin_fileuploadform" ).submit();
		
	});

	$(document).on("change", ".js_meda_choosefile", function() {
        
        var files = !!this.files ? this.files : [];
        var number = $(this).attr('data-uploadnumber');
        
        if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
 
        if (/^image/.test( files[0].type)){ // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file
 
            reader.onloadend = function(){ // set image data as background of div
                $("#js_adminimage_preview_"+number).css("background-image", "url("+this.result+")");
            }
        }

    });

	// --- Open Upload-Window if clicking the area
	$(document).on('click', '.admin_imagePreview', function(){ 
	    $(this).parent(".admin_uploadcontainer").find('input[type=file]').trigger('click');
	});

	// --- Add new upload field
	$('#js_admin_moremediaupload').click(function() { 

		if(media_upload_count<=max_media_upload_count) {
			var code = '<div class="admin_uploadcontainer admin_uploadcontainer_'+media_upload_count+'"><!--<div class="adminRremove_mediaupload" data-uploadnumber="'+media_upload_count+'">Entfernen</div>--><p><input type="file" name="media_file[]" class="js_media_file_'+media_upload_count+'2 js_meda_choosefile" data-uploadnumber="'+media_upload_count+'" /></p><p class="imagePreview_container"><div class="admin_imagePreview" id="js_adminimage_preview_'+media_upload_count+'" data-uploadnumber="'+media_upload_count+'"><div class="admin_upload_advice"><strong>hier klicken</strong><br>um ein Bild hoch zu laden</div></div></p><p><label for="alt_text"><span class="helptext">ALT-Text</span></lable><input type="text" name="alt_text" /></p></div';
			$('.js_adminimageupload_box').append(code);

			media_upload_count++;
		} 
		if(media_upload_count>max_media_upload_count) {
			$('#js_admin_moremediaupload').addClass('hide');
		}

	});

	$(document).on('click', '.adminRremove_mediaupload', function() {
        var number = $(this).attr('data-uploadnumber');
		$(this).parent().remove();
	});
   
	
	/*--------------------------------------------------------------*/
	//  Allgemeine Drawer Funktion
	/*--------------------------------------------------------------*/
	//  trigger: class="js_admin_opendrawer"
	//  wich drawer: data-drawer="js_admin_opendrawer"
	/*--------------------------------------------------------------*/
	
	$('.js_admin_opendrawer').click(function(e) {
		onlylink = $(this).attr("type");
		if(onlylink!="checkbox") {
			e.preventDefault();
		}
		var drawerID = $(this).attr("data-drawer");

		if($(this).is(':checked')) {
			$('.js_admin_opendrawer_'+drawerID).removeClass('admin_hide');
		} else {
			$('.js_admin_opendrawer_'+drawerID).addClass('admin_hide');
		}

	});

	$(document).on("click", ".js_admin_tabbar", function(e) {
		e.preventDefault();
		var drawerID = $(this).attr("data-drawer");
		
		$('.tabbar').children().each(function(){
			$(this).children().removeClass('active');
		});
		$('.js_admin_tabbarcontent').children().each(function(){
			$(this).addClass('admin_hide');
		});

		$(this).addClass('active');
		if($('.js_admin_opendrawer_'+drawerID).hasClass('admin_hide')) {
			$('.js_admin_opendrawer_'+drawerID).removeClass('admin_hide');
		} else {
			$('.js_admin_opendrawer_'+drawerID).addClass('admin_hide');
		}

	});


	$('.admin_tab').click(function() {
		
		var x=1;
		$.each($(this).parent().parent().find('.admin_tabcontent'), function(i, obj) {
			
			if(!$('#js_admin_tabcontent_'+x).hasClass('.admin_hide')) {
				$('#js_admin_tabcontent_'+x).addClass('admin_hide');
			}
			x++;			
		});	

		var drawerID = $(this).parent().attr("data");
		$('#js_admin_tabcontent_'+drawerID).removeClass('admin_hide');

	});
	
	/*--------------------------------------------------------------*/
	//  Fahrzeug Auswahl
	/*--------------------------------------------------------------*/

	$('.js_admin_wehrselector').click(function() {
		
		var car_wehrID = $(this).val();

		if($(this).is(':checked')) {
			$('.js_carselection_'+car_wehrID).removeClass('inactive');
		} else {
			$('.js_carselection_'+car_wehrID).addClass('inactive');
			$('.js_carselection_'+car_wehrID).children('.admin_car_tile').prop( "checked", false );
		}

	});


	/*--------------------------------------------------------------*/
	//  Mini Gallery DELETE (Admin-Form)
	/*--------------------------------------------------------------*/

	$('.js_admin_miniGallley_del').click(function(e) {
		e.preventDefault();
		
		var base_path = $(this).attr('data-basepath');
		var del_link = $(this).attr('data-linkvars');
		//alert(base_path+"admin/?op=media_delete"+del_link);


		$.ajax({
			type: "get",
			url: base_path+"/admin/?op=einsatz_image_delete"+del_link,
			cache: false,				
			data: base_path+"/admin/?op=einsatz_image_delete"+del_link,
	 	});

	 	$(this).parent('div').remove();

	});

	/*--------------------------------------------------------------*/
	//  IMAGE & FILE Upload AJAX
	/*--------------------------------------------------------------*/

	$(document).on("submit", "#js_admin_saveimage_ajax", function(e) {
		e.preventDefault();

		var media_type = $('[name="media_type"]').val();
		var formData = new FormData(this);
		$.each($("input[type=file]"), function(i, obj) {
       		$.each(obj.files,function(j, file){
           		formData.append('media_file['+j+']', file);
		    })
		});

		//console.log(formData);

		//--- Modul per AJAX laden
		$.ajax({
			type:'POST',
			url: g_basepath+'imageupload',
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(msg){
				var message = msg.split(":");
				$('.admin_lightbox_subform').addClass('admin_hide');
				$('.admin_lightbox_mainform').removeClass('admin_hide');

				if(message[0]=="success") {
					// Bild in die Liste einfügen

					if(media_type == "image") {	
						var i = $('#admin_moduledit_imggal').children().length;
						if( $("[name=js_admin_fastuploadtarget]").length ) { 
							if( $("[name=js_admin_fastuploadtarget]").val() == "teaser" ) {
								var action = 'js_admin_changeteaserimage';
								var img = basepath+'frontend/images_cms/'+message[5]+message[3]+'.'+message[4];
								change_teaser_image(message[2], img);
							}
						} else {
							var action = 'js_admin_moduleedit_imagedelete';
						}
						$('#admin_moduledit_imggal').append('<li class="'+action+'" id="slideshow_'+i+'" data-imgid="'+message[2]+'"><img src="'+basepath+'frontend/images_cms/'+message[5]+message[3]+'.'+message[4]+'" /></li>');
					} else {
						$('#admin_moduledit_imggal').append('<li class="file" id="slideshow_'+i+'" data-fileid="'+message[2]+'" data-name="'+message[3]+'" data-format="'+message[6]+'" data-size"'+message[5]+'" data-icon=""><p><strong>'+message[3]+'</strong></p><p>'+message[6]+' - '+message[5]+'</p><hr class="clear" /></li>');
					}

					$('#admin_moduledit_imggal').sortable("refresh");	
				}
				console.log(message);
				module_show_message(message, "");
            },
            error: function(formData){
                console.log("error");
                console.log(formData);
            }
		});
	
	});

	
	/*--------------------------------------------------------------*/
	//  Load Files from Folder
	/*--------------------------------------------------------------*/

	$(document).on("submit", "#js_admin_loadfolder_ajax", function(e) {
		e.preventDefault();
		var formData = new FormData(this);

		//--- Modul per AJAX laden
		$.ajax({
			type:'POST',
			url: g_basepath+'Load_folder',
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(msg){
				$('.js_admin_editorfolderlist').replaceWith(msg);
            },
            error: function(formData){
                console.log("error");
                console.log(formData);
            }
		});
	});


	/*--------------------------------------------------------------*/
	//  Page-Layout-Functions
	/*--------------------------------------------------------------*/

	$('.js_admin_pagelayoutsave').click(function(e) {
		e.preventDefault();
		var modulSequence = '';
		var modulNumber = 0;
		var op = $('[name="op"]').val();

		if(op=="news_save") {
			update_newsform();
		}

	    $('#content').children().each(function(){
			
		   	if($(this).attr('data-module-id')) {

		   		var modulID = $(this).attr('data-module-id');
		   		var moduleType = $(this).attr('data-pagemodule-type');

		   		// "contenteditable" Elemente auslesen
		   		// ------------------------------------------------------------------------------
			   	if(moduleType!="table") {
				   	if(moduleType=="teaser") {
				   		update_teaser_value(modulID);
				   	} else {
				   		if($(this).find('[contenteditable="true"]').length > 0) {
					    	
							contentdetail = new Array();

					    	$(this).find('[contenteditable="true"]').each(function(){
								contentdetail.push($(this).html());
							});
					    	$('[name="content_'+modulID+'"]').val(contentdetail.join("::"));  		
				   		}
				   	}
			   	} else {
			   		contentdetail = new Array();

			   		$(this).find('.cell').each(function() {
				    	cellcontent = new Array();

						cellcontent.push($(this).attr('data-cell'));

				    	$(this).find('[contenteditable="true"]').each(function(){
							cellcontent.push($(this).html());
						});	
				    	$(this).find('img').each(function(){
				    		var path = $(this).attr('src');
				    		var file = path.split('/');
				    		cellcontent.push(file[file.length-1]);
				    	});

				    	contentdetail.push(cellcontent.join("::"));

			   		});

					$('[name="content_'+modulID+'"]').val(contentdetail.join("|"));	   	
				}

		   		// ModulID's in der Reiehenfolge auslesen
		   		// ------------------------------------------------------------------------------
				if(modulSequence!="") {	
					modulSequence = modulSequence+':'+modulID;
				} else {
					modulSequence = modulID;
				}

				modulNumber++;
			}
	    });

	    var pagename = $('.js_admin_pagenameeditbox').html();

		$('.js_module_reihe').val(modulSequence);
		$('.js_admin_pagename').val(pagename);
		$( "#admin_form" ).submit();
	});

	function update_newsform() {
		newscontent = new Array();
		$('.editbox_news').find('[contenteditable="true"]').each(function(){
			newscontent.push($(this).html());
		});
		$('[name="news_headline"]').val(newscontent[0]);
		$('[name="news_shorttext"]').val(newscontent[1]);
	}


	$(document).on("click", ".js_insert_new_contentmodule", function(e) {

		var pos = $(this).attr('data-module-id');
		$('.insert_new_contentmodule_list').remove();

		$.ajax({
			type:'POST',
			url: g_basepath+'Load_contentmodulelist',
			data: "text=THE NEW TEXT MODULE<br>Loremipsum<br>dolor",
			success: function(msg) { 
				$(msg).insertAfter( $('.js_moduleresult_'+pos));
			},
			error: function() { alert("NÖ!"); },
			complete: function(){
				
			},
		});
	});	

	$(document).on("click", ".js_contentmodules_close", function(e) {
		e.preventDefault();
		$(this).parent().parent().remove();
	});

	$(document).on("click", ".js_new_contentmodule", function(e) {
		e.preventDefault();

		var pos = $(this).parent().parent().parent().parent().attr('data-module-id');
		var moduleListID = $(this).attr('data-modulelist-id');
		var op = getUrlParameter('op'); 

		//--- Modul Panel ausblenden
		$(this).parent().parent().parent().remove();

		//--- Modul per AJAX laden
		$.ajax({
			type:'POST',
			url: g_basepath+'Load_contentmodule',
			data: { 
				text: "THE NEW TEXT MODULE<br>Loremipsum dolor", 
				moduleID : moduleListID,
				itemnumber: "0"+new_module, 
				op: op 
			},
			success: function(msg) { 
				$(msg).insertAfter( $('.js_moduleresult_'+pos).closest('.js_adminlayoutmodul_'+pos)); 
				$('#content').sortable("refresh");
				new_module++;
			},
			error: function() { alert("NÖ!"); },
			complete: function(){
				
			},
		});
		
	});

	$(document).on("click", ".admin_layoutmodul_panel_delete", function(e) {

		//--- Modul Panel ausblenden
		var modulID = $(this).parent().attr('data-module-id');
		$('[data-module-id="'+modulID+'"]').remove(); 
		
	});


	/*--------------------------------------------------------------*/
	//  OPEN / CLOSE Metaform
	/*--------------------------------------------------------------*/


	$(document).on("click", ".js_admin_metamenue_open", function(e) {
		e.preventDefault();
		$('#admin_pagemetaform').removeClass('admin_hide');
	});

	$(document).on("click", ".js_admin_metamenue_close", function(e) {
		e.preventDefault();
		$('#admin_pagemetaform').addClass('admin_hide');
	});


	/*--------------------------------------------------------------*/
	//  DELETE DIALOG
	/*--------------------------------------------------------------*/

	$(document).on("click", ".js_admin_dialogbox", function(e) {
		e.preventDefault();

		var box = $(this).attr('data-box');
		var headtxt = $(this).attr('data-headtxt');
		var func = $(this).attr('data-func');
		var link = $(this).attr('href');

		$('#js_admin_dialog_'+box).removeClass('admin_hide');
		$('#js_admin_dialog_opt1').attr('href', link);
		$('#js_admin_dialogbox_headline').text(headtxt);
	});

	$(document).on("click", ".admin_closedialogbox", function(e) {
		e.preventDefault();
		$(this).parent().parent().addClass('admin_hide');
	});
	$(document).on("click", ".js_admin_closedialogbox", function(e) {
		e.preventDefault();
		$(this).parent().parent().addClass('admin_hide');
	});

	/*--------------------------------------------------------------*/
	//  DELETE DIALOG >> SPECIAL <<
	/*--------------------------------------------------------------*/

	$(document).on("click", ".js_admin_dialogbox_special", function(e) {
		e.preventDefault();

		var box = $(this).attr('data-box');
		var func = $(this).attr('data-func');
		var link = $(this).attr('href');

		var link_a = link+'&func='+func;
		var link_b = link;

		$('#js_admin_dialog_'+box).removeClass('admin_hide');
		$('#js_admin_dialog_opt1').attr('href', link_a);
		$('#js_admin_dialog_opt2').attr('href', link_b);
	});

	$(document).on("click", ".admin_closedialogbox", function(e) {
		e.preventDefault();
		$(this).parent().parent().addClass('admin_hide');
	});


	/*--------------------------------------------------------------*/
	//  OPEN / CLOSE Edit Lighbox
	/*--------------------------------------------------------------*/

	$(document).on("click", ".admin_layoutmodul_panel_edit", function(e) {
		e.preventDefault();

		$('#js_admin_lightbox').removeClass('admin_hide');
		$(document.body).addClass('admin_noscroll');

		var moduleID = $(this).attr('data-moduleid');
		var contentmoduleID = $(this).attr('data-contentmoduleid');
		var modul_type = $(this).attr('data-moduletype');
		var content = $(this).next('input').val();
		
		//alert("Yep! "+moduleID+"-"+contentmoduleID+" / "+modul_type+" / "+content);

		//--- Modul per AJAX laden
		$.ajax({
			type:'POST',
			url: g_basepath+'Load_moduleeditform',
			data: { 
				moduleID : moduleID,
				contentmoduleID : contentmoduleID,
				modul_type : modul_type,
				content : content 
			},
			success: function(msg) { 
				$('#js_admin_lightboxcontentarea').append(msg); 
				$('#admin_moduledit_imggal').sortable();
			},
			error: function() { alert("Sorry - jQuerry could not call the PHP function."+msg); },
			complete: function(){
				
			},
		});
		
	});

	$(document).on("click", "#js_admin_modulelightbox_close", function(e) {
		e.preventDefault();
		close_module_editlightbox();
	});

	$(document).on("click", "#js_admin_moduleedit_opensubform", function(e) {
		e.preventDefault();
		$('.admin_lightbox_subform').removeClass('admin_hide');
		$('.admin_lightbox_subform2').addClass('admin_hide');
		$('.admin_lightbox_mainform').addClass('admin_hide');
	});	
	$(document).on("click", "#js_admin_moduleedit_opensubform2", function(e) {
		e.preventDefault();
		close_module_settingsform();
		$('.admin_lightbox_subform2').removeClass('admin_hide');
	});	
	$(document).on("click", "#js_admin_moduleedit_closesubform", function(e) {
		e.preventDefault();
		close_module_settingsform();
	});	
	$(document).on("click", "#js_admin_modulesettings", function(e) {
		e.preventDefault();
		close_module_settingsform();
		$('#js_admin_modulsettingsform').removeClass('admin_hide');
	});	

	function close_module_editlightbox() {
		$('#js_admin_lightbox').addClass('admin_hide');
		$('#js_admin_lightboxcontentarea').replaceWith('<div class="admin_imageupload_bg" id="js_admin_lightboxcontentarea"></div>');
		$(document.body).removeClass('admin_noscroll');
	}
	
	function close_module_settingsform() {
		if($('.admin_lightbox_mainform').hasClass('admin_hide')) {	
			$('.admin_lightbox_mainform').removeClass('admin_hide');
			if(!$('#js_admin_modulsettingsform').hasClass('admin_hide')) {	
				$('#js_admin_modulsettingsform').addClass('admin_hide');
			}
			if(!$('.admin_lightbox_subform').hasClass('admin_hide')) {	
				$('.admin_lightbox_subform').addClass('admin_hide');
			}
			if(!$('.admin_lightbox_subform2').hasClass('admin_hide')) {	
				$('.admin_lightbox_subform2').addClass('admin_hide');
			}
		} else {
			$('.admin_lightbox_mainform').addClass('admin_hide');
			if(!$('#js_admin_modulsettingsform').hasClass('admin_hide')) {	
				$('#js_admin_modulsettingsform').addClass('admin_hide');
			}
			if(!$('.admin_lightbox_subform').hasClass('admin_hide')) {	
				$('.admin_lightbox_subform').addClass('admin_hide');
			}
			if(!$('.admin_lightbox_subform2').hasClass('admin_hide')) {	
				$('.admin_lightbox_subform2').addClass('admin_hide');
			}
		}
	}


	//  Modulbox Message
	/*--------------------------------------------------------------*/
	function module_show_message(message, display) {
		if(display=="") {
			display = ".admin_lightbox_mainform"; 
		}
		$('<div class="admin_globalmessages"><p class="admin_'+message[0]+'">'+message[1]+'</p></div>').prependTo(display);
        $( ".admin_globalmessages" ).delay(2000).fadeOut(1000, "swing");
	}

	//  special Module edit functions << IMAGE GALLERY >>
	/*--------------------------------------------------------------*/

	$(document).on("click", ".js_admin_moduleedit_imagedelete", function(e) {
		e.preventDefault();

		$(this).remove();

	});
	
	$(document).on("click", ".js_admin_moduleedit_addimage", function(e) {
		e.preventDefault();

		var v_imgid = $(this).attr('data-imgid');
		var v_path = $(this).attr('data-path');
		var i = $('#admin_moduledit_imggal').children().length;

		$(this).children().addClass("admin_active_image");
		$('#admin_moduledit_imggal').append('<li class="js_admin_moduleedit_imagedelete" id="slideshow_'+i+'" data-imgid="'+v_imgid+'"><img src="'+basepath+'frontend/images_cms/'+v_path+'" /></li>');
		$('#admin_moduledit_imggal').sortable("refresh");		

		var msg = "success:Bild wurde der Gallerie hinzugefügt";
		var message = msg.split(":");
		module_show_message(message, ".admin_lightbox_subform2");
	});

	$(document).on("click", "#js_admin_moduleedit_imggal_update", function(e) {
		e.preventDefault();

		var images ="";
		var images_html ="";
		var i = 1;
		var modulID = $(this).attr('data-moduleID');

		// --- UPDATEN
		$('#admin_moduledit_imggal').children().each(function(){
			
			if(i==1) {
	   			images_html = images_html+'<li class="slideshow_'+modulID+'_'+i+' active">'+$(this).html()+'</li>';
	   		} else {
	   			images_html = images_html+'<li class="slideshow_'+modulID+'_'+i+'">'+$(this).html()+'</li>';
	   		}
	   		images = images+'{img::'+ $(this).attr('data-imgid')+'}';
	   		i++;

	    });

		$('[data-slidehow-id="slideshow_'+modulID+'"]').replaceWith('<ul class="slideshow" data-slidehow-id="slideshow_'+modulID+'">'+images_html+'</ul>');
		$('[name="content_'+modulID+'"]').val(images);
		
		// --- Slide-Show initialisieren
		$('.slideshow').each(function() {
			slsh = $(this).attr('data-slidehow-id');
			itemcount = $(this).children().length;
			$('.js_'+slsh).find('.actualImg').html(1);
			$('.js_'+slsh).find('.allImages').html(itemcount);
		});

		close_module_editlightbox();
	});

	$(document).on("click", ".js_admin_moduleedit_addfile", function(e) {
		e.preventDefault();

		var v_fileid = $(this).attr('data-fileid');
		var v_name = $(this).attr('data-name');
		var v_format = $(this).attr('data-format');
		var v_size = $(this).attr('data-size');
		var v_icon = $(this).attr('data-icon');
		var i = $('#admin_moduledit_imggal').children().length;

		$('#admin_moduledit_imggal').append('<li class="file" id="slideshow_'+i+'" data-fileid="'+v_fileid+'" data-name="'+v_name+'" data-format="'+v_format+'" data-size="'+v_size+'" data-icon="'+v_icon+'"><img src="'+v_icon+'" /><p><strong>'+v_name+'</strong></p><p>'+v_format+' - '+v_size+'</p><div class="editpanel"><a href="#" class="js_admin_moduleedit_filedelete">delete</a><hr class="clear" /></li>');
		$('#admin_moduledit_imggal').sortable("refresh");		

		var msg = "success:Datei wurde der Liste hinzugefügt";
		var message = msg.split(":");
		module_show_message(message, ".admin_lightbox_subform2");
	});
	
	$(document).on("click", "#js_admin_moduleedit_downloadfile_update", function(e) {
		e.preventDefault();

		var files ="";
		var files_html ="";
		var i = 1;
		var modulID = $(this).attr('data-moduleID');

		// --- UPDATEN
		$('#admin_moduledit_imggal').children().each(function(){
			
   			files_html = files_html+'<li><a href="'+basepath+'frontend/files_cms/'+$(this).attr('data-name')+'" download><img src="'+basepath+'frontend/images/icons/icon_download.svg" /><p class="name">'+$(this).attr('data-name')+'</p><p class="desc">'+$(this).attr('data-format')+' Dokument / '+$(this).attr('data-size')+'</p></a></li>';
	   		files = files+'{file::'+ $(this).attr('data-fileid')+'}';
	   		i++;

	    });

		$('[data-download-id="download_'+modulID+'"]').replaceWith('<ul data-download-id="download_'+modulID+'">'+files_html+'</ul>');
		$('[name="content_'+modulID+'"]').val(files);
		
		close_module_editlightbox();
	});
	$(document).on("click", ".js_admin_moduleedit_filedelete", function(e) {
		e.preventDefault();

		$(this).parent().parent().remove();

	});

	//  Settings speichern
	/*--------------------------------------------------------------*/

	$(document).on("submit", "#js_admin_savesettings_ajax", function(e) {
		e.preventDefault();

		var formData = new FormData(this);
		var name = $('#js_admin_setting_name').val();
		var modulID = $('#js_admin_setting_moduleID').val();

		//--- Modul per AJAX laden
		$.ajax({
			type:'POST',
			url: g_basepath+'save_modulesettings',
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(msg){
				if(msg!="") {
					var message = msg.split(":");

					if(message[0]=="success") {
						close_module_settingsform();
					}
					module_show_message(message, "#js_admin_modulsettingsform");
				} else {
					var folder = 'images_cms/gallerie/'+name;
					$('[name="name_'+modulID+'"]').val(name);
					$('#js_admin_uploadtarget').val(folder);
					close_module_settingsform();
				}
            },
            error: function(formData){
                console.log("error");
                console.log(formData);
            }
		});
	});

	
	// Special Module edit functions << TEASER >>
	/*--------------------------------------------------------------*/
		
	$(document).on("click", ".admin_layoutmodul_panel_addnewteaser", function(e) {
		e.preventDefault();

		var i = $(this).parent().find("ul").children().length;
		var msg = '<li class="js_admin_teaseredit_'+i+'"><div class="admin_teaser_edit" data-itemID="'+i+'"></div><div class="admin_teaser_delete"></div><input type="hidden" name="js_admin_teaserimage_'+i+'" value="" /><a href="#"><figure><img src="'+basepath+'/frontend/images_cms/placeholder.jpg" alt="intro" /></figure><div class="subhead"><h3>Intro</h3><h2>Text</h2></div></a></li>';

		$(this).parent().find("ul").append(msg);
		$( ".datafacts" ).sortable({cancel: 'p'});
	});

	$(document).on("click", ".admin_teaser_edit", function(e) {
		e.preventDefault();

		$('#js_admin_lightbox').removeClass('admin_hide');
		$(document.body).addClass('admin_noscroll');
		icon_object = $(this);
		var teaserid = $(this).attr('data-itemID');

		//--- Modul per AJAX laden
		$.ajax({
			type:'POST',
			url: g_basepath+'Load_teaseredit',
			data: { 
				subline 	: $(this).parent().find('h3').text(),
				headline 	: $(this).parent().find('h2').text(),
				url 		: $(this).parent().find('a').attr('href'),
				image 		: $(this).parent().find('img').attr('src'),
				imageID 	: $(this).parent().find('[name=js_admin_teaserimage_'+teaserid+']').val(),
				teaserID 	: teaserid,
				moduleID 	: $(this).parent().parent().parent().parent().parent().attr('data-module-id')
			},
			success: function(msg) { 
				$('#js_admin_lightboxcontentarea').append(msg); 
			},
			error: function() { alert("Sorry - Icons konnten nicht geladen werden"); },
			complete: function(){
				
			},
		});
	});

	$(document).on("click", ".js_admin_changeteaserimage", function(e) {
		var imgid = $(this).attr('data-imgid');
		var img = $(this).children().attr('src');

		change_teaser_image(imgid, img);

		close_module_settingsform();
	});

	$(document).on("click", "#js_admin_moduleedit_teaser_update", function(e) {
		e.preventDefault();

		var moduleID = $(this).attr('data-moduleid');
		var teaserID = $('.js_teaser_editID').val();		
		var imageID = $('.js_teaser_imageID').val();		
		var sub = $('.js_teaser_sub').val();		
		var head = $('.js_teaser_head').val();		
		var link = $('.js_teaser_link').val();
		var img = $('.js_admin_aktteaserimg').attr('src');

		$('.js_adminlayoutmodul_'+moduleID).find('[name=js_admin_teaserimage_'+teaserID+']').val(imageID);
		$('.js_adminlayoutmodul_'+moduleID).find('.js_admin_teaseredit_'+teaserID).find('img').attr('src', img);
		$('.js_adminlayoutmodul_'+moduleID).find('.js_admin_teaseredit_'+teaserID).find('a').attr('href', link);
		$('.js_adminlayoutmodul_'+moduleID).find('.js_admin_teaseredit_'+teaserID).find('h3').text(sub);
		$('.js_adminlayoutmodul_'+moduleID).find('.js_admin_teaseredit_'+teaserID).find('h2').text(head);
		
		update_teaser_value(moduleID);
		close_module_editlightbox();
	});

	$(document).on("click", ".admin_teaser_delete", function(e) {
		var moduleID = $(this).parent().parent().parent().parent().parent().attr('data-module-id');
		$(this).parent().remove();
		update_teaser_value(moduleID);
	});

	
	function change_teaser_image(imgid, img) {
		$('.js_teaser_imageID').val(imgid);
		$('.teaser_form').find('img').attr('src', img);
	}
	function update_teaser_value(moduleID) {
		var string = "";
		$('.js_adminlayoutmodul_'+moduleID).find('ul').children().each(function() {
			var imgid = $(this).find('input').val();
			var sub = $(this).find('h3').text();
			var head = $(this).find('h2').text();
			var url = $(this).find('a').attr('href');
			string = string+'{'+imgid+'|'+sub+'|'+head+'|'+url+'}';
		});
		$('[name=content_'+moduleID+']').val(string);
	}

	// Special Module edit functions << TABLES >>
	/*--------------------------------------------------------------*/

	$(document).on("click", ".admin_layoutmodul_panel_addnewcell", function(e) {
		e.preventDefault();
		
		var msg = '<div class="col-1 cell"><div class="admin_table_delete admin_hide"></div><div class="admin_table_edit admin_hide"></div><p class="label" contenteditable="true">label</p><p class="fact" contenteditable="true">value</p></div>';
		$( msg ).insertBefore( $(this).parent().find('.clear') );
		$( ".datafacts" ).sortable({cancel: 'p'});
	});

	$(document).on("click", ".admin_table_delete", function(e) {
		$(this).parent().remove();
	});
	$(document).on("click", ".admin_table_delete", function(e) {
		$(this).parent().remove();
	});

	$(document).on("click", ".admin_table_edit", function(e) {
		e.preventDefault();

		$('#js_admin_lightbox').removeClass('admin_hide');
		$(document.body).addClass('admin_noscroll');
		icon_object = $(this);

		//--- Modul per AJAX laden
		$.ajax({
			type:'POST',
			url: g_basepath+'Load_inserticons',
			data: { 
				path : 'icons/'
			},
			success: function(msg) { 
				$('#js_admin_lightboxcontentarea').append(msg); 
			},
			error: function() { alert("Sorry - Icons konnten nicht geladen werden"); },
			complete: function(){
				
			},
		});
	});

	$(document).on("click", ".js_admin_insert_table_icon", function(e) {
		close_module_editlightbox();
		var img = $(this).attr("src");
		var code = '<img src="'+img+'" />';
		$( icon_object ).next('img').remove();
		$( code ).insertAfter( icon_object );
	});

	$(document).on("click", ".js_admin_tabledeleteicon", function(e) {
		e.preventDefault();
		close_module_editlightbox();
		$( icon_object ).next('img').remove();
	});


	$(document).on("click", "#js_admin_moduleedit_video_update", function(e) {
		e.preventDefault();

		i = 1;
		var modulID = $(this).attr('data-moduleID');
		
		$('.js_admin_tabbarcontent').children().each(function(e) { 
			if(!$(this).hasClass('admin_hide')) {
				type = $(this).attr('data-type');
				linkname = $('[name="link_'+i+'"]').val();
			}
			i++;
		});

		link = linkname.split("/");
		value = '{video::'+type+'::'+link[link.length-1]+'}';
		html = '<div class="responsive-video"><img src="'+basepath+'backend/images/contentmodule/icon_module_video.svg" /><p>Speichern Sie die Seite, um die Videovorschau zu sehen:<br/>Type: <strong>'+type+'</strong><br/>URL: <strong>'+linkname+'</strong></p></div>';
		
		$('[name="content_'+modulID+'"]').val(value);
		$('.js_adminlayoutmodul_'+modulID+' > div.row > div.responsive-video').replaceWith(html);
		close_module_editlightbox();
	});


	//  special Module edit functions << NEWS >>
	/*--------------------------------------------------------------*/

	$(document).on("click", ".js_admin_news_opendatetime", function(e) {
		e.preventDefault();

		$('#js_admin_lightbox').removeClass('admin_hide');
		$(document.body).addClass('admin_noscroll');

		var moduleID = $(this).attr('data-moduleid');
		var contentmoduleID = $(this).attr('data-contentmoduleid');
		var newsID = $(this).attr('data-id');
		var moduletype = $(this).attr('data-moduletype');
		
		//--- Modul per AJAX laden
		$.ajax({
			type:'POST',
			url: g_basepath+'Load_moduleadminform',
			data: { 
				moduleID : moduleID,
				contentmoduleID : contentmoduleID,
				id: newsID,
				modul_type: moduletype
			},
			success: function(msg) { 
				$('#js_admin_lightboxcontentarea').append(msg); 
			},
			error: function() { alert("NÖ!"); },
			complete: function(){
				
			},
		});
		
	});

	$(document).on("click", "#js_admin_newsdetails_update", function(e) {
		e.preventDefault();

		var datum = $('[name="datum"]').val();
		var zeit = $('[name="zeit"]').val();
		var wehr = $('[name="wehr"] :selected').text();
		var wehrID = $('[name="wehr"]').val();

		var datum_de = datum.split("-")
		var zeit_de = zeit.split(":")
		var interfacestring = datum_de[2]+"."+datum_de[1]+"."+datum_de[0]+" - "+zeit_de[0]+":"+zeit_de[1]+"h - "+wehr;

		$('[name="news_datetime"]').val(datum+" "+zeit);
		$('[name="news_wehrID"]').val(wehrID);
		$('.js_admin_news_opendatetime').text(interfacestring);
		close_module_editlightbox();

	});

	$(document).on("click", "#js_admin_imageupload", function(e) {
		e.preventDefault();

		$('#js_admin_lightbox').removeClass('admin_hide');
		$(document.body).addClass('admin_noscroll');

		var path = $(this).attr('data-path');
		var type = $(this).attr('data-type');
		var name = $(this).attr('data-imgname');
		var img = $(this).attr('data-imgtagtarget');

		//--- Modul per AJAX laden
		$.ajax({
			type:'POST',
			url: g_basepath+'Load_imageupload',
			data: { 
				img_name : name,
				folder : path,
				media_type : type,
				imgtag : img
			},
			success: function(msg) { 
				$('#js_admin_lightboxcontentarea').append(msg); 
			},
			error: function() { alert("NÖ!"); },
			complete: function(){
				
			},
		});
		
	});

	$(document).on("submit", "#js_admin_savesingleimage_ajax", function(e) {
		e.preventDefault();

		var newimage = basepath+'frontend/'+$('[name="folder"]').val()+'/';
		var formData = new FormData(this);
		$.each($("input[type=file]"), function(i, obj) {
       		$.each(obj.files,function(j, file){
           		formData.append('media_file['+j+']', file);
		    })
		});
	
		//--- Modul per AJAX laden
		$.ajax({
			type:'POST',
			url: g_basepath+'imageupload',
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(msg){
				
				var message = msg.split(":");

				if(message[0]=="success") {
					// Bild in die Liste einfügen
					d = new Date();
					$('#'+$('[name="imgtagtarget"]').val()+'').attr('src', newimage+message[3]+'.'+message[4]+'?'+d.getTime());
					close_module_editlightbox();
				} else {
					module_show_message(message, "");
				}
            },
            error: function(formData){
                console.log("error");
                console.log(formData);
            }
		});
	});


	/*--------------------------------------------------------------*/
	//  ALLGEMEINER HELFER
	/*--------------------------------------------------------------*/

	$(document).on("click", ".js_admin_changestageimage", function(e) {

		var image = $(this).attr('data-path');
		var moduleID = $(this).attr('data-moduleID');
		
		$('#stage').children().css('background-image', 'url('+basepath+'/frontend/images_cms/stages/'+image+')');  
		$('.js_admin_smallstage').val(image);
		$('.js_admin_smallstage_moduleID').val(moduleID);
		close_module_editlightbox();

	});

	$(document).on("submit", "#js_admin_savestage_ajax", function(e) {
		e.preventDefault();

		var media_type = $('[name="media_type"]').val();
		var formData = new FormData(this);
		$.each($("input[type=file]"), function(i, obj) {
       		$.each(obj.files,function(j, file){
           		formData.append('media_file['+j+']', file);
		    })
		});

		console.log(formData);

		//--- Modul per AJAX laden
		$.ajax({
			type:'POST',
			url: g_basepath+'imageupload',
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(msg){
				var message = msg.split(":");
				$('.admin_lightbox_subform').addClass('admin_hide');
				$('.admin_lightbox_mainform').removeClass('admin_hide');

				if(message[0]=="success") {
					// Bild in die Liste einfügen

					if(media_type == "image") {	
						var i = $('#admin_moduledit_imggal').children().length;
						$('#admin_moduledit_imggal').append('<li class="js_admin_changestageimage" id="slideshow_'+i+'" data-imgid="'+message[2]+'" data-path="'+message[3]+'.'+message[4]+'"><img src="'+basepath+'frontend/images_cms/'+message[5]+message[3]+'.'+message[4]+'" /></li>');
					}
				}
				console.log(message);
				module_show_message(message, "");
            },
            error: function(formData){
                console.log("error");
                console.log(formData);
            }
		});
	
	});

	/*--------------------------------------------------------------*/
	//  Text-Editor
	/*--------------------------------------------------------------*/
	
	$(document).on("mousedown", ".js_admin_editorbutton", function(e) {
        event.preventDefault();
    });
	$(document).on("click", ".js_admin_insertlink", function(e) {
		e.preventDefault();

		var selObj = window.getSelection();
		var selectedText = selObj.toString();
		editor_selRange = saveSelection();

		if(selObj.baseNode.parentNode.tagName === 'A') {
			var url = selObj.baseNode.parentNode.getAttribute('href');
			var target = selObj.baseNode.parentNode.getAttribute('target');
		} else {
			var url = ''; 
			var target = '';	
		}
		
		$('#js_admin_lightbox').removeClass('admin_hide');
		$(document.body).addClass('admin_noscroll');

		//--- Modul per AJAX laden
		$.ajax({
			type:'POST',
			url: g_basepath+'Load_editorfunctions',
			data: { 
				formtype: 'link',
				link_url: url,
				link_target: target
			},
			success: function(msg) { 
				$('#js_admin_lightboxcontentarea').append(msg); 
				$('.js_admin_linkeditor_text').val(selectedText);
				//alert("Selected Text: "+selectedText);
			},
			error: function() { 
				alert("Sorry - jQuerry could not call the PHP function."); },
			complete: function(){ },
		});
	});
	$(document).on("click", "#js_admin_linkeditor_save", function(e) {
		e.preventDefault();
	
		restoreSelection(editor_selRange);

		var link = $('.js_admin_linkeditor_link').val();
		var target = $('.js_admin_linkeditor_target').val();
		var text = $('.js_admin_linkeditor_text').val();
		
		if(link!="" && text!="") {
			var text = '<a href="'+link+'" class="textlink" target="'+target+'">'+text+'</a>';
			//alert(text);
			insertTextAtCursor(text, 'link');
			close_module_editlightbox();
		}
	});
	$(document).on("click", "#js_admin_linkeditor_removelink", function(e) {
		e.preventDefault();
		restoreSelection(editor_selRange);
		var selObj = window.getSelection();
		stripBBCode(selObj);
		close_module_editlightbox();
	});		
	$(document).on("click", ".js_admin_insertbold", function(e) {
		e.preventDefault();

		var selObj = window.getSelection();
		var selectedText = selObj.toString();

		if(selectedText!="") {
			if(selObj.baseNode.parentNode.tagName === 'STRONG') {
				stripBBCode(selObj);
			} else {
				insertBBCode(selObj, '<strong>'+selectedText+'</strong>');
			}
		}
		selObj.removeAllRanges();
	});
	$(document).on("click", ".js_admin_insertkursiv", function(e) {
		e.preventDefault();

		var selObj = window.getSelection();
		var selectedText = selObj.toString();
		
		if(selectedText!="") {
			if(selObj.baseNode.parentNode.tagName === 'I') {
				stripBBCode(selObj);
			} else {
				insertBBCode(selObj, '<i>'+selectedText+'</i>');
			}
		}
		selObj.removeAllRanges();
	});
	$(document).on("click", ".js_admin_insertunderline", function(e) {
		e.preventDefault();

		var selObj = window.getSelection();
		var selectedText = selObj.toString();
		
		if(selectedText!="") {
			if(selObj.baseNode.parentNode.tagName === 'U') {
				stripBBCode(selObj);
			} else {
				insertBBCode(selObj, '<u>'+selectedText+'</u>');
			}
		}
		selObj.removeAllRanges();
	});

	function stripBBCode(selObj) {
	    var range = selObj.getRangeAt(0);
	    var node = $(range.commonAncestorContainer);
        node.unwrap();
	}
	function insertBBCode(selObj, selectedText) {
		range = selObj.getRangeAt(0);
        range.deleteContents();
        range.insertNode(range.createContextualFragment(selectedText));
	}

	/*--------------------------------------------------------------*/
	//  ALLGEMEINER HELFER
	/*--------------------------------------------------------------*/

	function getUrlParameter(sParam) {
	    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
	        sURLVariables = sPageURL.split('&'),
	        sParameterName,
	        i;

	    for (i = 0; i < sURLVariables.length; i++) {
	        sParameterName = sURLVariables[i].split('=');

	        if (sParameterName[0] === sParam) {
	            return sParameterName[1] === undefined ? true : sParameterName[1];
	        }
	    }
	};

	function saveSelection() {
	    if (window.getSelection) {
	        sel = window.getSelection();
	        if (sel.getRangeAt && sel.rangeCount) {
	            return sel.getRangeAt(0);
	        }
	    } else if (document.selection && document.selection.createRange) {
	        return document.selection.createRange();
	    }
	    return null;
	}
	function restoreSelection(range) {
	    if (range) {
	        if (window.getSelection) {
	            sel = window.getSelection();
	            sel.removeAllRanges();
	            sel.addRange(range);
	        } else if (document.selection && range.select) {
	            range.select();
	        }
	    }
	}
	function insertTextAtCursor(text, type) {
	    var sel, range, html;
	    if (window.getSelection) {
	        sel = window.getSelection();
	        if (sel.getRangeAt && sel.rangeCount) {
	        	check = sel.baseNode.parentNode.tagName;
	            range = sel.getRangeAt(0);
	            // remove the old content
	            range.deleteContents();
	            // if a link is already there - remove it
		        if(type === 'link' && check==='A') {
		            var node = $(range.commonAncestorContainer);
	        		node.unwrap();
	        	}
	            var textNode = text; 
	           	range.insertNode(range.createContextualFragment(textNode));
   	    		
   	    		sel.removeAllRanges();
	        }
	    } else if (document.selection && document.selection.createRange) {
	        range = document.selection.createRange();
	        range.pasteHTML(text);
	        range.select();
	    }
	}

})(jQuery);