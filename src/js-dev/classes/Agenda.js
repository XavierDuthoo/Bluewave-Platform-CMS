/* globals Settings:true, moment:true, modal:true */
var Agenda = (function () {

    var Agenda = function ($el, siteid) {
        _.bindAll(this);

        this.$el = $el;
        this.settings = new Settings();
        this.agendaId = this.$el.find('#id').val();
        this.siteid = siteid;

        this.initDateTimePickers();
        this.initFileUpload();
        this.initWysiwyg();
        this.bind();
    };

    Agenda.prototype.bind = function() {
        this.$el.on('click', '#add-event', this.addEvent);

        this.$el.find('.trigger-add-event').on('click', function(e) {
            $(e.currentTarget).parent().prev().find('form').submit();
        });

        $(this.$el).on('click', '.js-button-trigger-file', function (e) {
            e.preventDefault();
            $(this).next().find('input[type="file"]').trigger('click');
        });
    };

    Agenda.prototype.initDateTimePickers = function() {
        $('.datetimepicker').datetimepicker({
            language: 'nl-NL',
            minuteStepping: 15,
        });
    };

    Agenda.prototype.initFileUpload = function() {
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
                that.$el.on('submit', '.add-event', function (event) {
                    var jqxhr = data.submit().complete(function (result, textStatus, jqXHR) {
                        var fileInputs = that.$el.find('input[type=file]');
                        var filename1 = $(fileInputs[0]).parent().parent().prev().val();

                        that.saveAllDataToServer(event, filename1);
                    });
                
                    return false;
                });
            }
        });
    };

    Agenda.prototype.initWysiwyg = function() {
        this.$el.find('#wysiwyg').wysihtml5({color: true, html: true});
    };

    Agenda.prototype.addEvent = function(e) {
        e.preventDefault();
        console.log('add event');
    };

    Agenda.prototype.saveAllDataToServer = function(e, filename) {
        e.preventDefault();
        var that = this;

        var isNew = false;
        var callURL = '/index.php?page=sites&action=editBlock';
        var form = $(e.currentTarget);

        if(this.$el.attr('data-new') === 'true') {
            callURL = '/index.php?page=sites&action=addBlock';
            isNew = true;
        }

        var $submit = $(e.currentTarget).find('input[type=submit]');
            $submit.attr('disabled', 'disabled');
            $submit.val('Bezig...');

        var newEvent = {};
        newEvent.site_id = this.siteid;
        newEvent.agenda_id = this.agendaId;
        newEvent.event_title     = this.$el.find('#event_title').val();
        newEvent.start_date = moment(this.$el.find('#start_date').val(), 'DD-MM-YYYY h:mm').format('YYYY-MM-DD h:mm');
        newEvent.end_date = moment(this.$el.find('#end_date').val(), 'DD-MM-YYYY h:mm').format('YYYY-MM-DD h:mm');
        newEvent.image = filename;
        newEvent.content = this.$el.find('#wysiwyg').val();
        newEvent.fb_url = this.$el.find('#fb_url').val();
        newEvent.order_number = 0;
        newEvent.context = 'event';

        var newAgenda = {};
        newAgenda.name = this.$el.find('#name').val();
        newAgenda.siteid = this.siteid;
        newAgenda.order_number = 0;
        newAgenda.site_id = this.siteid;
        newAgenda.context = 'agenda';

        if(this.agendaId === "") {
            this.saveAgenda(newAgenda, callURL, newEvent, $submit);
        } else {
            console.log(callURL);
            this.saveEvent(newEvent, callURL);
        }

    };

    Agenda.prototype.saveAgenda = function(newAgenda, callURL, newEvent, $submit) {
        var that = this;

        $.ajax({
            type: 'POST',
            url: that.settings.URI + callURL,
            data: newAgenda,
            success: function(data) {
                that.$el.attr('data-new', false);
                that.$el.find('#id').val(data);
                that.$el.attr('data-blockid', data);

                $submit.removeAttr('disabled');
                $submit.val('Opslaan');

                if(newEvent !== undefined) {
                    newEvent.agenda_id = that.$el.find('#id').val();
                    that.saveEvent(newEvent, '/index.php?page=sites&action=addBlock');
                }
            }
        });
    };

    Agenda.prototype.saveEvent = function(args, url) {
        var that = this;
        $.ajax({
            type: 'POST',
            url: that.settings.URI + url,
            data: args,
            success: function(data) {
                $('#addEvent').modal('hide');
                that.$el.find('#addEvent').modal('hide');

                // Empty the form
                var forms = that.$el.find('#addEvent').find('form');
                forms[0].reset();
            }
        });
    };

    return Agenda;
})();