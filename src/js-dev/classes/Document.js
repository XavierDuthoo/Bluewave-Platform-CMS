/* globals Settings:true */
var Document = (function () {

    var Document = function ($el, siteid) {
        _.bindAll(this);

        this.$el = $el;
        this.siteid = siteid;
        this.settings = new Settings();
        this.numberOfCallsToServer = 0;

        // Prevent default submit that fileupload.js triggers when choosing a file
        this.$el.on('submit', function (e) {
            e.preventDefault();
            return false;
        });

        this.bind();
        this.initFileUploadFields();
    };

    Document.prototype.bind = function() {
        $(this.$el).on('click', '.js-button-trigger-file', function (e) {
            e.preventDefault();
            $(this).next().find('input[type="file"]').trigger('click');
        });
    };

    Document.prototype.initFileUploadFields = function() {
        var that = this;

        this.$el.find('input[type=file]').fileupload({
            url: 'server/php/',
            dataType: 'json',
            dropZone: null,
            pasteZone: null,
            singleFileUploads: false,
            change: function (e, data) {
                $(e.target).parent().parent().prev().val(data.files[0].name);
            },
            add: function (event, data) {
                that.$el.on('submit', '.ajax-file-upload', function (event) {
                    event.preventDefault();
                    console.log(that.$el.find('.ajax-file-upload'));
                    console.log('Number of calls to server : ' + that.numberOfCallsToServer);

                    var jqxhr = data.submit().complete(function (result, textStatus, jqXHR) {
                        var fileInputs = that.$el.find('input[type=file]');
                        var filename1 = $(fileInputs[0]).parent().parent().prev().val();
                        var filename2 = $(fileInputs[1]).parent().parent().prev().val();

                        that.saveAllDataToServer(event, filename1, filename2);
                    });
                });

                return false;
            },
            done: function(e,data){
                that.numberOfCallsToServer = true;
            }
        });
    };

    Document.prototype.saveAllDataToServer = function(event, filename1, filename2) {
        var that = this;

        var isNew = false;
        var callURL = '/index.php?page=sites&action=editBlock';
        var form = $(event.currentTarget);

        if(this.$el.attr('data-new') === 'true') {
            callURL = '/index.php?page=sites&action=addBlock';
            isNew = true;
        }

        if(that.numberOfCallsToServer >= 1) {
            return false;
        }

        that.numberOfCallsToServer++;

        var $submit = $(event.currentTarget).find('input[type=submit]');
            $submit.attr('disabled', 'disabled');
            $submit.val('Bezig...');

        var args = {};
        args.site_id = this.siteid;
        args.name = this.$el.find('#name').val();
        args.cover = filename1;
        args.url = filename2;
        args.order_number = 0;
        args.context = 'document';

        $.ajax({
            type: 'POST',
            url: that.settings.URI + callURL,
            data: args,
            success: function(data) {
                that.$el.attr('data-new', false);
                that.$el.find('#id').val(data);
                that.$el.attr('data-blockid', data);

                $submit.removeAttr('disabled');
                $submit.val('Opslaan');
            }
        });
    };

    return Document;
})();