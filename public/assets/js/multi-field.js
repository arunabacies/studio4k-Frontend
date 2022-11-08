(function(window, undefined) {
    'use strict';
  
    /*
    NOTE:
    ------
    PLACE HERE YOUR OWN JAVASCRIPT CODE IF NEEDED
    WE WILL RELEASE FUTURE UPDATES SO IN ORDER TO NOT OVERWRITE YOUR JAVASCRIPT CODE PLEASE CONSIDER WRITING YOUR SCRIPT HERE.  */
  
    $(document).ready(function(){
        var maxField = 15; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.multi-field'); //Input field wrapper
        var x = 1; //Initial field counter is 1
        // var fieldHTML = '<div class="row"><div class="col-xs-6"><label>Key</label><input type="text" name="otherTagsValue[' + x +'][Key]" class="an-form-control" /></div><div class="col-xs-5"><label>Value</label><input type="text" name="otherTagsValue[' + x +'][Value]" class="an-form-control" /></div><div class="col-xs-1" style="margin-top: 25px;"><a href="javascript:void(0);" class="remove_button" title="Add field"><i style="font-size: 20px;font-weight: bold;" class="icon-minus"></i></a></div></div>'; //New input field html 
        
        //Once add button is clicked
        $(addButton).click(function(){
            var modal= $(this).closest('.modal').attr('id');
            
            
            //Check maximum number of input fields
            var str = $('.multi-field div.mt-1:last').find('.r-name').attr('name');
            console.log(str);
            var params = str.split('[');
            console.log(str);
            str = params[1].split(']');
            
            var l =str[0];
            console.log(l);
            l++;
            var assigned_users = JSON.parse($('.create-session').attr('assigned-users'));
            var html = '';
            html += '<div class="row mt-1 new-room">'+
            '<input class="form-control"  type="text"  name="rooms['+ l+'][roomId]" value="" hidden>' +
          '<div class="col-md-5"> ' +
              '<label>Room</label> ' +
                '<input class="form-control r-name" required type="text"  name="rooms['+ l+'][name]">' +
            '</div>' +
            '<div class="col-md-6">' +
              '<label>Users</label>'+
              
                '<select class="select2 form-control assign-users" multiple="multiple" data-live-search="true" name="rooms['+ l+'][id][]">';
    
                assigned_users.forEach(element => {
                  html += '<option value="'+ element.user_id +'">'+ element.name +'</option>' ;
                });
                
                    
                  
              html +=  '</select>'+  
              
            '</div>'+
            '<div class="col-md-1 text-right" style="margin-top:20px;"><a href="javascript:void(0);" class="remove_button btn btn-icon btn-light-danger" title="Remove field"><i style="top:3px;" class="bx bx-minus"></i></a></div>' +
            '<div class="form-group"><input type="checkbox" name="rooms['+ l+'][broadcasttoNDI]" checked style="margin-left:15px;"> Broadcast to NDI</div>'+
          '</div>';
    
            if(l < maxField){ 
                // x++; //Increment field counter
                $('#'+modal+' .multi-field ').append(html); //Add field html
                $('#'+modal+' .multi-field .select2').select2();
            }
            
        });
    
        // var p= 1;
        // Add Presenters
        $('.add_presenter_button').click(function(){
            var str = $('#generateLink div.presenter-block:last').find('.p-name').attr('name');
            
            if(str !== undefined && str !== null && str !== "" ){
                var params = str.split('[');
                str = params[1].split(']');
                
                var p =str[0];
                p++;
            }else{
                var p = 0;
            }
            var html = '<div class="row presenter-block">' +
                        
                            '<div class="col-md-11 presenter-detils">' +
                                '<div class="row">' +


                                '<div class="col-8">'+
                                    '<div class="row">'+
                                        '<div class="form-group col-12">'+
                                            '<label> Name</label> '+
                                            '<input id="firstPresenterId" class="form-control" type="text" name="presenter['+p+'][id]" hidden>' + 
                                            '<input class="form-control p-name" required type="text" placeholder="Name" name="presenter['+ p +'][name]">'+
                                        '</div>'+
                                        '<div class="form-group col-12">'+
                                            '<label> Email</label> '+ 
                                            '<input class="form-control p-email" required type="text" placeholder="Email" name="presenter['+ p +'][email]">'+
                                        '</div>'+
                                        '<div class="row url-details" style="display:none;">'+
                                            '<div class="col-md-6">'+
                                                '<div class="form-group" style="margin-bottom:0px;margin-left: 15px;">'+
                                                    '<div style="cursor:pointer" class="badge badge-pill d-inline-flex align-items-center badge-light-warning copy-btn"><i class="bx bx-copy font-size-small mr-50"></i><small class="text-warning">Click Here to Copy Url</small></div>'+  
                                                    '<input class="form-control url copy-to-clipboard" required type="text" placeholder="Url" name="presenter['+ p +'][uuid]" style="height:0;opacity:0;">'+
                                                '</div>'+
                                            '</div>'+
                                            '<div class="col-md-6">'+
                                                '<div class="form-group" style="margin-bottom:0px;">'+
                                                    '<div style="height: 0;opacity: 0;" class="badge badge-pill d-inline-flex align-items-center badge-light-warning copy-btn"><i class="bx bx-copy font-size-small mr-50"></i><small class="text-warning">Click Here to Copy Password</small></div>'+
                                                    '<input class="form-control password copy-to-clipboard" required type="text" placeholder="Password" name="presenter['+ p +'][password]" style="height:0;opacity:0;">'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+

                                        '<div class="form-group col-12">'+
                                            '<label> Avatar</label> '+ 
                                            '<input class="form-control "  type="text"  name="presenter['+ p +'][avatar_file_name]" hidden>'+
                                            '<input type="text" class="remove-avatar-file-name" name="presenter['+p+'][remove_avatar_file_name]" value="" hidden>'+
                                            
                                            '<input class="form-control "  type="file"  name="presenter['+ p +'][avatar]">'+
                                        '</div>'+
                                    '</div>'+
                                
                                '</div>'+
                                '</div>'+
                              
                            '</div>'+
                            '<div class="col-md-1" style="margin-top: 25px;">'+
                                
                                '<button type="button" class="btn btn-icon btn-danger remove_presenter_button"><i data-toggle="popover" data-placement="top" data-trigger="hover" data-container="body" data-content="Click Here to delete this row" style="top:3px;" class="bx bx-x"></i></button>'+
                            '</div>'+
                            
                            
                            
                        '</div>';
            if(p < maxField){ 
                // p++; //Increment field counter
                $('#generateLink .container-fluid').append(html); //Add field html
                // $('.multi-field .selectpicker').selectpicker('refresh');
            }
        })
        
        //Once remove button is clicked
        $(wrapper).on('click', '.remove_button', function(e){
            // console.log("remove");
            // e.preventDefault();
            $(this).parent().parent('.row').remove(); //Remove field html
            x--; //Decrement field counter
        });
        // '.remove_presenter_button'
        $(document).on('click', '.remove_presenter_button', function(e){
            $('#generateLink .generate-link-error').hide();
            // console.log("testtt");
            // console.log($(this).parent());
            $(this).parent().parent('.presenter-block').remove(); //Remove field html
            // x--; //Decrement field counter
        });
        
    });
    
  })(window);

