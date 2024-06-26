"use strict";

WebFont.load({
  google: { "families": ["Lato:300,400,700,900"] },
  custom: { "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: [mainurl + '/assets/admin/css/fonts.min.css'] },
  active: function () {
    sessionStorage.fonts = true;
  }
});

/*****************************************************
  ==========Bootstrap Notify start==========
  ******************************************************/
function bootnotify(message, title, type) {
  var content = {};

  content.message = message;
  content.title = title;
  content.icon = 'fa fa-bell';

  $.notify(content, {
    type: type,
    placement: {
      from: 'top',
      align: 'right'
    },
    showProgressbar: true,
    time: 1000,
    allow_dismiss: true,
    delay: 4000
  });
}
/*****************************************************
==========Bootstrap Notify end==========
******************************************************/

$(function ($) {

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  /* ***************************************************************
  ==========disabling default behave of form submits start==========
  *****************************************************************/
  $("#ajaxEditForm").attr('onsubmit', 'return false');
  $("#ajaxForm").attr('onsubmit', 'return false');
  $("#ajaxFormDisqus").attr('onsubmit', 'return false');
  $("#ajaxFormRecaptcha").attr('onsubmit', 'return false');
  /* *************************************************************
  ==========disabling default behave of form submits end==========
  ***************************************************************/

  // Sidebar Search

  $(".sidebar-search").on('input', function () {
    let term = $(this).val().toLowerCase();

    if (term.length > 0) {
      $(".sidebar ul li.nav-item").each(function (i) {
        let menuName = $(this).find("p").text().toLowerCase();
        let $mainMenu = $(this);

        // if any main menu is matched
        if (menuName.indexOf(term) > -1) {
          $mainMenu.removeClass('d-none');
          $mainMenu.addClass('d-block');
        } else {
          let matched = 0;
          let count = 0;
          // search sub-items of the current main menu (which is not matched)
          $mainMenu.find('span.sub-item').each(function (i) {
            // if any sub-item is matched  of the current main menu, set the flag
            if ($(this).text().toLowerCase().indexOf(term) > -1) {
              count++;
              matched = 1;
            }
          });


          // if any sub-item is matched  of the current main menu (which is not matched)
          if (matched == 1) {
            $mainMenu.removeClass('d-none');
            $mainMenu.addClass('d-block');
          } else {
            $mainMenu.removeClass('d-block');
            $mainMenu.addClass('d-none');
          }
        }
      });
    } else {
      $(".sidebar ul li.nav-item").addClass('d-block');
    }
  });




  /* ***************************************************
  ==========bootstrap datepicker start==========
  ******************************************************/
  $('.datepicker').datepicker({
    autoclose: true
  });
  /* ***************************************************
  ==========bootstrap datepicker end==========
  ******************************************************/



  /* ***************************************************
  ==========fontawesome icon picker start==========
  ******************************************************/
  $('.icp-dd').iconpicker();

  $('.icp').on('iconpickerSelected', function (event) {
    $("#inputIcon").val($(".iconpicker-component").find('i').attr('class'));
  });
  /* ***************************************************
  ==========fontawesome icon picker upload end==========
  ******************************************************/


  /* ***************************************************
  ==========Summernote initialization start==========
  ******************************************************/
  $(".summernote").each(function (i) {
    let theight;
    let $summernote = $(this);
    if ($(this).data('height')) {
      theight = $(this).data('height');
    } else {
      theight = 200;
    }
    $('.summernote').eq(i).summernote({
      height: theight,
      dialogsInBody: true,
      dialogsFade: false,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['fontname', ['fontname']],
        ['fontsize', ['fontsize']],
        ['height', ['height']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']],
      ],
      popover: {
        image: [
          ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
          ['float', ['floatLeft', 'floatRight', 'floatNone']],
          ['remove', ['removeMedia']]
        ],
        link: [
          ['link', ['linkDialogShow', 'unlink']]
        ],
        table: [
          ['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
          ['delete', ['deleteRow', 'deleteCol', 'deleteTable']],
        ],
        air: [
          ['color', ['color']],
          ['font', ['bold', 'underline', 'clear']],
          ['para', ['ul', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture']]
        ]
      },
      callbacks: {
        onImageUpload: function (files) {
          $(".request-loader").addClass('show');

          let fd = new FormData();
          fd.append('image', files[0]);

          $.ajax({
            url: imgupload,
            method: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            success: function (data) {
              $summernote.summernote('insertImage', data);
              $(".request-loader").removeClass('show');
            }
          });

        }
      }
    });
  });



  $(document).on('click', ".note-video-btn", function () {

    let i = $(this).index();

    if ($(".summernote").eq(i).parents(".modal").length > 0) {

      setTimeout(() => {
        $("body").addClass('modal-open');
      }, 500);
    }
  });


  /* ***************************************************
  ==========Summernote initialization end==========
  ******************************************************/




  $('.icp-dd').iconpicker();
  $('.icp').on('iconpickerSelected', function (event) {
    $("#inputIcon").val($(".iconpicker-component").find('i').attr('class'));
  });


  /* ***************************************************
  ==========Summernote initialization end==========
  ******************************************************/



  /* ***************************************************
  ==========Bootstrap Notify start==========
  ******************************************************/
  function bootnotify(message, title, type) {
    var content = {};

    content.message = message;
    content.title = title;
    content.icon = 'fa fa-bell';

    $.notify(content, {
      type: type,
      placement: {
        from: 'top',
        align: 'right'
      },
      showProgressbar: true,
      time: 1000,
      allow_dismiss: true,
      delay: 4000,
    });
  }


  /* ***************************************************
  ==========Bootstrap Notify end==========
  ******************************************************/

  /* ***************************************************
  ====sub category Gatter for Item Upload start ========
  ******************************************************/


  // get sub category for item insert
  $(document).on('change', '.getSubCategory', function () {
    let url = $("#subcatGetterForItem").attr('value');
    let id = $(this).val();
    let code = $(this).data('code');

    var formData = new FormData();
    formData.append('url', url);
    formData.append('category_id', id);
    formData.append('code', code);
    $.ajax({
      url: url,
      method: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        jQuery("#" + code + '_subcategory').empty();
        jQuery.each(response.subcategories, function (key, value) {
          jQuery("#" + code + '_subcategory').append('<option value="' + value.id + '">' + value.name + '</option>')
        });
      },
      error: function (data) {
        console.log('Error......');
      }
    });
  });

  /* ***************************************************
  ====sub category Gatter for Item Upload End ========
  ******************************************************/





  /* ***************************************************
  ==========Form Submit with AJAX Request Start==========
  ******************************************************/
  $("#submitBtn").on('click', function (e) {
    $(e.target).attr('disabled', true);

    $(".request-loader").addClass("show");

    let ajaxForm = document.getElementById('ajaxForm');
    let fd = new FormData(ajaxForm);
    let url = $("#ajaxForm").attr('action');
    let method = $("#ajaxForm").attr('method');

    if ($("#ajaxForm .summernote").length > 0) {
      $("#ajaxForm .summernote").each(function (i) {
        let content = $(this).summernote('code');

        fd.delete($(this).attr('name'));
        fd.append($(this).attr('name'), content);
      });
    }

    $.ajax({
      url: url,
      method: method,
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $(e.target).attr('disabled', false);
        $(".request-loader").removeClass("show");

        $(".em").each(function () {
          $(this).html('');
        })

        if (data == "success") {
          location.reload();
        }

        // if error occurs
        else if (typeof data.error != 'undefined') {
          for (let x in data) {
            if (x == 'error') {
              continue;
            }
            document.getElementById('err' + x).innerHTML = data[x][0];
          }
        }
      },
      error: function (error) {
        $(".em").each(function () {
          $(this).html('');
        })
        for (let x in error.responseJSON.errors) {
          document.getElementById('err' + x).innerHTML = error.responseJSON.errors[x][0];
        }
        $(".request-loader").removeClass("show");
        $(e.target).attr('disabled', false);
      }
    });
  });

  $("#disqusSubmitBtn").on('click', function (e) {
    $(e.target).attr('disabled', true);

    $(".request-loader").addClass("show");

    let ajaxFormDisqus = document.getElementById('ajaxFormDisqus');
    let fd = new FormData(ajaxFormDisqus);
    const form = $("#ajaxFormDisqus");
    const summernoteForm = $("#ajaxFormDisqus .summernote");
    let url = form.attr('action');
    let method = form.attr('method');

    if (summernoteForm.length > 0) {
      summernoteForm.each(function (i) {
        let content = $(this).summernote('code');
        fd.delete($(this).attr('name'));
        fd.append($(this).attr('name'), content);
      });
    }

    $.ajax({
      url: url,
      method: method,
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $(e.target).attr('disabled', false);
        $(".request-loader").removeClass("show");

        $(".em").each(function () {
          $(this).html('');
        })

        if (data == "success") {
          location.reload();
        }

        // if error occurs
        else if (typeof data.error != 'undefined') {
          for (let x in data) {
            if (x == 'error') {
              continue;
            }
            document.getElementById('err' + x).innerHTML = data[x][0];
          }
        }
      },
      error: function (error) {
        $(".em").each(function () {
          $(this).html('');
        })
        for (let x in error.responseJSON.errors) {
          document.getElementById('err' + x).innerHTML = error.responseJSON.errors[x][0];
        }
        $(".request-loader").removeClass("show");
        $(e.target).attr('disabled', false);
      }
    });
  });

  $("#recaptchaSubmitBtn").on('click', function (e) {
    $(e.target).attr('disabled', true);
    $(".request-loader").addClass("show");
    let ajaxFormRecaptcha = document.getElementById('ajaxFormRecaptcha');
    let fd = new FormData(ajaxFormRecaptcha);
    const form = $("#ajaxFormRecaptcha");
    const summernoteForm = $("#ajaxFormRecaptcha .summernote");
    let url = form.attr('action');
    let method = form.attr('method');

    if (summernoteForm.length > 0) {
      summernoteForm.each(function (i) {
        let content = $(this).summernote('code');
        fd.delete($(this).attr('name'));
        fd.append($(this).attr('name'), content);
      });
    }

    $.ajax({
      url: url,
      method: method,
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {
        $(e.target).attr('disabled', false);
        $(".request-loader").removeClass("show");

        $(".em").each(function () {
          $(this).html('');
        })

        if (data == "success") {
          location.reload();
        }

        // if error occurs
        else if (typeof data.error != 'undefined') {
          for (let x in data) {
            if (x == 'error') {
              continue;
            }
            document.getElementById('err' + x).innerHTML = data[x][0];
          }
        }
      },
      error: function (error) {
        $(".em").each(function () {
          $(this).html('');
        })
        for (let x in error.responseJSON.errors) {
          document.getElementById('err' + x).innerHTML = error.responseJSON.errors[x][0];
        }
        $(".request-loader").removeClass("show");
        $(e.target).attr('disabled', false);
      }
    });
  });

  $("#permissionBtn").on('click', function () {
    $("#permissionsForm").trigger("submit");
  });

  $("#langBtn").on('click', function () {
    $("#langForm").trigger("submit");
  });
  /* ***************************************************
  ==========Form Submit with AJAX Request End==========
  ******************************************************/

  /* ***************************************************
  ==========datatables start==========
  ******************************************************/
  $('#basic-datatables').DataTable({
    responsive: true,
    ordering: false,
    searching: false
  });
  /* ***************************************************
  ==========datatables end==========
  ******************************************************/


  /* ***************************************************
  ==========Form Prepopulate After Clicking Edit Button Start==========
  ******************************************************/
  $(".editbtn").on('click', function () {
    let datas = $(this).data();
    delete datas['toggle'];

    for (let x in datas) {
      if ($("#in" + x).hasClass('summernote')) {
        $("#in" + x).summernote('code', datas[x]);
      } else if ($("#in" + x).hasClass('image')) {
        $("#in" + x).attr('src', datas[x]);
      } else if ($("#in" + x).data('role') == 'tagsinput') {
        if (datas[x].length > 0) {
          let arr = datas[x].split(" ");
          for (let i = 0; i < arr.length; i++) {
            $("#in" + x).tagsinput('add', arr[i]);
          }
        } else {
          $("#in" + x).tagsinput('removeAll');
        }
      }
      else if ($("input[name='" + x + "']").attr('type') == 'radio') {
        $("input[name='" + x + "']").each(function (i) {
          if ($(this).val() == datas[x]) {
            $(this).prop('checked', true);
          }
        });
      }
      else {
        $("#in" + x).val(datas[x]);
      }
    }
  });

  /* ***************************************************
  ==========Form Prepopulate After Clicking Edit Button End==========
  ******************************************************/




  /* ***************************************************
  ==========Form Update with AJAX Request Start==========
  ******************************************************/
  $("#updateBtn").on('click', function (e) {
    $(".request-loader").addClass("show");

    let ajaxEditForm = document.getElementById('ajaxEditForm');
    let fd = new FormData(ajaxEditForm);
    let url = $("#ajaxEditForm").attr('action');
    let method = $("#ajaxEditForm").attr('method');

    if ($("#ajaxEditForm .summernote").length > 0) {
      $("#ajaxEditForm .summernote").each(function (i) {
        let content = $(this).summernote('code');
        fd.delete($(this).attr('name'));
        fd.append($(this).attr('name'), content);
      })
    }

    $.ajax({
      url: url,
      method: method,
      data: fd,
      contentType: false,
      processData: false,
      success: function (data) {

        $(".request-loader").removeClass("show");

        $(".em").each(function () {
          $(this).html('');
        })

        if (data == "success") {
          location.reload();
        }

        // if error occurs
        else if (typeof data.error != 'undefined') {
          for (let x in data) {
            if (x == 'error') {
              continue;
            }
            document.getElementById('eerr' + x).innerHTML = data[x][0];
          }
        }
      },
      error: function (error) {
        $(".request-loader").removeClass("show");

        $(".em").each(function () {
          $(this).html('');
        })
        const data = error.responseJSON.errors;
        for (let x in data) {
          if (x === 'error') {
            continue;
          }
          document.getElementById('eerr' + x).innerHTML = data[x][0];
        }
      }
    });
  });

  $(".update-btn").each(function () {
    $(this).on('click', function (e) {
      let $this = $(this);

      $(".request-loader").addClass("show");

      let formId = $(this).data('form_id');
      let ajaxEditForm = document.getElementById(formId);
      let fd = new FormData(ajaxEditForm);
      let url = $("#" + formId).attr('action');
      let method = $("#" + formId).attr('method');

      if ($("#" + formId + " .summernote").length > 0) {
        $("#" + formId + " .summernote").each(function (i) {
          let content = $(this).summernote('code');
          fd.delete($(this).attr('name'));
          fd.append($(this).attr('name'), content);
        })
      }

      $.ajax({
        url: url,
        method: method,
        data: fd,
        contentType: false,
        processData: false,
        success: function (data) {
          let parentCount = $this.parents('.modal').length;
          let parentId;
          // if the form is in modal
          if (parentCount > 0) {
            parentId = $this.parents('.modal').attr('id');
          }
          // if the form is not in modal
          else {
            parentId = formId;
          }
          $(".request-loader").removeClass("show");

          $("#" + parentId).children(".em").each(function () {
            $(this).html('');
          })

          if (data == "success") {
            location.reload();
          }

          // if error occurs
          else if (typeof data.error != 'undefined') {
            for (let x in data) {
              if (x == 'error') {
                continue;
              }
              $("#" + parentId + " .eerr" + x).html(data[x][0]);
            }
          }
        }
      });
    });
  });
  /* ***************************************************
  ==========Form Update with AJAX Request End==========
  ******************************************************/



  /* ***************************************************
  ==========Delete Using AJAX Request Start==========
  ******************************************************/
  $('.deletebtn').on('click', function (e) {
    e.preventDefault();

    $(".request-loader").addClass("show");

    swal({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      type: 'warning',
      buttons: {
        confirm: {
          text: 'Yes, delete it!',
          className: 'btn btn-success'
        },
        cancel: {
          visible: true,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        $(this).parent(".deleteform").trigger('submit');
      } else {
        swal.close();
        $(".request-loader").removeClass("show");
      }
    });
  });
  /* ***************************************************
  ==========Delete Using AJAX Request End==========
  ******************************************************/


  /* ***************************************************
  ==========Close Ticket Using AJAX Request Start==========
  ******************************************************/
  $('.close-ticket').on('click', function (e) {
    e.preventDefault();

    $(".request-loader").addClass("show");

    swal({
      title: 'Are you sure?',
      text: "You want to close this ticket!",
      type: 'warning',
      buttons: {
        confirm: {
          text: 'Yes, close it!',
          className: 'btn btn-success'
        },
        cancel: {
          visible: true,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        swal.close();
        $(".request-loader").removeClass("show");
      } else {
        swal.close();
        $(".request-loader").removeClass("show");
      }
    });
  });
  /* ***************************************************
  ==========Delete Using AJAX Request End==========
  ******************************************************/


  /* ***************************************************
  ==========Delete Using AJAX Request Start==========
  ******************************************************/
  $(document).on('change', '.bulk-check', function () {
    let val = $(this).data('val');
    let checked = $(this).prop('checked');

    // if selected checkbox is 'all' then check all the checkboxes
    if (val == 'all') {
      if (checked) {
        $(".bulk-check").each(function () {
          $(this).prop('checked', true);
        });
      } else {
        $(".bulk-check").each(function () {
          $(this).prop('checked', false);
        });
      }
    }


    // if any checkbox is checked then flag = 1, otherwise flag = 0
    let flag = 0;
    $(".bulk-check").each(function () {
      let status = $(this).prop('checked');

      if (status) {
        flag = 1;
      }
    });

    // if any checkbox is checked then show the delete button
    if (flag == 1) {
      $(".bulk-delete").addClass('d-inline-block');
      $(".bulk-delete").removeClass('d-none');
    }
    // if no checkbox is checked then hide the delete button
    else {
      $(".bulk-delete").removeClass('d-inline-block');
      $(".bulk-delete").addClass('d-none');
    }
  });

  $('.bulk-delete').on('click', function () {

    swal({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      type: 'warning',
      buttons: {
        confirm: {
          text: 'Yes, delete it!',
          className: 'btn btn-success'
        },
        cancel: {
          visible: true,
          className: 'btn btn-danger'
        }
      }
    }).then((Delete) => {
      if (Delete) {
        $(".request-loader").addClass('show');
        let href = $(this).data('href');
        let ids = [];

        // take ids of checked one's
        $(".bulk-check:checked").each(function () {
          if ($(this).data('val') != 'all') {
            ids.push($(this).data('val'));
          }
        });

        let fd = new FormData();
        for (let i = 0; i < ids.length; i++) {
          fd.append('ids[]', ids[i]);
        }

        $.ajax({
          url: href,
          method: 'POST',
          data: fd,
          contentType: false,
          processData: false,
          success: function (data) {

            $(".request-loader").removeClass('show');
            if (data == "success") {
              location.reload();
            }
          }
        });
      } else {
        swal.close();
      }
    });

  });
  /* ***************************************************
  ==========Delete Using AJAX Request End==========
  ******************************************************/


  //  image (id) preview js/
  $(document).on('change', '#image', function (event) {
    var file = event.target.files[0];
    var reader = new FileReader();
    reader.onload = function (e) {
      $('.showImage img').attr('src', e.target.result);
    };

    reader.readAsDataURL(file);
  })
  //  image (class) preview js/
  $(document).on('change', '.image', function (event) {
    let $this = $(this);
    var file = event.target.files[0];
    var reader = new FileReader();
    reader.onload = function (e) {
      $this.prev('.showImage').children('img').attr('src', e.target.result);
    };

    reader.readAsDataURL(file);
  });

  //image 2 preview js
  $(document).on('change', '#image_2', function (event) {
    let $this = $(this);
    var file = event.target.files[0];
    var reader = new FileReader();
    reader.onload = function (e) {
      $this.prev('#showImage_2').children('img').attr('src', e.target.result);
    };
    reader.readAsDataURL(file);
  });


  //end image 2 preview js

  // datepicker & timepicker
  $("input.datepicker").datepicker();
  $('input.timepicker').timepicker();

  // select2
  $('.select2').select2();
});




function cloneInput(fromId, toId, event) {
  let $target = $(event.target);
  let $formId = $('#' + fromId);

  if ($target.is(':checked')) {
    $('#' + fromId + ' .form-control').each(function (i) {
      let index = i;
      let val = $(this).val();
      let $toInput = $('#' + toId + ' .form-control').eq(index);
      // console.log($toInput)
      if ($(this).hasClass('summernote')) {
        $toInput.summernote('code', val);
      } else if ($(this).data('role') == 'tagsinput') {
        if (val.length > 0) {
          let tags = val.split(',');
          tags.forEach(tag => {
            $toInput.tagsinput('add', tag);
          });
        } else {
          $toInput.tagsinput('removeAll');
        }
      } else if ($(this).data('role') == 'checkbox') {
        if ($(this).is(':checked')) {
          $toInput.prop('checked', true);
        }
      } else {
        $toInput.val(val);
      }
    });
  } else {
    $('#' + toId + ' .form-control').each(function (i) {
      let $toInput = $('#' + toId + ' .form-control').eq(i);

      if ($(this).hasClass('summernote')) {
        $toInput.summernote('code', '');
      } else if ($(this).data('role') == 'tagsinput') {
        $toInput.tagsinput('removeAll');
      } else {
        $toInput.val('');
      }
    });
  }
}

$(document).on('change', '.langBtn', function () {
  let $this = $(this);
  var $code = $(this).val();

  $.ajax({
    url: $("#setLocale").val(),
    method: 'get',
    data: {
      code: $code
    },
    success: function (data) {
      // console.log(curr_url+$this.val())
      window.location = curr_url + $this.val();
    }
  });
})

