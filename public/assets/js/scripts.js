/*
*  File name: Scritps.js
*  All main scripts are here
*/

(function($) {
  'use strict';

  var tmzn = $("#gttz").text();
  var baseUrl = $("#baseUrl").val();
  var chimeUrl = 'https://9uv9otko19.execute-api.us-east-1.amazonaws.com/Prod/';
  
  $('#demo,#editEventTime').daterangepicker({
    "singleDatePicker": true,
    "timePicker": true,
    locale: {
      format: 'M/DD/YYYY hh:mm A'
    },
    timeZone: tmzn
    // "startDate": "03/24/2021",
    // "endDate": "03/30/2021"
  });

  // $(".selectpicker").select2();
 
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Delete 
  $(document).on('click', '.delete', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var projectId = $(this).attr('project-id');
    
    var url = $(this).data('url');
    var name = $(this).data('name');
    var _this = $(this);

    Swal.fire({
      title: "Are you sure?",
      text: `You want to Delete ${name}`,
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#e53735",
      confirmButtonText: "Remove",
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      preConfirm: (login) => {
        $.ajax({
          type: "POST",
          url: url,
          data: {id:id,projectId : projectId},
          success: function (response) {
                                
            var resp = JSON.parse(response);
            
            if(resp.message == "Success") {
              Swal.fire({ type: "success", title: "Removed!", text: `${name}` + " has been removed.", confirmButtonClass: "btn btn-success" });

              
             
              //  _this.closest('.col-md-6').remove();
              location.reload();
            } else if(resp.status == 401) {
              window.location = baseUrl;
            }else {
            //   swal({
            //     title: 'Opps...',
            //     text: resp.message,
            //     type: 'error',
            //     timer: '1500'
            // })
            }
  
        
          }         
        });
      },
      allowOutsideClick: () => !Swal.isLoading()
    },
    );
          
     
    // });
  });

  if($('#gttz').length > 0 ) {
    
    if(tmzn !== null && tmzn !== undefined){

      console.log(moment().tz(tmzn).format("DD-MM-YYYY h:mm:ss A"))
      var dt = moment().format();
      console.log(dt);
      var m = utc(dt, "DD-MM-YYYY h:mm:ss A");
      console.log(m)
      // console.log(m.clone().local().format("DD-MM-YYYY h:mm:ss A"))
      console.log(moment().tz(tmzn).format("DD-MM-YYYY h:mm:ss A"))
      // var nwdt = moment.tz("2021-12-09 10:40", "America/New_York");  // 5am PDT
      // console.log(nwdt);

      var offset = tz(tmzn).format('Z z');
     
      var newoff = offset.slice(0, 1) + offset.slice(2);
      newoff = newoff.slice(0, 6);
      newoff = newoff.replace(/:/g, '.');
      
      var setdt = tz(tmzn).format('dddd, MMMM D, YYYY mm:hh');
      console.log(setdt)
      $('#gttz small').text(setdt);
    }
  }

  // Delete Event
  $(document).on('click', '.deleteEvent', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var url = $(this).data('url');
    var name = $(this).data('name');
    var projectId = $(this).attr('project-id');
    var _this = $(this);
    
    Swal.fire({
      title: "Are you sure?",
      text: `You want to Delete ${name}`,
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#e53735",
      confirmButtonText: "Delete",
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      preConfirm: (login) => {
        $.ajax({
          type: "POST",
          url: url,
          data: {'id':id,'projectId':projectId},
          success: function (response) {
                                
            var resp = JSON.parse(response);
            if(resp.message == "Success") {
             Swal.fire({ type: "success", title: "Deleted!", text: `${name}` + " has been deleted.", confirmButtonClass: "btn btn-success" });

              // swal("Deleted!", `${name}`, "success");
              _this.closest('tr').remove();
              location.reload();
            } else if(resp.status == 401) {
              window.location = baseUrl;
            } else {
            //   swal({
            //     title: 'Opps...',
            //     text: resp.message,
            //     type: 'error',
            //     timer: '1500'
            // })
            }
  
          },
          error: function (response) {
            
            // swal({
            //     title: 'Opps...',
            //     text: response.message,
            //     type: 'error',
            //     timer: '1500'
            // })
        }         
        // });
      });
      },
      allowOutsideClick: () => !Swal.isLoading()
    },
    );
  });

  /** Send mail to presenters */
  $(document).on('click', '.send-mail-button', function (e) {
    e.preventDefault();
    var presenters = JSON.parse($(this).attr('presenters'));
    var event_id = $(this).attr('session-id');
    var url = $(this).attr('url');
    var _this = $(this);
    var presenter= [];
    
    Swal.fire({
      title: "Are you sure?",
      text: `You want to Send Mail`,
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#e53735",
      confirmButtonText: "Send Mail",
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      preConfirm: (login) => {
        if(presenters.length != 0) {
          for(var i= 0; i<presenters.length;i++) {
            console.log(presenters[i]['name'])
            presenter.push({"link":chimeUrl+"?ExternalUserId="+ presenters[i]['external_user_id']+"&EventId="+ event_id+ "&p="+ presenters[i]['password'], 
            "password" : presenters[i]['password'], 
            'email': presenters[i]['email'] });
          }
        }

        $.ajax({
          type: "POST",
          url: url,
          data: {id:event_id,presenter : presenter},
          success: function (response) {
                                
            var resp = JSON.parse(response);
            if(resp.message == "Success") {
             Swal.fire({ type: "success", title: "Email Sent Successfully!", 
                          text:  "Message has been sent successfully.", 
                          confirmButtonClass: "btn btn-success" });

            } else if(resp.status == 401) {
              window.location = baseUrl;
            } else {
              swal({
                title: 'Opps...',
                text: resp.message,
                type: 'error',
                timer: '1500'
            })
            }
  
          },
          error: function (response) {
            // console.log(response);
            swal({
                title: 'Opps...',
                text: response.message,
                type: 'error',
                timer: '1500'
            })
          }         
        });
        
      },
      allowOutsideClick: () => !Swal.isLoading()
    },
    );
      
  });

  /** Copy url from add presenter modal */
  $("#generateLink").on("click", ".copy-btn", function(){ 

    $(this).parent().find(".copy-to-clipboard").select(),document.execCommand("copy");
    $(this).closest('#generateLink').find('.alert-block').show();
    $(".alert-fade").delay(1000).fadeOut('slow');
    
  });

  /** Terminate session */
  $(document).on('click', '.terminate-session', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var url = $(this).data('url');
    var name = $(this).data('name');
    var state = $(this).attr('state_to_be');
    var title = $(this).attr('title');
    var _this = $(this);
    
    Swal.fire({
      title: "Are you sure?",
      text: `You want to ${title} ${name}`,
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#e53735",
      confirmButtonText: `${title}`,
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      preConfirm: (login) => {
        $.ajax({
          type: "POST",
          url: url,
          data: {'id':id,'state':state},
          success: function (response) {
                                
            var success_title;
            if(title == 'Start') {
              success_title = "Started";
            } else {
              success_title = "Terminated";
            }
            
            var resp = JSON.parse(response);
            if(resp.message == "Success") {
             Swal.fire({ type: "success", title: success_title+"!", 
                          text: `${name}` + ` has been ${success_title}.`, 
                          confirmButtonClass: "btn btn-success" });
              if(state == "running") {
                _this.removeClass('bx-play-circle');
                _this.addClass('bx-x-circle');
                _this.attr('title','Terminate');
                _this.attr('state_to_be','closed');
                _this.closest('span').attr('data-content','Click Here to Terminate Session');
              } else if(state == "closed") {
                _this.removeClass('bx-x-circle');
                _this.addClass('bx-no-entry');
                _this.attr('title','');
                _this.attr('state_to_be','closed');
                _this.closest('span').css('pointer-events','none');
                _this.closest('td').find('.generate-video-url-button').parent().css('pointer-events','none');
                _this.closest('td').find('.eventEdit').parent().css('pointer-events','none');
                _this.closest('td').find('.send-mail-button').parent().css('pointer-events','none');
                
              }
              
            } else if(resp.status == 401) {
              window.location = baseUrl;
            } else {
              Swal.fire({
                title: 'Opps...',
                text: resp.message,
                type: 'error',
                timer: '1500'
            })
            }
  
          },
          error: function (response) {
            // console.log(response);
            swal({
                title: 'Opps...',
                text: response.message,
                type: 'error',
                timer: '1500'
            })
          }         
        });
      },
      allowOutsideClick: () => !Swal.isLoading()
    },
    );
  });

  /** Delete User */
  $(document).on('click', '.delete-user', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var url = $(this).data('url');
    var name = $(this).data('name');
    var role = $(this).attr('role');
    var _this = $(this);

    Swal.fire({
      title: "Are you sure?",
      text: `You want to Delete ${name}`,
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#e53735",
      confirmButtonText: "Delete",
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      preConfirm: (login) => {
        $.ajax({
          type: "POST",
          url: url,
          data: {'id':id,'name':name, 'role':role},
          success: function (response) {
                                
            var resp = JSON.parse(response);
            if(resp.message == "Success") {
             Swal.fire({ type: "success", 
                          title: "Success!", 
                          text: `${name}` + " Deleted.", 
                          confirmButtonClass: "btn btn-success" });

              _this.closest('tr').remove();
              location.reload();
            } else {
              Swal.fire({
                title: 'Opps...',
                text: resp.message,
                type: 'error',
                timer: '1500'
            })
            }
  
          },
          error: function (response) {
            // console.log(response);
            swal({
                title: 'Opps...',
                text: response.message,
                type: 'error',
                timer: '1500'
            })
          }         
        });
      },
      allowOutsideClick: () => !Swal.isLoading()
    },
    );
   
  });

  /** User Edit */
  $(document).on('click', '.userEdit', function() {
    var id = $(this).data('id');
    var email = $(this).data('email');
    var name = $(this).data('name');
    var role = $(this).attr('role');
    var status = $(this).attr('status');
    $("#editUser #editUserId").attr('value', id);
    $("#editUser #editUserName").attr('value', name);
    $("#editUser #status").attr('value', status);
    $("#editUser input[name='euserrole'][value=" + role + "]").attr('checked', 'checked');
    $("#editUser #editEmail").attr('value',email);
    $("#editUser #editName").attr('value',name);
  });

  /** Clear values on hiding Edit user modal */
  $('#editUser').on('hidden.bs.modal', function (e) {
    $("#editUser #editEmail").attr('value','');
  });

  /** Event Edit */
  $(document).on('click', '.eventEdit', function() {
    $("#editEvent .loader-overlay").fadeIn(1000);
    var session_state = $(this).attr('session-state');
    if (session_state == 'closed' || session_state == 'running') {
      $('#editEvent .button-submit').attr('disabled','disabled');
      $('#editEvent .add_button').attr('disabled','disabled');
    }
    var url = $(this).data('url');
    var id = $(this).data('id');
    var project_id = $(this).attr('project-id');
    var assigned_users = JSON.parse($(this).attr('assigned-users'));
    var _this = $(this);

    $.ajax({
      type: "POST",
      url: url,
      data: {id:id,project_id: project_id,time_zone : tmzn},
      success: function (response) {
        // console.log(response)
        var resp = JSON.parse(response);
        if(resp.message == 'Success') {
          var result = resp.data;
          var name = result.name;
          var event_time = result.event_time;
          event_time = moment(event_time).format('MM/DD/YYYY hh:mm A');
          var rooms = result.rooms;

          console.log(event_time);

          $("#editEvent #eventName").attr('value', name);
          $("#editEvent #eventId").attr('value', id);
          $("#editEventTime").data('daterangepicker').setStartDate(event_time);
          $("#editEventTime").data('daterangepicker').setEndDate(event_time); 
          $("#editEvent #assignedUsers").attr('value', _this.attr('assigned-users'));
          $("#editEvent #room").attr('value', JSON.stringify(rooms));

          var html;
          var i= 0;
          if (rooms != []) {
            /** For each room */
            rooms.forEach(element => { 
              var member_ids1 = [];
              /** Get members for room */
              var members1 = element.members;
              members1.forEach(e => {
                member_ids1.push(e.user_id);
              })
        
              if(i == 0) {
                /*** If Waiting room */
                $("#editEvent #roomFirstId").attr('value', element.id);
                $("#editEvent #roomFirstName").attr('value', element.name);
                $('#roomFirstUsers').val( member_ids1);
                $('#roomFirstUsers').select2();
              } else {
                  html = '';
                  var roomuniqueid = "roomUsers"+i;
                  var checked ;
                  if(element.broadcast_ndi == true) {
                    checked = "checked";
                  } else {
                    checked = "";
                  }
                  html += '<div class="row mt-1">'+
                          '<input class="form-control" type="text"  name="rooms['+ i+'][roomId]" value="'+element.id+'" hidden>' +
                          '<div class="col-md-5"> ' +
                            '<label>Room</label> ' +
                            '<input class="form-control r-name" required type="text"  name="rooms['+ i+'][name]" value="'+element.name+'">' +
                          '</div>' +
                          '<div class="col-md-6">' +
                            '<label>Users</label>'+
                          
                            '<select id="'+ roomuniqueid+'" class="form-control select2 assign-users" multiple data-live-search="true" name="rooms['+ i+'][id][]">';
        
                            assigned_users.forEach(element => {
                              html += '<option value="'+ element.user_id +'">'+ element.name +'</option>' ;
                            });
                            
                            html +=  '</select>'+  
                          
                          '</div>'+
                          '<div class="col-md-1 text-right" style="margin-top:20px;"><a href="javascript:void(0);" class="remove_button btn btn-icon btn-light-danger" title="Remove field"><i style="top:3px;" class="bx bx-minus"></i></a></div>'+
                          '<div class="form-group"><input type="checkbox" name="rooms['+ i+'][broadcasttoNDI]"'+ checked +' style="margin-left:15px;"> Broadcast to NDI</div>'+
                        '</div>';
                      
                  $('#editEvent .multi-field').append(html);
                  $('#roomUsers'+i).val( member_ids1);
                  $('#roomUsers'+i).select2();
              }
              i++;
        
            });
          }
          $("#editEvent .loader-overlay").fadeOut(1000);

        }
        
      }
    });
  });

  /** Generate url and password for presenters */
  $(document).on('click', '.generate-link', function() {
    $('#generateLink .generate-link-error').hide();
    var event_id = $(this).closest('.container-fluid').find('#presenterEventId').attr('value');
	  var name = $(this).closest('.presenter-detils').find('.p-name').val();
    name = name.replaceAll(' ','');
   
    var max = 400000;
    var min = 100000;
    min = Math.ceil(min);
    max = Math.floor(max);
    $(this).closest('.presenter-detils').find('.url-details').show();
    var uuid = Math.floor(Math.random() * (max - min)) + min;
	  uuid = uuid+"#"+name;
    var pass = '';
    var str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$'; 
              
    for (var i = 1; i <= 6; i++) { 
      var char = Math.floor(Math.random() 
        * str.length + 1); 
                  
      pass += str.charAt(char) 
    } 

    var url = chimeUrl+'?ExternalUserId='+ uuid +'&EventId='+ event_id + '&p=' + pass;
              
    $(this).closest('.presenter-detils').find('.url').attr('value', url);
    $(this).closest('.presenter-detils').find('.password').attr('value', pass);

  });

  /**  Generate url and password */
  $(document).on('input', '#generateLink .p-name,#generateLink .p-email', function() {
    var name = $(this).closest('.presenter-detils').find('.p-name').val();
    var email = $(this).closest('.presenter-detils').find('.p-email').val();
    if(name != '' && email != '') {
     
      $('#generateLink .generate-link-error').hide();
      var event_id = $(this).closest('.container-fluid').find('#presenterEventId').attr('value');
      name = name.replaceAll(' ','');
    
      var max = 400000;
      var min = 100000;
      min = Math.ceil(min);
      max = Math.floor(max);
      $(this).closest('.presenter-detils').find('.url-details').show();
      var uuid = Math.floor(Math.random() * (max - min)) + min;
      uuid = uuid+"#"+name;
      var pass = '';
      var str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$'; 
                
      for (var i = 1; i <= 6; i++) { 
        var char = Math.floor(Math.random() 
          * str.length + 1); 
                    
        pass += str.charAt(char) 
      } 

      var url = chimeUrl+'?ExternalUserId='+ uuid +'&EventId='+ event_id + '&p=' + pass;
      
                
      $(this).closest('.presenter-detils').find('.url').attr('value', url);
      $(this).closest('.presenter-detils').find('.password').attr('value', pass);
    }
    
  });
 
  /** Add presenter */
  $(document).on('click', '.generate-video-url-button', function() {
    $("#generateLink .loader-overlay").fadeIn(1000);
    //Check if session terminated 
    var session_state = $(this).attr('session-state');
    if (session_state == 'closed') {
      $('#generateLink .button-submit').attr('disabled','disabled');
      $('#generateLink .add_presenter_button').attr('disabled','disabled');
    }
    var event_id = $(this).attr('session-id');
    var project_id = $(this).attr('project-id');
    var url = $(this).attr('url');
    $('#generateLink #presenterEventId').attr('value',event_id);

    $.ajax({
      type: "POST",
      url: url,
      data: {id:event_id,project_id: project_id},
      success: function (response) {
        // console.log(response)
        var resp = JSON.parse(response);
        if ( resp.message == 'Success') {
          var presenters = resp.data;
          var presenter = JSON.stringify(presenters);
          $('#generateLink #presenters').attr('value',presenter);

          if (presenters.length != 0) {
            for (var i= 0; i<presenters.length;i++) {
            // console.log(presenters[i]['email']);
              // if (presenters[i]['email'] != "") {
                
                // var copy_data = '<tr><td>'+ presenters[i]['name'] +'</td>' +'<td>'+ chimeUrl+'?ExternalUserId='+ presenters[i]['external_user_id']+'&EventId='+ event_id+ '&p='+ presenters[i]['password'] +'</td></td>'
                // $('#generateLink .select-row').append(copy_data);
                if (presenters[i]['email'] == "") {
                  var html = '<div class="row presenter-block" style="height:0;opacity:0">' ;
                } else {
                  var html = '<div class="row presenter-block">' ;
                }
                
                    
                html += '<div class="col-md-11 presenter-detils">' +
                  '<div class="row">' +
      
                    '<div class="col-8">'+
                      '<div class="row">'+
                        '<div class="form-group col-12">'+
                          '<label> Name</label> '+ 
                          '<input id="firstPresenterId" class="form-control" type="text" name="presenter['+i+'][id]" value="'+presenters[i]['id']+'" hidden>'+
                          '<input class="form-control p-name" required type="text" placeholder="Name" name="presenter['+ i +'][name]" value="'+presenters[i]['name']+'">'+
                        '</div>'+
                        '<div class="form-group col-12 ">'+
                          '<label> Email</label> '+ 
                          '<input class="form-control p-email" required type="text" placeholder="Email" name="presenter['+ i +'][email]" value="'+presenters[i]['email']+'">'+
                        '</div>'+
                        '<div class="form-group col-12 ">'+
                          '<label> Avatar</label> '+ 
                          '<input class="form-control "  type="file"  name="presenter['+ i +'][avatar]">'+
                        '</div>'+
                        '<div class="row url-details" >'+
                          '<div class="col-md-6">'+
                            '<div class="form-group" style="margin-bottom:0px;margin-left:15px;">'+
                              '<div style=" cursor:pointer" class="badge badge-pill badge-light-warning d-inline-flex align-items-center copy-btn"><i class="bx bx-copy font-size-small mr-50"></i><small class="text-warning">Click Here to Copy Url</small></div>'+  
                              '<input class="form-control url copy-to-clipboard" required type="text" placeholder="Url" name="presenter['+ i +'][uuid]" value="'+chimeUrl+'?ExternalUserId='+ presenters[i]['external_user_id']+'&EventId='+ event_id+ '&p='+ presenters[i]['password']+'" style="height:0;opacity:0;">'+
                            '</div>'+
                          '</div>'+
                          '<div class="col-md-6">'+
                            '<div class="form-group" style="margin-bottom:0px;">'+
                              '<div style="height: 0;opacity: 0;" class="badge badge-pill badge-light-warning d-inline-flex align-items-center copy-btn"><i class="bx bx-copy font-size-small mr-50"></i><small class="text-warning">Click Here to Copy Password</small></div>'+
                              '<input class="form-control password copy-to-clipboard" required type="text" placeholder="Password" name="presenter['+ i +'][password]" value="'+presenters[i]['password']+'" style="height:0;opacity:0;">'+
                            '</div>'+
                          '</div>'+
                        '</div>'+
                      '</div>'+
                      
                    '</div>'+
                    '<div class="col-4 text-center" style="top:22px">'+
                      '<input type="text"  class="avatar-file-name" name="presenter['+i+'][avatar_file_name]" value="'+presenters[i]['avatar_file_name']+'" hidden>'+
                      '<input type="text" class="remove-avatar-file-name" name="presenter['+i+'][remove_avatar_file_name]" value="" hidden>';
                      if(presenters[i]['avatar'] != null) {
                      html += '<img src="'+presenters[i]['avatar']+'" width="150px" height="150px">'+
                      '<button type="button" class="btn btn-danger mb-3 delete-presenter-avatar" style="width: 80%;margin-top:5px" onclick="deletePresenterAvatar(this)">Delete image</button>';
                      }
                    html += '</div>'+
                  '</div>'+
                '</div>'+
                    
                '<div class="col-md-1" style="margin-top: 25px;">'+
                  '<button type="button" class="btn btn-icon btn-danger remove_presenter_button"><i style="top:3px;" data-toggle="popover" data-placement="top" data-trigger="hover" data-container="body" data-content="Click Here to delete this row" class="bx bx-x"></i></button>'+
                '</div>'+
                '</div>';
                // $('#generateLink .container-fluid').append(html); .edit-presenter-block          '<a href="javascript:void(0);" class="remove_presenter_button" title="Add field"><i style="position:relative;top:8px;color:#FF2829;font-weight:bold;" class="bx bx-minus"></i></a>'+
                $('#generateLink .edit-presenter-block').append(html);
                
              // }
            }
            
            $('#generateLink .send-mail').removeAttr('disabled');
          } else {
            $('#generateLink .send-mail').attr('disabled','disabled');
          }
        }
        $("#generateLink .loader-overlay").fadeOut(1000);
      }
    });

  });
  
  /** Project Edit*/
  $(document).on('click', '.projectEdit', function() {
    var id = $(this).data('id');
    var name = $(this).attr('projectName');
    var client_name = $(this).attr('client-name');
    var job_number = $(this).attr('job-number');
    var members = JSON.parse($(this).attr('members'));
	  var recording = $(this).attr('recording') ;
	
    $('#editProjectModal #projectId').attr('value', id);
    $("#editProjectModal #projectName").attr('value', name);
    $("#editProjectModal #clientID").attr('value',client_name);
    $("#editProjectModal #jobNumber").val(job_number);
    $("#editProjectModal #assignedMembers").val($(this).attr('members'));
    if(recording == 1) {
      $("#editProjectModal #recording").prop('checked', true);
    } else {
      $("#editProjectModal #recording").prop('checked', false);
    }
    $('#editProjectModal #members').val( members);
    $('#editProjectModal #members').select2();
  });

  // $(document).on('click', '#generateLink .send-mail', function(){
  //   $('#generateLink .send-mail').html('Sending');
  //   var event_id =$('#generateLink #presenterEventId').attr('value');
  //   var url = $(this).attr('url');
  //   var presenter = [];
  //   var presenters = $('#generateLink .presenter-detils').length;
  //   var count = 0;

  //   $('#generateLink .presenter-detils').each(function() {
  //     if($(this).find('.url').val() != '' && $(this).find('.password').val() != '' && 
  //       $(this).find('.p-email').val() != '' ) {
  //       presenter.push({"link":$(this).find('.url').val(), 
  //           "password" : $(this).find('.password').val(), 
  //           'email': $(this).find('.p-email').val() });
  //       count++;
  //     } else {
  //       $('#generateLink .generate-link-error').show();
  //     }

  //   });

  //   if(presenters == count) {
  //     $.ajax({
  //       type: "POST",
  //       url: url,
  //       data: {id:event_id,presenter : presenter},
  //       success: function (response) {
                              
  //         var resp = JSON.parse(response);
  //         if(resp.message == "Success") {
  //            $('#generateLink .send-mail').html('Send Mail');
  //         } else if(resp.status == 401) {
  //           window.location = baseUrl;
  //         }else {
  //           swal({
  //             title: 'Opps...',
  //             text: resp.message,
  //             type: 'error',
  //             timer: '1500'
  //         })
  //         }
  
  //       },
  //       error: function (response) {
  //         // console.log(response);
  //         swal({
  //             title: 'Opps...',
  //             text: response.message,
  //             type: 'error',
  //             timer: '1500'
  //         })
  //       }         
  //     });
  //   } else {
  //     $('#generateLink .send-mail').html('Send Mail');
  //   }
   
  // });

  /** Clear values on hiding Add Presenter modal */
  $('#generateLink').on('hidden.bs.modal', function(){
    
    $(this).find('form')[0].reset();
    $('#generateLink .edit-presenter-block').html('');
    $('#generateLink .select-row').html('');
    $('#generateLink .presenter-block').remove();
    $('#generateLink .send-mail').removeAttr('disabled');
    $('#generateLink .button-submit').removeAttr('disabled');
    $('#generateLink .button-submit').html('Submit');
  });

  /** Clear values on hiding Edit Session modal */
  $('#editEvent').on('hidden.bs.modal', function(){
    
    $('#editEvent .edit-assign-rooms').html('');
    $('#editEvent #roomFirstUsers').val('').trigger('change');
    $('#editEvent .row.mt-1').remove();
    $(this).find('form')[0].reset();
  });

  /** Clear values on hiding Add Session modal */
  $('#newSchedule').on('hidden.bs.modal', function(){
    $('#editEvent .new-room').remove();
    $(this).find('form')[0].reset();
  });
 
  /** Form validation */
  $(document).on('click', '.modal .button-submit', function() {

    if($(this).closest('.modal').find('form')[0].checkValidity() != false) {

      $(this).html('<div class="loader" id="loader-btn"><span></span><span></span><span></span></div>');
      $(this).attr('disabled', true);
      $(this).closest('.modal').find('form').submit();

    }

  });

  $(document).on('click', '.add-file', function(){
    var html = $(".clone").html();
    $(".increment").after(html);  
  });

  $("body").on("click",".btn-danger",function(){ 
    $(this).parents(".control-group").remove();
  });

  /** Upload Audio */
  $(document).on("click", ".upload-audio", function(){
    var id = $(this).data('id');
    var audio_name = $(this).attr('audio-name');
    var audio_url = $(this).attr('audio-url');
    var audio = $(this).attr('audio');
    // console.log(audio_url);
    $('#multimediaModal1 .projectId').attr('value',id);
    $('#multimediaModal1 .uploadedAudio').attr('value',audio);
    if (audio_url != '') {
      $('#multimediaModal1').find('.audio-block').show();
      document.getElementById("audioPlay").pause();
      document.getElementById("audioPlay").setAttribute('src', audio_url);
      document.getElementById("audioPlay").load();

    }
  });

  /** Upload Image */
  $(document).on("click", ".upload-image", function(){
    var id = $(this).data('id');
    var image = $(this).attr('image');
    // console.log(image);
    var image_name = $(this).attr('image-name');
    var image_url= $(this).attr('image-url');
    $('#multimediaModal .projectId').attr('value',id);
    $('#multimediaModal .uploadedImage').attr('value',image);
    if (image_url != '') {
      $('#multimediaModal').find('.col-md-3').show();
      $('#multimediaModal').find('.col-md-3 img').attr('src',image_url);
    }
    
  });

  /** Remove User from Project */
  $(document).on('click', ".removeUser", function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var url = $(this).data('url');
    var name = $(this).data('name');
    var projectId = $(this).attr('project-id');
    var _this = $(this);

    console.log(url);

    Swal.fire({
      title: "Are you sure?",
      text: `You want to Remove ${name}`,
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#e53735",
      confirmButtonText: "Remove",
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      preConfirm: (login) => {
        $.ajax({
          type: "POST",
          url: url,
          data: {'id':id,'projectId':projectId},
          success: function (response) {
            console.log(response)
            var resp = JSON.parse(response);

            if(resp.message == "Success") {
              Swal.fire({ type: "success", title: "Removed!", text: `${name}` + " has been removed.", confirmButtonClass: "btn btn-success" });

              _this.closest('tr').remove();
              location.reload();
            } else if(resp.status == 401) {
              window.location = baseUrl;
            } else {

              swal({
                title: 'Opps...',
                text: resp.message,
                type: 'error',
                timer: '1500'
            })
            }
  
          },
          error: function (response) {
            swal({
                title: 'Opps...',
                text: "Please try again",
                type: 'error',
                timer: '1500'
            })
          }         
        });
      },
      allowOutsideClick: () => !Swal.isLoading()
    },
    );

  });

  /** Remove Image file*/
  $(document).on('click', '.remove-image', function (e) {
    e.preventDefault();
    var id = $(this).closest('#multimediaModal').find('.uploadedImage').val();
    var url = $(this).attr('url');
    var project_id = $(this).attr('project-id');
    var _this = $(this);
    // console.log(id);
    $.ajax({
      type: "POST",
      url: url,
      data: {'id':id,'project_id':project_id},
      success: function (response) {
        var resp = JSON.parse(response);

        if(resp.status == 200) {
          _this.closest('#multimediaModal').find('.alert-block strong').html(resp.message);
          _this.closest('#multimediaModal').find('.alert-block').show();
          _this.closest('#multimediaModal').find('.col-md-3').remove();
          _this.closest('#multimediaModal').find('.alert-block').delay(4000).fadeOut('slow');

        } else if(resp.status == 401) {
          window.location = baseUrl;
        } else {
          _this.closest('#multimediaModal').find('.alert-block strong').html(resp.message);
          _this.closest('#multimediaModal').find('.alert-block').show();
        }

      },
              
    });

  });

  /** Remove Audio file */
  $(document).on('click', '.remove-audio', function (e) {
    e.preventDefault();
    var id = $(this).closest('#multimediaModal1').find('.uploadedAudio').val();
    var url = $(this).attr('url');
    var project_id = $(this).attr('project-id');
    var _this = $(this);
    // console.log(id);
    $.ajax({
      type: "POST",
      url: url,
      data: {'id':id,'project_id':project_id},
      success: function (response) {
        var resp = JSON.parse(response);

        if(resp.status == 200) {
          _this.closest('#multimediaModal1').find('.alert-block strong').html(resp.message);
          _this.closest('#multimediaModal1').find('.alert-block').show();
          _this.closest('#multimediaModal1').find('.audio-block').remove();
          // _this.closest('#multimediaModal1').find('#uploadedAudio').html('Uploaded Audio: ');
          _this.closest('#multimediaModal1').find('.alert-block').delay(2000).fadeOut('slow');
        } else if(resp.status == 401) {
          window.location = baseUrl;
        } else {
          _this.closest('#multimediaModal1').find('.alert-block strong').html(resp.message);
          _this.closest('#multimediaModal1').find('.alert-block').show();
        }

      },
              
    });

  });
  

  $(".alert-fade").delay(4000).fadeOut('slow');

  /** Remove Profile Image */
  $(document).on('click', '.profile-image-remove', function (e) {
    var avatar_file_name = $(this).closest('form').find('.avatar-file-name').attr('value');
    $(this).closest('form').find('.delete').attr('value', avatar_file_name);
    $(this).closest('form').find('.col-md-3').remove();
  });

  $('#multimediaModal1').on('hidden.bs.modal', function(){
    var player = document.getElementById('audioPlay');
    player.pause();
    player.src = player.src;
  });

  $(document).on('click', '.create-session', function () {
    $('#newSchedule #demo').daterangepicker({
      "singleDatePicker": true,
      "timePicker": true,
      locale: {
        format: 'M/DD/YYYY hh:mm A'
      },
      timeZone: tmzn
      // "startDate": "03/24/2021",
      // "endDate": "03/30/2021"
    });
    // $('#newSchedule #demo').daterangepicker({
    //   timeZone: tmz
    // })
  });
  // $(document).on('change', '#user-datatable .custom-select', function (e) {
  //   var per_page = $(this).val();
  //   var url = $('#user-datatable #userUrl').val();
  //   var list_url = $('#user-datatable #userListUrl').val();
  //   // console.log(url);
  //   url = url.split("/1");
  //   console.log(url[0])
  //   window.location.href = url[0]+'/1/'+per_page;
  //   // $.ajax({
  //   //   type: 'GET', 
  //   //   url : url, 
  //   //   data: {'per_page':per_page},
  //   //   success : function (response) {
  //   //     console.log(response);
  //   //     // var resp = JSON.parse(response);
  //   //     // var d = resp.data;
  //   //     $.ajax({
  //   //       type: 'POST', 
  //   //       url : list_url, 
  //   //       data: {data: JSON.stringify(response)},
  //   //       success : function (res) {
  //   //         console.log(res);

  //   //       }
  //   //     });
  //   //   }
  //   // });
  // });
  
  // $(document).on('click', '.refresh-session-button', function (e) {
  //   console.log("test");
  //   var session_url = $(this).attr('url');
  //   var id = $(this).data('id');
  //   console.log(session_url,id);
  //   $.ajax({
  //     type: "GET",
  //     url: session_url,
  //     data: {'id':id},
  //     success: function (response) {
  //       console.log(response);
  //     }
  //   });
  // });
  
  // setInterval(function() {
  //   console.log("test");
  //   var session_url = $('#sessionTable').attr('url');
  //   var id = $('#sessionTable').data('id');
  //   console.log(session_url);
  //   // var page = window.location.href;
  //   // $.ajax({
  //   //   type:"GET",
  //   // url: session_url,
  //   // data: {'id': id},
  //   // success: function (response) {
  //   //   // var resp = JSON.parse(response);
  //   //   console.log(response);
  //   // //  $('#commentarea').html(data);
  //   // }
  //   // });
  // }, 60000);

  /** Copy urls */
  // $(document).on('click', '.copy-all-url', function (e) {

    

  //   e.preventDefault();
  //   var presenters = JSON.parse($(this).attr('presenters'));
  //   var event_id = $(this).attr('session-id');
  //   var url = $(this).attr('url');
  //   var _this = $(this);
  //   var presenter= [];
    
      
  // });

  $(".zero-configuration").DataTable();
   var o = $(".row-grouping").DataTable({
       columnDefs: [{ visible: !1, targets: 2 }],
       order: [[2, "asc"]],
       displayLength: 10,
       drawCallback: function (a) {
           var o = this.api(),
               t = o.rows({ page: "current" }).nodes(),
               r = null;
           o.column(2, { page: "current" })
               .data()
               .each(function (a, o) {
                   r !== a &&
                       ($(t)
                           .eq(o)
                           .before('<tr class="group"><td colspan="5">' + a + "</td></tr>"),
                       (r = a));
               });
       },
   });
   
})(jQuery);

