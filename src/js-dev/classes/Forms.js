/* globals Settings:true, google:true */
var Forms = (function () {

    var Forms = function () {
        _.bindAll(this);

        if($("form").length === 0) {
            return false;
        }

        if($('.spinner-input').length > 0) {
            var spinner = $('#timer-seconds').spinner({min: 0, max: 999, step: 1});
            spinner.spinner('value', $('#timer-seconds').attr('data-initvalue'));
        }

        this.settings = new Settings();
        this.bind();
    };

    Forms.prototype.bind = function() {
        $(".validate").validate({
            highlight: function(element) {
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
            },
            success: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
            }
        });

        $(".avatar-preview").click(this.triggerInput);
        $('.preview-file').change(this.previewImage);
        $('.choosePass').submit(this.checkPasswords);
        $('#current-website').change(this.updateActiveSite);
    };

    Forms.prototype.triggerInput = function(event) {
        event.preventDefault();
        $('input[type=file]').trigger('click');
    };

    Forms.prototype.previewImage = function(event) {
        this.readURL(document.getElementById("avatar"));
    };

    Forms.prototype.readURL = function(input) {
        if (input.files && input.files[0]) {
            if(!input.files[0].type.match('image.*')) {
                return false;
            }

            var reader = new FileReader();
            
            reader.onload = function (e) {
                var image = new Image();
                image.src = e.target.result;
                console.log(image.width, image.height);
                $("#uploadPreview").css('background-image', 'url(' + e.target.result + ')');

                if(image.width > image.height) {
                    $("#uploadPreview").css({'background-size': 'auto 100%'});
                } else {
                    $("#uploadPreview").css({'background-size': '100% auto'});
                }

                $('.avatar-preview').addClass('chosen');
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    };

    Forms.prototype.checkPasswords = function(event) {
        if($("#password").val() !== $("#password_repeat").val()) {
            $('p span.notequal').removeClass('hide');
            event.preventDefault();
        } else {
            if($("#password").val().length < 6) {
                $('p span.length').addClass('error');
                event.preventDefault();
            }
        }
    };

    Forms.prototype.updateActiveSite = function(event) {
        console.log('change');
        console.log($('#current-website option:selected').attr('data-link'));
    };

    return Forms;
})();