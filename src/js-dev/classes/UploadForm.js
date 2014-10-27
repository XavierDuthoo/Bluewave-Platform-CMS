var UploadForm = (function () {

    var UploadForm = function ($element) {
        _.bindAll(this);
        this.$element = $element;

        this.$element.on('submit', function (e) {
            console.log('PREVENT');
            e.preventDefault();
            return false;
        });

        this.initForm();
    };

    UploadForm.prototype.initForm = function() {
        var that = this;

        this.$element.find('input[type=file]').fileupload({
            url: 'server/php/',
            dataType: 'json',
            dropZone: null,
            pasteZone: null,
            change: function (e, data) {
                console.log(that.$element);
                $.each(data.files, function (index, file) {
                    console.log(that.$element);
                    that.$element.parent().parent().parent().find('input[type=text]').val(file.name);
                });
            },
            add: function (event, data) {
                $("#up_btn").on('click', function () {
                    console.log(data);
                    var jqxhr = data.submit().success(function (result, textStatus, jqXHR) {console.log('success');})
                                             .error(function (jqXHR, textStatus, errorThrown) {console.log(jqXHR, textStatus, errorThrown);})
                                             .complete(function (result, textStatus, jqXHR) {console.log('complete');});
                });

                return false;
            }
        });
    };

    return UploadForm;
})();