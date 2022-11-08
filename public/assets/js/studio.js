(function(window, undefined) {
    'use strict';
  
    /*
    NOTE:
    ------
    PLACE HERE YOUR OWN JAVASCRIPT CODE IF NEEDED
    WE WILL RELEASE FUTURE UPDATES SO IN ORDER TO NOT OVERWRITE YOUR JAVASCRIPT CODE PLEASE CONSIDER WRITING YOUR SCRIPT HERE.  */
  
    

    $(document).ready(function(){
      var studio_chimeUrl =  'https://rl0qljsswi.execute-api.us-east-1.amazonaws.com/Prod/';


      var tmzn = $("#gttz").text();
      $('#editSessionTime').daterangepicker({
        "singleDatePicker": true,
        "timePicker": true,
        locale: {
          format: 'M/DD/YYYY hh:mm A'
        },
        timeZone: tmzn
        // "startDate": "03/24/2021",
        // "endDate": "03/30/2021"
      });

        //Studio Edit
        $(document).on('click', '.studioEdit', function() {
            console.log("test");
            var id = $(this).data('id');
            var name = $(this).attr('projectName');
            var client_name = $(this).attr('client-name');
            var job_number = $(this).attr('job-number');
            var members = JSON.parse($(this).attr('members'));
            var recording = $(this).attr('recording') ;
            
            $('#editStudioModal #projectId').attr('value', id);
            $("#editStudioModal #projectName").attr('value', name);
            $("#editStudioModal #clientID").attr('value',client_name);
            $("#editStudioModal #jobNumber").val(job_number);
            $("#editStudioModal #assignedMembers").val($(this).attr('members'));
            $('#editStudioModal #members').val( members);
            $('#editStudioModal #members').select2();
			$("#editStudioModal input[name='recording'][value=" + recording + "]").attr('checked', 'checked');
            
            
        });

        $('#newStudioSession').on('hidden.bs.modal', function(){
            $('#newStudioSession .assign-users').val('').trigger('change');
            $(this).find('form')[0].reset();
        });

        //Session Edit
        $(document).on('click', '.sessionEdit', function() {
          $("#editstudioSession .loader-overlay").fadeIn(1000);
          var session_state = $(this).attr('session-state');
          if (session_state == 'closed' || session_state == 'running') {
            $('#editstudioSession .button-submit').attr('disabled','disabled');
          }
          var url = $(this).data('url');
          var id = $(this).data('id');
          var project_id = $(this).attr('project-id');
          var timezone = $(this).attr('timezone');
          // var assigned_users = JSON.parse($(this).attr('assigned-users'));
          var assigned_users = $(this).attr('assigned-users');

          console.log(assigned_users);

          $.ajax({
            type: "POST",
            url: url,
            data: {id:id,project_id: project_id,time_zone : timezone},
            success: function (response) {
              console.log(response)
              var resp = JSON.parse(response);
              if(resp.message == 'Success') {
                var result = resp.data;
                var name = result.name;
                var event_time = result.event_time;
                event_time = moment(event_time).format('MM/DD/YYYY hh:mm');
                var members = result.members;

                // console.log(resp,members,JSON.stringify(members));

                $("#editstudioSession #eventName").attr('value', name);
                $("#editstudioSession #eventId").attr('value', id);
                $("#editstudioSession #editSessionTime").data('daterangepicker').setStartDate(event_time);
                $("#editstudioSession #editSessionTime").data('daterangepicker').setEndDate(event_time); 
                $("#editstudioSession #assignedUsers").attr('value', assigned_users);
                $("#editstudioSession #members").attr('value', JSON.stringify(members));
                // $("#editEvent #room").attr('value', $(this).attr('rooms'));
                var member_ids1 = [];
                if (members != []) {
                  /** For each members */
                  members.forEach(element => { 
                    
                    /** Get members for room */
                    // var members1 = element.members;
                    // members1.forEach(e => {
                      member_ids1.push(element.user_id);
                    // });
                  });
              
                  $('#roomFirstUsers').val( member_ids1);
                  $('#roomFirstUsers').select2();
              
                  
                }
                $("#editstudioSession .loader-overlay").fadeOut(1000);
              }
              
            }
          });

          
        });

        $('#editstudioSession').on('hidden.bs.modal', function(){
          $('#editstudioSession #roomFirstUsers').val('').trigger('change');
          $(this).find('form')[0].reset();
        });

        $(document).on('click', '.session-add-presenter', function() {
          $("#addSessionPresenter .loader-overlay").fadeIn(1000);
          //Check if session terminated 
          var session_state = $(this).attr('session-state');
          if (session_state == 'closed') {
            $('#addSessionPresenter .button-submit').attr('disabled','disabled');
            $('#addSessionPresenter .add_presenter_button').attr('disabled','disabled');
          }
          var event_id = $(this).attr('session-id');
          var project_id = $(this).attr('project-id');
          var url = $(this).attr('url');
          $('#addSessionPresenter #presenterEventId').attr('value',event_id);
      
          $.ajax({
            type: "POST",
            url: url,
            data: {id:event_id,project_id: project_id},
            success: function (response) {
              var resp = JSON.parse(response);
              if ( resp.message == 'Success') {
                var presenters = resp.data;
                // var presenter = result.presenters;
                // console.log(presenter)
                var presenter = JSON.stringify(presenters);
                $('#addSessionPresenter #presenters').attr('value',presenter);
      
                if (presenters.length != 0) {
                  for (var i= 0; i<presenters.length;i++) {
                  
                    if (presenters[i]['email'] != null) {
                      var html = '<div class="row presenter-block">' +
                          
                      '<div class="col-md-11 presenter-detils">' +
                        '<div class="row">' +
            
                          // '<div class="col-12">'+
                            // '<div class="row">'+
                              '<div class="form-group col-6">'+
                                '<label> Name</label> '+ 
                                '<input id="firstPresenterId" class="form-control" type="text" name="presenter['+i+'][id]" value="'+presenters[i]['id']+'" hidden>'+
                                '<input class="form-control p-name" required type="text" placeholder="Name" name="presenter['+ i +'][name]" value="'+presenters[i]['name']+'">'+
                              '</div>'+
                              '<div class="form-group col-6 ">'+
                                '<label> Email</label> '+ 
                                '<input class="form-control p-email" required type="text" placeholder="Email" name="presenter['+ i +'][email]" value="'+presenters[i]['email']+'">'+
                              '</div>'+
                              
                              '<div class="row url-details" >'+
                                '<div class="col-md-6">'+
                                  '<div class="form-group" style="margin-bottom:0px;margin-left:15px;">'+
                                    '<div style=" cursor:pointer" class="badge badge-pill badge-light-warning d-inline-flex align-items-center copy-btn"><i class="bx bx-copy font-size-small mr-50"></i><small class="text-warning">Click Here to Copy Url</small></div>'+  
                                    '<input class="form-control url copy-to-clipboard" required type="text" placeholder="Url" name="presenter['+ i +'][uuid]" value="'+studio_chimeUrl+'?ExternalUserId='+ presenters[i]['external_user_id']+'&StudioSessionId='+ event_id+ '&p='+ presenters[i]['password']+'" style="height:0;opacity:0;">'+
                                  '</div>'+
                                '</div>'+
                                '<div class="col-md-6">'+
                                  '<div class="form-group" style="margin-bottom:0px;">'+
                                    '<div style="height: 0;opacity: 0;" class="badge badge-pill badge-light-warning d-inline-flex align-items-center copy-btn"><i class="bx bx-copy font-size-small mr-50"></i><small class="text-warning">Click Here to Copy Password</small></div>'+
                                    '<input class="form-control password copy-to-clipboard" required type="text" placeholder="Password" name="presenter['+ i +'][password]" value="'+presenters[i]['password']+'" style="height:0;opacity:0;">'+
                                  '</div>'+
                                '</div>'+
                              '</div>'+
                            // '</div>'+
                            
                          // '</div>'+
                          
                        '</div>'+
                      '</div>'+
                          
                      '<div class="col-md-1" style="margin-top: 25px;">'+
                        '<button type="button" class="btn btn-icon btn-danger remove_presenter_button"><i style="top:3px;" data-toggle="popover" data-placement="top" data-trigger="hover" data-container="body" data-content="Click Here to delete this row" class="bx bx-x"></i></button>'+
                      '</div>'+
                      '</div>';
                      // $('#generateLink .container-fluid').append(html); .edit-presenter-block          '<a href="javascript:void(0);" class="remove_presenter_button" title="Add field"><i style="position:relative;top:8px;color:#FF2829;font-weight:bold;" class="bx bx-minus"></i></a>'+
                      $('#addSessionPresenter .edit-presenter-block').append(html);
                      
                    }
                  }
                  
                  $('#addSessionPresenter .send-mail').removeAttr('disabled');
                } else {
                  $('#addSessionPresenter .send-mail').attr('disabled','disabled');
                }
              }
              $("#addSessionPresenter .loader-overlay").fadeOut(1000);
            }
          });
      
        });

        var maxField = 15; //Input fields increment limitation
        // Add Presenters
        $(document).on('click', '#addSessionPresenter .add_presenter_button', function() {
        // $('#addSessionPresenter .add_presenter_button').click(function(){
          var str = $('#addSessionPresenter div.presenter-block:last').find('.p-name').attr('name');
          
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


                              // '<div class="col-8">'+
                                  // '<div class="row">'+
                                      '<div class="form-group col-6">'+
                                          '<label> Name</label> '+
                                          '<input id="firstPresenterId" class="form-control" type="text" name="presenter['+p+'][id]" hidden>' + 
                                          '<input class="form-control p-name" required type="text" placeholder="Name" name="presenter['+ p +'][name]">'+
                                      '</div>'+
                                      '<div class="form-group col-6">'+
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

                                      
                                  // '</div>'+
                              
                              // '</div>'+
                              '</div>'+
                            
                          '</div>'+
                          '<div class="col-md-1" style="margin-top: 25px;">'+
                              
                              '<button type="button" class="btn btn-icon btn-danger remove_presenter_button"><i data-toggle="popover" data-placement="top" data-trigger="hover" data-container="body" data-content="Click Here to delete this row" style="top:3px;" class="bx bx-x"></i></button>'+
                          '</div>'+
                          
                          
                          
                      '</div>';
          if(p < maxField){ 
              // p++; //Increment field counter
              $('#addSessionPresenter .container-fluid').append(html); //Add field html
              // $('.multi-field .selectpicker').selectpicker('refresh');
          }
        });

        $("#addSessionPresenter").on("click", ".copy-btn", function(){ 

          $(this).parent().find(".copy-to-clipboard").select(),document.execCommand("copy");
          $(this).closest('#addSessionPresenter').find('.alert-block').show();
          $(".alert-fade").delay(1000).fadeOut('slow');
          
        });

        // '.remove_presenter_button'
        $(document).on('click', '#addSessionPresenter .remove_presenter_button', function(e){
          $('#addSessionPresenter .generate-link-error').hide();
          // console.log("testtt");
          // console.log($(this).parent());
          $(this).parent().parent('.presenter-block').remove(); //Remove field html
          // x--; //Decrement field counter
        });

        //Record session
        $(document).on('click', '.studio-session-record', function (e) {
          e.preventDefault();
          var id = $(this).data('id');
          var studio_id = $(this).attr('studio-id');
          var url = $(this).data('url');
          var name = $(this).data('name');
          var start_recording = $(this).attr('start-recording');
          var state = start_recording == 1 ? 'stop' : 'start';
          var title = $(this).attr('title');
          console.log(start_recording);
          // var projectId = $(this).attr('project-id');
          var _this = $(this);
          console.log(id,studio_id,state);
          var session_state = $(this).closest('td').find('.terminate-session').attr('state_to_be');
          if (session_state == 'running') {
            Swal.fire({
              title: 'Opps...',
              text: 'Please start session and try again',
              type: 'error',
              timer: '1500'
            })
          } else if (session_state == 'closed') {
            Swal.fire({
              title: "Are you sure?",
              text: `You want to ${title} recording?`,
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
                  data: {'id':id,'studio_id':studio_id,'state':state},
                  success: function (response) {
                                        
                    var success_title;
                    if(title == 'Start') {
                      success_title = "Started";
                    } else {
                      success_title = "Stopped";
                    }
                    
                    var resp = JSON.parse(response);
                    
                    if(resp.message == "Success") {
                      location.reload();
                    Swal.fire({ type: "success", title: success_title+"!", text: `${name}` + ` has been ${success_title}.`, confirmButtonClass: "btn btn-success" });
                      if(state == "start") {
                        _this.removeClass('bx-log-in-circle text-warning');
                        _this.addClass('bx-circle text-danger');
                        _this.attr('title','Stop');
                        _this.attr('start_recording','stop');
                        _this.closest('span').attr('data-content','Click Here to Stop Recording');
                      } else if(state == "stop") {
                        _this.removeClass('bx-circle text-danger');
                        _this.addClass('bx-log-in-circle text-warning');
                        _this.attr('title','Start');
                        _this.closest('span').attr('data-content','Click Here to Start Recording');
  
                        _this.attr('start_recording','start');
                        // _this.closest('span').css('pointer-events','none');
                        // _this.closest('td').find('.generate-video-url-button').parent().css('pointer-events','none');
                        // _this.closest('td').find('.eventEdit').parent().css('pointer-events','none');
                        // _this.closest('td').find('.send-mail-button').parent().css('pointer-events','none');
                        
                      }
                      // Swal.fire("Terminated!", `${name}`, "success");
                      // location.reload();
                      // _this.closest('tr').remove();
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
                    // swal({
                    //     title: 'Opps...',
                    //     text: response.message,
                    //     type: 'error',
                    //     timer: '1500'
                    // })
                }         
                });
              },
              allowOutsideClick: () => !Swal.isLoading()
            },
            );
          }
         
        });


        $('#addSessionPresenter').on('hidden.bs.modal', function(){
    
          $(this).find('form')[0].reset();
          $('#addSessionPresenter .edit-presenter-block').html('');
          $('#addSessionPresenter .presenter-block').remove();
          $('#addSessionPresenter .send-mail').removeAttr('disabled');
          $('#addSessionPresenter .button-submit').removeAttr('disabled');
          $('#addSessionPresenter .button-submit').html('Submit');
        });

        
        $(document).on('click', '.go-link', function (e) {
          var id = $(this).data('id');
          var url = $(this).data('url');
          $.ajax({
            type: "POST",
            url: url,
            data: {'id':id},
            success: function (response) {
                console.log(response)
                var resp = JSON.parse(response);
                if (resp.status == 200) {
                    var data= resp.data;
                    var url=data.url;
                    window.open(url,"_blank","") 
                } else {
                    var msg= resp.message;
                    $('.go-link-alert').html(msg);
                    $('.go-link-alert').show();
                    $('.go-link-alert').delay(2000).fadeOut('slow');
                }
            }
          });
        });
        
        // $(".alert-fade").delay(4000).fadeOut('slow');


      /**  Generate url and password */
      $(document).on('input', ' #addSessionPresenter .p-name, #addSessionPresenter .p-email', function() {
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

          var url = studio_chimeUrl+'?ExternalUserId='+ uuid +'&EventId='+ event_id + '&p=' + pass;
          
                    
          $(this).closest('.presenter-detils').find('.url').attr('value', url);
          $(this).closest('.presenter-detils').find('.password').attr('value', pass);
        }
        
      });
      
      $('input:radio[name="uploadSettings"]').change(
        function(){
          var url = $('#uploadSettings #storageUrl').val();
          console.log(url)
          var storage_source ;
          var studioId = $('#uploadSettings #studioId').val();
            if (this.checked && this.value == 'google') {
                console.log("google")
                $('#uploadSettings .google-drive-div').show();
                $('#uploadSettings .dropbox-div').hide();
                storage_source = 2;
                
            } else if(this.checked && this.value == 'dropbox') {
              console.log("dropbox")
              $('#uploadSettings .dropbox-div').show();
              $('#uploadSettings .google-drive-div').hide();
              storage_source = 3;
            }
            

      });

        $(document).on('click', '.gmail-login', function (e) {
          var url = $('#uploadSettings #url').val();
          var storageUrl = $('#uploadSettings #storageUrl').val();
          var studioId = $('#uploadSettings #studioId').val();
          var token = $('#uploadSettings #token').val();
          var storage_source = 2;
          // /studio/storage_cred/<int:studio_id>
          // $.ajax({
          //   type: "POST",
          //   url: storageUrl,
          //   data: {'id':studioId,'storage_source':3},
          //   success: function (response) {
          //     console.log(response);
          //   }
          // });
          $.ajax({
            type: "GET",
            url: url,
            data: {'id':studioId,'storage_source':storage_source},
            success: function (response) {
                // console.log("ttttt")
                console.log(response);
                // var tab = window.open('about:blank', '_blank');
                // tab.document.write(response); // where 'html' is a variable containing your HTML
              // var resp = JSON.parse(response);
            }
          });

          window.open(url+'gmail/login?studio_id='+ studioId +'&token=' + token);
          location.reload();

        });

        
        $(document).on('click', '.dropbox-login', function (e) {
          var url = $('#uploadSettings #url').val();
          var studioId = $('#uploadSettings #studioId').val();
          var token = $('#uploadSettings #token').val();
          var storage_source = 3;
          $.ajax({
            type: "GET",
            url: url,
            data: {'id':studioId,'storage_source':storage_source},
            success: function (response) {
                // console.log("ttttt")
                console.log(response);
                // var tab = window.open('about:blank', '_blank');
                // tab.document.write(response); // where 'html' is a variable containing your HTML
              // var resp = JSON.parse(response);
            }
          });

          window.open(url+'dropbox/login?studio_id='+ studioId +'&token=' + token);

        });


        
        $(document).on('click', '.remove-storage-email', function (e) {
          var type = $(this).attr('type');
        });

    });
   
})(window);