/** Delete Presenter avatar */
function deletePresenterAvatar(e) {
  var avatar_file_name = $('.delete-presenter-avatar').closest('div').find('.avatar-file-name').attr('value');
  $('.delete-presenter-avatar').closest('div').find('.remove-avatar-file-name').attr('value', avatar_file_name);
  $('.delete-presenter-avatar').closest('div').find('img').remove();
  e.remove();
}

function copyTextToClipboard(text) {
  if (!navigator.clipboard) {
    fallbackCopyTextToClipboard(text);
    return;
  }
  navigator.clipboard.writeText(text).then(function() {
    console.log('Async: Copying to clipboard was successful!');
  }, function(err) {
    console.error('Async: Could not copy text: ', err);
  });
}

function copyDivData(containerId) {

  $('#generateLink .url-details').find('.url').each(function(){
    if($(this).val() != '') {
      var name = $(this).closest('.row .url-details').closest('.col-8').find('.p-name').val();
      var copy_data = '<tr><td>'+ name +'</td>' +'<td>'+ $(this).val() +'</td></td>'
      $('#generateLink .select-row').append(copy_data);
    }
  });
  var range = document.createRange();
  range.selectNode(containerId); //changed here
  window.getSelection().removeAllRanges(); 
  window.getSelection().addRange(range); 
  document.execCommand("copy");
  window.getSelection().removeAllRanges();
  // alert("data copied");
  $('#generateLink').find('.alert-block').show();
    $(".alert-fade").delay(1000).fadeOut('slow');
    
  $('#generateLink .select-row').html('');
}