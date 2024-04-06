"use strict";

function cloneInput(fromId, toId, event) {
    let $target = $(event.target);

    if($target.is(':checked')) {
        $('#' + fromId + ' .form-control').each(function(i) {
            let index = i;
            let val = $(this).val();
            let $toInput = $('#' + toId + ' .form-control').eq(index);

            if ($(this).hasClass('summernote')) {
                $toInput.summernote('code', val);
            } else if ($(this).data('role') == 'tagsinput') {
                if(val.length > 0) {
                    let tags = val.split(',');
                    tags.forEach(tag => {
                        $toInput.tagsinput('add', tag);
                    });
                } else {
                    $toInput.tagsinput('removeAll');
                }
            } else {
                $toInput.val(val);
            }
        });
    } else {
        $('#' + toId + ' .form-control').each(function(i) {
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

$(document).ready(function() {
    $('#pageForm').on('submit', function(e) {
        $('.request-loader').addClass('show');
        e.preventDefault();

        let action = $('#pageForm').attr('action');
        let fd = new FormData(document.querySelector('#pageForm'));

        $.ajax({
            url: action,
            method: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            success: function(data) {
                $('.request-loader').removeClass('show');

                if (data == 'success') {
                    window.location = currUrl;
                }
            },
            error: function(error) {
                $('#pageErrors').show();
                let errors = ``;

                for (let x in error.responseJSON.errors) {
                    errors += `<li>
              <p class="text-danger mb-0">${ error.responseJSON.errors[x][0] }</p>
            </li>`;
                }

                $('#pageErrors ul').html(errors);

                $('.request-loader').removeClass('show');

                $('html, body').animate({
                    scrollTop: $('#pageErrors').offset().top - 100
                }, 1000);
            }
        });
    });
});

$(document).ready(function() {
    $('#pageFormSubmit').on('click', function(e) {
        $('.request-loader').addClass('show');
        e.preventDefault();

        let action = $('#pageForm').attr('action');
        let fd = new FormData(document.querySelector('#pageForm'));

        $.ajax({
            url: action,
            method: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            success: function(data) {
                $('.request-loader').removeClass('show');

                if (data == 'success') {
                    window.location = currUrl;
                }
            },
            error: function(error) {
                $('#pageErrors').show();
                let errors = ``;

                for (let x in error.responseJSON.errors) {
                    errors += `<li>
              <p class="text-danger mb-0">${ error.responseJSON.errors[x][0] }</p>
            </li>`;
                }

                $('#pageErrors ul').html(errors);

                $('.request-loader').removeClass('show');

                $('html, body').animate({
                    scrollTop: $('#pageErrors').offset().top - 100
                }, 1000);
            }
        });
    });
});