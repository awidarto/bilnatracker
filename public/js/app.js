    // shared functions for dynamic table
    function addTableRow(table)
    {
        // clone the last row in the table
        var $tr = $(table).find('thead tr').clone();

        var trow = $('<tr></tr>');

        $tr.find('input').each(function(){
            console.log(this);
	        var dt = $('<input type="text">').attr('name',$(this).attr('name')+'[]').attr('value',$(this).val()).attr('class',$(this).attr('class')).attr('readonly','readonly');
            trow.append($('<td></td>').append(dt));
        })

        var act = $('<td><span class="btn del" style="cursor:pointer" ><b class="icon-minus-alt"></b></span></td>');

        trow.append(act);

        // append the new row to the table
        $(table).find('tbody').append(trow);

        $(table).find('thead input').val('');

    }

    /*
    $('table').on('click','.del',function(){
        console.log($(this).closest('tr').html());
        $(this).closest('tr').remove();
    });
    */

	function string_to_slug(str) {
		str = str.replace(/^\s+|\s+$/g, ''); // trim
		str = str.toLowerCase();

		// remove accents, swap ñ for n, etc
		var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
		var to   = "aaaaeeeeiiiioooouuuunc------";
		for (var i=0, l=from.length ; i<l ; i++) {
			str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
		}

		str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
		.replace(/\s+/g, '-') // collapse whitespace and replace by -
		.replace(/-+/g, '-'); // collapse dashes

		return str;
	}

    $(document).ready(function(){

        accounting.settings = {
            currency: {
                symbol : 'IDR',   // default currency symbol is '$'
                format: '%s %v', // controls output: %s = symbol, %v = value/number (can be object: see below)
                decimal : ',',  // decimal point separator
                thousand: '.',  // thousands separator
                precision : 2   // decimal places
            },
            number: {
                precision : 0,  // default precision on numbers is 0
                thousand: '.',
                decimal : ','
            }
        }

    	//base = 'http://localhost/pnu/public/';

    	var sharelist = {};

        $('.eventdate').daterangepicker({
                maxDate: moment().add('months',12)
            },function(start, end){
                if ( typeof(start) !== "undefined" && start !== null && start !== '' ) {
                    $('#fromDate').val(start.format('YYYY-MM-DD'));
                    $('#toDate').val(end.format('YYYY-MM-DD'));
                }
            });

        $('.eventdate').on('show',function(ev, picker){
                var start = $('#fromDate').val();
                if ( typeof(start) !== "undefined" && start !== null && start !== '' && start !== 'invalid date' ) {
                    picker.setStartDate(moment( $('#fromDate').val(), 'YYYY-MM-DD' ));
                    picker.setEndDate(moment( $('#toDate').val(), 'YYYY-MM-DD' ));
                }else{
                    picker.setStartDate(moment());
                    picker.setEndDate(moment().add('days',3));
                }
        });

        $('.filterdaterangepicker').daterangepicker({
            opens:'right',
            format:'DD-MM-YYYY'
        });

        $('.daterangespicker').daterangepicker({
            format:'DD-MM-YYYY'
        });

        $('.datetimerangepicker').daterangepicker({
            format:'DD-MM-YYYY hh:mm:ss',
            timePicker: true,
            timePicker12Hour: false,
            timePickerSeconds: true
        });

		$('.pop').click(function(){
			var _id = $(this).attr('id');

			var _rel = $(this).attr('rel');

			$.fancybox({
				type:'iframe',
				href: base + '/' + _rel + '/' + _id,
				autosize: true
			});

		})

		$('.tag_email_inline').tagsInput({
			'autocomplete_url': base + 'ajax/email',
		   	'height':'80px',
		   	'width':'100%',
		   	'interactive':true,
		   	'onChange' : function(c){

		   		},
		   	'onAddTag' : function(t){
		   			console.log(t);
		   		},
		   	'onRemoveTag' : function(t){
		   			console.log(t);
		   		},
		   	'defaultText':'add email',
		   	'removeWithBackspace' : true,
		   	'minChars' : 0,
		   	'maxChars' : 0, //if not provided there is no limit,
		   	'placeholderColor' : '#666666'
		});


		$('.tag_email').tagsInput({
			'autocomplete_url': base + 'ajax/email',
			'autocomplete':{
				'select':function(event, ui){

					if(_.indexOf(sharearray,ui.item.id) < 0){
						sharearray.push(ui.item.id);
					}

					console.log(sharearray);

					var sh = $('#shared').val();

					if(sh == ''){
						$('#shared').val(ui.item.id);
					}else{
						$('#shared').val(sh + ',' + ui.item.id);
					}
				}
			},
		   	'height':'100px',
		   	'width':'100%',
		   	'interactive':true,
		   	'onChange' : function(c){
		   			console.log(c);
		   		},
		   	'onAddTag' : function(t){
		   			console.log(t);
		   		},
		   	'onRemoveTag' : function(t){
		   			console.log(t);
		   		},
		   	'defaultText':'add email',
		   	'removeWithBackspace' : true,
		   	'minChars' : 0,
		   	'maxChars' : 0, //if not provided there is no limit,
		   	'placeholderColor' : '#666666'
		});

		$('.tag_shared').tagsInput({
			'autocomplete_url': base + 'ajax/email',
			'autocomplete':{
				'select':function(event, ui){
					sharelist[ui.item.value] = ui.item.id;
				}
			},
		   	'height':'100px',
		   	'width':'100%',
		   	'interactive':true,
		   	'onChange' : function(c){
		   			console.log(sharelist);
		   		},
		   	'onAddTag' : function(t){
					sharelist[t] = '';
		   		},
		   	'onRemoveTag' : function(t){
					delete sharelist[t];
		   		},
		   	'defaultText':'add email',
		   	'removeWithBackspace' : true,
		   	'minChars' : 0,
		   	'maxChars' : 0, //if not provided there is no limit,
		   	'placeholderColor' : '#666666'
		});

		$('.tag_rev').tagsInput({
			'autocomplete_url': base + 'ajax/rev',
		   	'height':'100px',
		   	'width':'100%',
		   	'interactive':true,
		   	'onChange' : function(c){

		   		},
		   	'onAddTag' : function(t){
		   			console.log(t);
		   		},
		   	'onRemoveTag' : function(t){
		   			console.log(t);
		   		},
		   	'defaultText':'add title',
		   	'removeWithBackspace' : true,
		   	'minChars' : 0,
		   	'maxChars' : 0, //if not provided there is no limit,
		   	'placeholderColor' : '#666666'
		});

		$('.tag_keyword').tagsInput({
			'autocomplete_url': base + 'ajax/tag',
		   'height':'100px',
		   'width':'100%',
		   'interactive':true,
		   'onChange' : function(c){

		   		},
		   'onAddTag' : function(t){
		   			console.log(t);
		   		},
		   'onRemoveTag' : function(t){
		   			console.log(t);
		   		},
		   'defaultText':'add tag',
		   'removeWithBackspace' : true,
		   'minChars' : 0,
		   'maxChars' : 0, //if not provided there is no limit,
		   'placeholderColor' : '#666666'
		});


		$('.auto_user').autocomplete({
			source: base + 'ajax/email',
			select: function(event, ui){
				$('#user_id').val(ui.item.id);
				$('#user_name').val(ui.item.label);
			}
		});

		$('.auto_userdata').autocomplete({
			source: base + 'ajax/userdata',
			select: function(event, ui){
				$('#emp_user_id').val(ui.item.id);
				$('#emp_email').val(ui.item.userdata.email);

				$('#emp_jobtitle').val(ui.item.userdata.employee_jobtitle);

				$('#emp_department').select2('val',ui.item.userdata.department);

				$('#emp_mobile').val(ui.item.userdata.mobile);
				$('#emp_phone').val(ui.item.userdata.home);
				$('#emp_street').val(ui.item.userdata.street);
				$('#emp_city').val(ui.item.userdata.city);
				$('#emp_zip').val(ui.item.userdata.zip);
			}
		});

		$('.auto_userdatabyemail').autocomplete({
			source: base + 'ajax/userdatabyemail',
			select: function(event, ui){
				$('#acc_user_id').val(ui.item.id);
				$('#acc_fullname').val(ui.item.userdata.fullname);

				$('#acc_username').val(ui.item.userdata.username);

                $('#acc_designation').val(ui.item.userdata.designation);

				//$('#acc_department').select2('val',ui.item.userdata.department);

			}
		});

        $('.auto_userdatabyname').autocomplete({
            source: base + 'ajax/userdatabyname',
            select: function(event, ui){
                $('#acc_user_id').val(ui.item.id);
                $('#acc_fullname').val(ui.item.userdata.fullname);

                $('#acc_username').val(ui.item.userdata.username);

                $('#acc_designation').val(ui.item.userdata.designation);

                //$('#acc_department').select2('val',ui.item.userdata.department);

            }
        });

		$('.auto_idbyemail').autocomplete({
			source: base + 'ajax/useridbyemail',
			select: function(event, ui){
				$('#emp_user_id').val(ui.item.id);
				$('#emp_fullname').val(ui.item.userdata.fullname);

				$('#emp_jobtitle').val(ui.item.userdata.employee_jobtitle);

				$('#emp_department').select2('val',ui.item.userdata.department);

				$('#emp_mobile').val(ui.item.userdata.mobile);
				$('#emp_phone').val(ui.item.userdata.home);
				$('#emp_street').val(ui.item.userdata.street);
				$('#emp_city').val(ui.item.userdata.city);
				$('#emp_zip').val(ui.item.userdata.zip);

			}
		});

		$('.auto_group').autocomplete({
			source: base + 'ajax/group',
			select: function(event, ui){
				$('#groupid').val(ui.item.id);
			}
		});

        /*
		$('.autocomplete_product').autocomplete({
            source: function (request, response) {
                $.ajax({
					url: base + 'ajax/product',
                    data: { q: request.term, maxResults: 10 },
                    dataType: 'json',
                    success: function (data) {

                        response($.map(data, function (item) {
                            return {
                                value: item.id,
                                avatar: item.pic,
                                title: item.label,
                                description: item.description,
                                id: item.id
                            };
                        }))
                    }
                })
            },
            select: function (event, ui) {
            	var id = this.id;

            	console.log(ui);

            	$('#' + id + '_id').val(ui.item.id);

                return false;
            }
        });

		$('.autocomplete_product').each(function() {
			$(this).data('uiAutocomplete')._renderItem = function (ul, item) {
		        var inner_html = '<a><div class="list_item_container">'
		        + '<div class="image">' + item.avatar + '</div>'
		        + '<div class="description span4">'
		        + '<div class="label"><h5>' + item.title + '</h5></div>'
		        + '<p>' + item.description + '</p></div></div></a>';
	            return $('<li></li>')
	                    .data("item.autocomplete", item)
	                    .append(inner_html)
	                    .appendTo(ul);
	        };
		});
        */

		$('.autocomplete_product_link').autocomplete({
            source: function (request, response) {
                $.ajax({
					url: base + 'ajax/product',
                    data: { q: request.term, maxResults: 10 },
                    dataType: 'json',
                    success: function (data) {

                        response($.map(data, function (item) {
                            return {
                                value: item.link,
                                avatar: item.pic,
                                title: item.label,
                                description: item.description,
                                id: item.id
                            };
                        }))
                    }
                })
            },
            select: function (event, ui) {

                return false;
            }
        });

		$('.autocomplete_product_link').each(function() {
			$(this).data('uiAutocomplete')._renderItem = function (ul, item) {
		        var inner_html = '<a><div class="list_item_container">'
		        + '<div class="image">' + item.avatar + '</div>'
		        + '<div class="description span4">'
		        + '<div class="label"><h5>' + item.title + '</h5></div>'
		        + '<p>' + item.description + '</p></div></div></a>';
	            return $('<li></li>')
	                    .data("item.autocomplete", item)
	                    .append(inner_html)
	                    .appendTo(ul);
	        };
		});






    });