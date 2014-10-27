(function(){

var Settings = (function() {

    var Settings = function() {
        this.URI = 'http://' + window.location.host + '/platform/src';
        this.API = this.URI + '/api';
        this.messages = {};
    };

    return Settings;

})();

var Applicaties = (function () {

    var Applicaties = function () {
        if (!(~document.URL.indexOf('sites'))) {
            return;
        }

        this.init();
        this.settings = new Settings();
    };

    Applicaties.prototype.init = function() {

        var that = this;

        //APPLICATIES -> Het netwerk veranderen in de options menu zorgt ervoor dat het input veld verandert
        $('#netwerken').change(function(evt){
            $('#ssid').val($('#netwerken option:selected').text());
        });

        //APPLICATIES -> site var is de active site
        var site = $('.site-view-link.active').attr('data-identifier');

        //APPLICATIES -> Als er op de opslaan button van netwerk ssid geklikt is dan veranderen we de ssid
        $('.btn-primary.ssid').click(function(evt){
            $.ajax({
                type: 'POST',
                data: {wlan_id: $('#netwerken option:selected').attr('data-id'), new_name: $('#ssid').val()},
                url: that.settings.API + '/restapi.php/' + site + '/upd/wlanconf',
                success: function(data) {

                }
            });

            return false;
        });

        //APPLICATIES -> Als er op de opslaan button van landing page geklikt is dan veranderen we de landing page
        $('.btn-primary.landingpage').click(function(evt){
            $.ajax({
                type: 'POST',
                data: {redirect_url: $('#landing').val()},
                url: that.settings.API + '/restapi.php/' + site + '/set/settings',
                success: function(data) {
                }
            });

            return false;
        });

        //APPLICATIES -> OVERZICHT -> Een site toevoegen met naam en beschrijving toevoegen. Naam is de api sitenaam
        $('.btn-primary.add-site').click(function(evt){
            $.ajax({
                type: 'POST',
                data: {name: $('#sitename').val(), desc: $('#identifier').val()},
                url: that.settings.API + '/restapi.php/seasons/set/site',
                success: function(data) {
                    if ( data === 'true' ) {
                        location.reload();
                    }
                }
            });

            return false;
        });

        //APPLICATIES -> OVERZICHT -> Een site verwijderen met id en site
        $('.delete-row').click(function(evt){
            $.ajax({
                type: 'POST',
                data: {id: $(evt.currentTarget).attr('data-deleteInSidebar'), site: $(evt.currentTarget).attr('data-site')},
                url: that.settings.API + '/restapi.php/' + $(evt.currentTarget).attr('data-site') + '/remove/site',
                success: function(data) {
                    if ( data === 'true' ) {
                        location.reload();
                    }
                }
            });
        });

        //APPLICATIES -> OVERZICHT -> Een site wijzigen
        $('.btn-primary.wijzigen').click(function(evt){
            $.ajax({
                type: 'POST',
                data: {identifier: $('#identifier').val(), newname: $('[name=sitename]').val()},
                url: that.settings.API + '/restapi.php/' + $('#identifier').val() + '/edit/site',
                success: function(data) {
                    if ( data === 'true' ) {
                        if ($('.alert').length > 0) {
                            console.log('klaar');
                            $('.alert').fadeOut();
                            $('.alert').fadeIn();
                        } else {
                            $('.contentpanel').before('<div class="alert alert-success">De gebruiker werd aangepast!</div>').fadeIn();
                        }
                    }
                }
            });

            return false;
        });
    }

    return Applicaties;

})();

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
        SiteStats.getStatus();
    };

    return Forms;
})();

/* globals Settings:true */
var Paragraph = (function () {

    var Paragraph = function ($el, siteid) {
        _.bindAll(this);

        this.$el = $el;
        this.siteid = siteid;

        this.$el.find('#site_id').val(this.siteid);
        this.settings = new Settings();

        this.initWysiwyg();
        this.initNewColorPicker(this.$el.find('span#colorSelector'));
    };

    Paragraph.prototype.initWysiwyg = function() {
        this.$el.find('#wysiwyg').wysihtml5({color: true, html: true});
        this.$el.on('submit', '.ajax-update-complex', this.save);
    };

    Paragraph.prototype.initNewColorPicker = function(holder) {
        $(holder).ColorPicker({
            onShow: function (colpkr) {
                $(colpkr).stop().fadeIn(500);
                return false;
            },
            onHide: function (colpkr) {
                $(colpkr).stop().fadeOut(500);
                return false;
            },
            onChange: function (hsb, hex, rgb) {
                $(holder).find('span').css('backgroundColor', '#' + hex);
                $(holder).parent().parent().find('#title_color').val('#'+hex);
            }
        });

        $(holder).parent().parent().find('#title_color').val('#000000');
    };

    Paragraph.prototype.save = function(e) {
        e.preventDefault();
        var isNew = false,
            that = this,
            form = this.$el.find('form');

        var $submit = this.$el.find('input[type=submit]');
            $submit.attr('disabled', 'disabled');
            $submit.val('Bezig...');

        if(this.$el.attr('data-new') === 'true') {
            isNew = true;
        }

        var args = {};
        args.site_id = this.siteid;
        args.context = 'paragraph';
        args.title = this.$el.find('#title').val();
        args.title_color = this.$el.find('#title_color').val();
        args.content = this.$el.find('#wysiwyg').val();
        args.order_number = 0;

        if(isNew) {
            // Add as new
            $.ajax({
                type: 'POST',
                url: that.settings.URI + '/index.php?page=sites&action=addBlock',
                data: args,
                success: function(data) {
                    form.parent().attr('data-new', false);
                    form.find('#id').val(data);
                    form.parent().attr('data-blockid', data);

                    $submit.removeAttr('disabled');
                    $submit.val('Opslaan');
                }
            });
        } else {
            // Update existing
            // Add block id to arguments
            args.id = form.find('#id').val();
            args.order_number = form.attr('data-orderid');

            $.ajax({
                type: 'POST',
                url: that.settings.URI + '/index.php?page=sites&action=editBlock',
                data: args,
                success: function(data) {
                    $submit.removeAttr('disabled');
                    $submit.val('Opslaan');
                }
            });
        }
    };

    return Paragraph;
})();

/* globals Settings:true, Document:true, Agenda:true, Paragraph:true */

var SiteDashboard = (function () {

    var SiteDashboard = function () {
        _.bindAll(this);

        if (!(~document.URL.indexOf('start'))) {
            return;
        }

        this.devices = [];
        this.accessPoints = [];
        this.guestsAndUsers = [];
        this.settings = new Settings();
        this.allUsers = [];
        this.getStatus();
    };

    SiteDashboard.prototype.getStatus = function() {
        var that = this;

        //DASHBOARD -> ALL USERS -> all users ophalen, worden via cronjob opgehaald en in onze mysql database gestoken
        if (~document.URL.indexOf('users')) {
            $.ajax({
                type: 'GET',
                url: this.settings.API + '/restapi.php/seasons/users',
                success: function(data) {
                    that.allUsers = JSON.parse(data);
                    that.loadUsers();
                }
            });
        }

        //DASHBOARD -> LIVE -> alle users en guests ophalen en toevoegen aan onze tabel
        if (~document.URL.indexOf('livestats')) {
            $.ajax({
                type: 'GET',
                url: this.settings.API + '/restapi.php/seasons/stats/station',
                success: function(data) {
                    that.guestsAndUsers = JSON.parse(data);

                    // guests toevoegen bij guests en users toevoegen bij users
                    that.guestsAndUsers.forEach(function(user){
                        if (user.is_guest) {
                            $('.guests').find('tbody').append('<tr><td>' + ((user.hostname) ? user.hostname : user.mac) +
                                                            '</td><td>' + ((user.authorized) ? 'Authorized' : 'Pending') +
                                                            '</td><td>' + user.ip +
                                                            '</td><td>' + user.essid +
                                                            '</td><td>' + user.signal +
                                                            '</td><td>' + secondsToString(user._uptime) +
                                                            '</td><td><button attr-mac="' + user.mac + '" class="block">Block</button>' +
                                                            '<button attr-mac="' + user.mac + '" attr-minutes="60" class="' + ((user.authorized) ? 'unauthorize' : 'authorize') + '">' + ((user.authorized) ? 'Unauthorize' : 'Authorize') + '</button></td></tr>');
                        } else {
                            $('.users').find('tbody').append('<tr><td>' + ((user.hostname) ? user.hostname : user.mac) +
                                                            '</td><td>' + user.ip +
                                                            '</td><td>' + user.essid +
                                                            '</td><td>' + secondsToString(user._uptime) +
                                                            '</td><td>' + user.signal +
                                                            '</td><td><button attr-mac="' + user.mac + '" class="block">Block</button><button attr-mac="' + user.mac + '" class="reconnect">Reconnect</button></td></tr>');
                        }
                    });

                    $('.guests').find('tbody').append('<tr><td><button class="authorizeall" attr-minutes=60>Authorize All</button></td>' +
                                                            '<td><button class="unauthorizeall">Unauthorize All</button></td>' +
                                                            '</td><td>' +
                                                            '</td><td>' +
                                                            '</td><td>' +
                                                            '</td></tr>');

                    $('.users').find('tbody').append('<tr><td><button class="reconnectall">Reconnect All</button></td>' +
                                                            '<td></td>' +
                                                            '</td><td>' +
                                                            '</td><td>' +
                                                            '</td><td>' +
                                                            '</td></tr>');

                    that.bind();
                }
            });
        }

        //DASHBOARD -> ACCESSPOINTS -> all accesspoints ophalen
        if (~document.URL.indexOf('accesspoints')) {

            $.ajax({
                type: 'GET',
                url: this.settings.API + '/restapi.php/seasons/stats/device',
                success: function(data) {

                    that.devices = JSON.parse(data);
                    var googleArray = [['name', 'users']];

                    //LOOPEN DOOR ALLE DATA EN INLADEN IN HTML
                    that.devices.forEach(function(device){
                        that.accessPoints.push({name: device.name, status: ((device['ng-state']) ? device['ng-state'] : device['na-state']), number_users: device.num_sta, ip: device.config_network.ip});
                        googleArray.push([device.name, device.num_sta]);

                        $('.devices').find('tbody').append('<tr><td>' + device.name +
                                                            '</td><td>' + ((((device['ng-state']) ? device['ng-state'] : device['na-state']) === 'RUN') ? 'CONNECTED' : 'DISCONNECTED') +
                                                            '</td><td>' + device.config_network.ip +
                                                            '</td><td>' + device.num_sta + '</td></tr>');
                    });

                    that.bind();
                }
            });
        }
    }

    SiteDashboard.prototype.loadUsers = function(){

        //we gebruiken slickgrid om zoveel data in te laden

        var newData = [];
        var that = this;

        //loopen door alle gebruikers en toevoegne in onze array
        this.allUsers.forEach(function(user){

            var a = new Date(((user.last_seen) ? user.last_seen : 0)*1000);
            var b = new Date(((user.first_seen) ? user.first_seen : 0)*1000);
            var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            var hours = a.getHours();
            var minutes = a.getMinutes();
            var year = a.getFullYear();
            var month = a.getMonth();
            var date = a.getDate();

            var first_hours = b.getHours();
            var first_minutes = b.getMinutes();
            var first_year = b.getFullYear();
            var first_month = b.getMonth();
            var first_date = b.getDate();

            newData.push({
                id: user.mac,
                hostname: ((user.hostname) ? user.hostname : user.mac),
                is_guest: ((user.is_guest) ? 'guest' : 'user'),
                first_seen: ((first_date.toString().length === 1 || first_date.toString().length === 0) ? ('0' + first_date) : first_date) + '/' + ((first_month.toString().length === 1 || first_month.toString().length === 0) ? ('0' + first_month) : first_month) + '/' + first_year + ' ' + first_hours + ':' + ((first_minutes.toString().length === 1 || first_minutes.toString().length === 0) ? ('0' + first_minutes) : first_minutes),
                last_seen: ((date.toString().length === 1 || date.toString().length === 0) ? ('0' + date) : date) + '/' + ((month.toString().length === 1 || month.toString().length === 0) ? ('0' + month) : month) + '/' + year + ' ' + hours + ':' + ((minutes.toString().length === 1 || minutes.toString().length === 0) ? ('0' + minutes) : minutes),
                actions: [user.mac, user.blocked]
            });
        });

        //alle opties instellen voor onze slickgrid
        var options = {
            enableCellNavigation: true,
            enableColumnReorder: false,
            asyncEditorLoading: true,
            forceFitColumns: false,
            multiColumnSort: true,
            rowHeight: 50,
            topPanelHeight: 100,
            syncColumnCellResize: true,
        };

        //columns instellen en id property linken met de waarde van de property in onze array
        var columns = [
            {
                id: "title",
                name: "Naam/MAC adres",
                field: "hostname",
                minWidth: 250,
                sortable: true,
                defaultSortAsc: true
            },
            {
                id: "is_guest",
                name: "User/Guest",
                field: "is_guest",
                minWidth: 150,
                sortable: true,
                defaultSortAsc: true
            },
            {
                id: "first_seen",
                name: "First Seen",
                minWidth: 230,
                field: "first_seen",
                sortable: true,
                defaultSortAsc: true
            },
            {
                id: "last_seen",
                name: "Last Seen",
                minWidth: 230,
                field: "last_seen",
                sortable: true,
                defaultSortAsc: true
            },
            {
                id: "actions",
                name: "Actions",
                minWidth: 140,
                field: "actions",
                sortable: true,
                defaultSortAsc: true,
                formatter: linkFormatter = function ( row, cell, value, columnDef, dataContext ) {
                    //zelf beslissen hoe het moet weergeven worden, in dit geval maken we button aan en kijken we als deze persoon al geblokt is of niet
                    return '<button attr-mac="' + value[0] + '" class="' + ((value[1] === "true") ? 'unblock' : 'block') + '">' + ((value[1] === "true") ? 'Unblock' : 'Block') + '</button>';
                }
            }
        ];

        // alles toevoegen
        var dataView = new Slick.Data.DataView({ inlineFilters: true });
        var grid = new Slick.Grid("#myGrid", dataView, columns, options);
        // voor meerdere paginas bij onze tabel
        var pager = new Slick.Controls.Pager(dataView, grid, $("#pager"));

        var percentCompleteThreshold = 0;
        var searchString = "";

        // een filter die we gebruiken voor ons zoekveld
        function myFilter(item, args) {

            if (args.searchString != "" && item["hostname"].indexOf(args.searchString) == -1) {
                return false;
            }

            return true;
        }

        dataView.onRowCountChanged.subscribe(function (e, args) {
            grid.updateRowCount();
            grid.render();
        });

        dataView.onRowsChanged.subscribe(function (e, args) {
            grid.invalidateRows(args.rows);
            grid.render();
        });

        function comparer(a, b) {
            var x = a[sortcol], y = b[sortcol];
            return (x == y ? 0 : (x > y ? 1 : -1));
        }

        // switchen van pagina in onze tabel
        dataView.onPagingInfoChanged.subscribe(function (e, pagingInfo) {
            var isLastPage = pagingInfo.pageNum == pagingInfo.totalPages - 1;
            var enableAddRow = isLastPage || pagingInfo.pageSize == 0;
            var options = grid.getOptions();

            if (options.enableAddRow != enableAddRow) {
              grid.setOptions({enableAddRow: enableAddRow});
            }
        });

        // sorteren van een column
        grid.onSort.subscribe(function (e, args) {
            sortcol = args.sortCols[0].sortCol.field;

            if ($.browser.msie && $.browser.version <= 8) {
                // using temporary Object.prototype.toString override
                // more limited and does lexicographic sort only by default, but can be much faster

                var percentCompleteValueFn = function () {
                    var val = this["percentComplete"];
                    if (val < 10) {
                        return "00" + val;
                    } else if (val < 100) {
                        return "0" + val;
                    } else {
                        return val;
                    }
                };
                  // use numeric sort of % and lexicographic for everything else
                  dataView.fastSort((sortcol == "percentComplete") ? percentCompleteValueFn : sortcol, args.sortAsc);
            } else {
                  // using native sort with comparer
                  // preferred method but can be very slow in IE with huge datasets
                  dataView.sort(comparer, args.sortCols[0].sortAsc);
            }
        });

        // zoeken in inputfield van zoekterm
        $("#txtSearch").keyup(function (e) {

            // clear on Esc
            if (e.which == 27) {
              this.value = "";
            }

            searchString = this.value;
            updateFilter();
            that.bind();
        });

        function updateFilter() {
            dataView.setFilterArgs({
                percentCompleteThreshold: percentCompleteThreshold,
                searchString: searchString
            });
            dataView.refresh();
        }

        dataView.beginUpdate();
        dataView.setItems(newData);
        dataView.setFilterArgs({
            percentCompleteThreshold: percentCompleteThreshold,
            searchString: searchString
        });
        dataView.setFilter(myFilter);
        dataView.endUpdate();

        //bind alle buttons
        this.bind();

    }

    function secondsToString (seconds) {

        var numdays = Math.floor(seconds / 86400);
        var numhours = Math.floor((seconds % 86400) / 3600);
        var numminutes = Math.floor(((seconds % 86400) % 3600) / 60);

        return ((numdays > 0) ? numdays + "d " : '') + ((numhours > 0) ? numhours + "h " : '') + ((numminutes > 0) ? numminutes + "m " : '');

    }

    SiteDashboard.prototype.bind = function() {

        var that = this;

        //block en unblock een user en mac adres meegeven, we kijken ook als het geblokt of het unblockt moet worden
        $('.block, .unblock').click(function(e){
            ($(e.currentTarget).attr('class') === 'block') ? $(e.currentTarget).html('Blocking...') : $(e.currentTarget).html('Unblocking...');
            $.ajax({
                type: 'POST',
                data: {mac: $(e.currentTarget).attr('attr-mac')},
                url: ($(e.currentTarget).attr('class') === 'block') ? that.settings.API + '/restapi.php/seasons/block-sta' : that.settings.API + '/restapi.php/seasons/unblock-sta',
                success: function(data) {
                    if (data === 'true') {
                        if ($(e.currentTarget).attr('class') === 'block') {
                            $(e.currentTarget).removeClass('block').addClass('unblock');
                            $(e.currentTarget).html('Unblock');
                        } else {
                            $(e.currentTarget).removeClass('unblock').addClass('block');
                            $(e.currentTarget).html('Block');
                        }
                    }
                }
            });
        });

        //reconnect
        $('.reconnect').click(function(e){
            $(e.currentTarget).text('Reconnecting...')
            $.ajax({
                type: 'POST',
                data: {mac: $(e.currentTarget).attr('attr-mac')},
                url: that.settings.API + '/restapi.php/seasons/reconnect-sta',
                success: function(data) {
                    if (data === 'true') {
                        $(e.currentTarget).text('Reconnected');
                    } else {
                        $(e.currentTarget).text('Reconnecting failed');
                    }
                }
            });
        });

        $('.reconnectall').click(function(e){
            $(e.currentTarget).text('Reconnecting...')
            $.ajax({
                type: 'POST',
                url: that.settings.API + '/restapi.php/seasons/reconnectall-sta',
                success: function(data) {
                    if (data === 'true') {
                        $(e.currentTarget).text('All Reconnected');
                    } else {
                        $(e.currentTarget).text('Reconnecting failed');
                    }
                }
            });
        });

        //authorize, unauthorize
        $('.authorize, .unauthorize').click(function(e){
            ($(e.currentTarget).attr('class') === 'authorize') ? $(e.currentTarget).html('Authorizing...') : $(e.currentTarget).html('Unauthorizing...');
            $.ajax({
                type: 'POST',
                data: {mac: $(e.currentTarget).attr('attr-mac'), minutes: $(e.currentTarget).attr('attr-minutes')},
                url: ($(e.currentTarget).attr('class') === 'authorize') ? that.settings.API + '/restapi.php/seasons/authorize-guest' : that.settings.API + '/restapi.php/seasons/unauthorize-guest',
                success: function(data) {
                    if (data === 'true') {
                        if ($(e.currentTarget).attr('class') === 'authorize') {
                            $(e.currentTarget).removeClass('authorize').addClass('unauthorize');
                            $(e.currentTarget).html('Unauthorize');
                        } else {
                            $(e.currentTarget).removeClass('unauthorize').addClass('authorize');
                            $(e.currentTarget).html('Authorize');
                        }
                    }
                }
            });
        });

        //authorize, unauthorize
        $('.authorizeall, .unauthorizeall').click(function(e){
            ($(e.currentTarget).attr('class') === 'authorizeall') ? $(e.currentTarget).html('Authorizing...') : $(e.currentTarget).html('Unauthorizing...');
            $.ajax({
                type: 'POST',
                data: {minutes: $(e.currentTarget).attr('attr-minutes')},
                url: ($(e.currentTarget).attr('class') === 'authorizeall') ? that.settings.API + '/restapi.php/seasons/authorize_all' : that.settings.API + '/restapi.php/seasons/unauthorize_all',
                success: function(data) {
                    if (data === 'true') {
                        ($(e.currentTarget).attr('class') === 'authorizeall') ? $(e.currentTarget).html('Authorized') : $(e.currentTarget).html('Unauthorized');
                    } else {
                        ($(e.currentTarget).attr('class') === 'authorizeall') ? $(e.currentTarget).html('Authorize Failed') : $(e.currentTarget).html('Unauthorized Failed');
                    }
                }
            });
        });

    };

    return SiteDashboard;
})();

var SiteStats = (function() {

    var SiteStats = function() {

        if (!(~document.URL.indexOf('page=stats'))) {
            return;
        }

        this.settings = new Settings();
        this.accessPoints = [];
        this.usersPerDay = [];
        this.usersPerPeriod = [];

        this.getStatus();
    };

    SiteStats.prototype.getStatus = function() {

        // TODO: kijken hoeveel procent, hoelang ze aanwezig blijven. 0 - 1 uur, 0 - 2 uur, ... over een week en over een maand // STATISTIEK

        var that = this, dag, maand;
        this.site = $('#current-website').val();

        var t = new Date();
        t.setMonth( t.getMonth() + 1 );

        if (t.getDate().toString().length === 1) {
            dag = '0' + t.getDate();
        } else {
            dag = t.getDate();
        }

        if (t.getMonth().toString().length === 1) {
            maand = '0' + t.getMonth();
        } else {
            maand = t.getMonth();
        }

        //datepicker instellen
        var datetimepicker = $('#datetimepicker').datepicker({
            format: "dd-mm-yyyy",
            autoclose:true,
            endDate: '1d',
            todayBtn:'linked'
        });

        var datetimepickerstart = $('#datetimepickerstart').datepicker({
            format: "dd-mm-yyyy",
            autoclose:true,
            rangeSelect: true,
            endDate: '1d',
            beforeShow: customRange,
            onSelect: customRange
        });

        var datetimepickerend = $('#datetimepickerend').datepicker({
            format: "dd-mm-yyyy",
            autoclose:true,
            rangeSelect: true,
            endDate: '1d',
            beforeShow: customRange,
            onSelect: customRange
        });

        var monthsArr = ['january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'novemberg', 'december'];

        //huidige dag instellen
        $('.form-control').val(dag + '-' + maand + '-' + t.getFullYear());

        //datetimepickerend.on('show', function(evt){
        var dateend, datestart;

        datetimepickerstart.off('changeDate').on('changeDate', function(e){
            datestart = $('#datetimepickerstart').datepicker('getDate').getTime();
            if (typeof dateend !== 'undefined' && typeof datestart !== 'undefined') {
                that.setDaySettings(datestart, dateend);
            }
        });

        datetimepickerend.off('changeDate').on('changeDate', function(e){
            dateend = $('#datetimepickerend').datepicker('getDate').getTime();

            if (typeof dateend !== 'undefined' && typeof datestart !== 'undefined') {
                that.setDaySettings(datestart, dateend);
            }
        });

        //})

        //toon datepicker event
        datetimepicker.on('show', function(evt){
            datetimepicker.off('changeDate').on('changeDate', function(e){
                that.setDaySettings(new Date(e.date).getTime());
            });
            that.setSelectMonthBtn(monthsArr, datetimepicker);
        }).on('changeMonth', function(evt){
            setTimeout(function(){
                that.setSelectMonthBtn(monthsArr, datetimepicker);
            }, 0);
        });

        function customRange(input) {
            if (input.id == "datetimepickerstart") {

                $("#ui-datepicker-div td").die();

                if (selectedDate != null) {
                    $('#datetimepickerend').datepicker('option', 'minDate', selectedDate).datepicker('refresh');
                }
            }
            if (input.id == "datetimepickerend") {

                $("#ui-datepicker-div td").live({
                    mouseenter: function() {
                        $(this).prevAll("td:not(.ui-datepicker-unselectable)").addClass("highlight");
                    },
                    mouseleave: function() {
                        $("#ui-datepicker-div td").removeClass("highlight");
                    }
                });

                var selectedDate = $("#datetimepickerstart").datepicker("getDate");
                if (selectedDate != null) {
                    $('#datetimepickerend').datepicker('option', 'minDate', selectedDate).datepicker('refresh');
                }
            }
        }

        //set huidige dag
        this.setDaySettings(new Date(maand + '/' + dag + '/' + t.getFullYear()).getTime());

        //set huidige dag
        $.ajax({
            type: 'GET',
            url: this.settings.API + '/restapi.php/' + this.site + '/stats/device',
            success: function(data) {
                that.devices = JSON.parse(data);
                var googleArray = [['name', 'users']];

                //LOOPEN DOOR ALLE DATA EN TOEVOEGEN AAN ARRAY
                that.devices.forEach(function(device){
                    that.accessPoints.push({name: device.name, status: ((device['ng-state']) ? device['ng-state'] : device['na-state']), number_users: device.num_sta, ip: device.config_network.ip});

                    googleArray.push([device.name, device.num_sta]);
                });

                // GOOGLE CHART MAKEN VAN PERCENTAGE GEBRUIKERS PER ACCESS POINT
                google.load("visualization", "1", {
                    packages:["corechart"],
                    callback: function(){
                        var data = google.visualization.arrayToDataTable(googleArray);

                        var options = {
                            title: 'Percentage of users per access point',
                            backgroundColor: 'transparent',
                            animation: {
                                duration: 5,
                                easing: 'in'
                            }
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                        chart.draw(data, options);
                    }
                });

            }
        });
    };

    SiteStats.prototype.setDaySettings = function(start, end){
            //SET DAY STATISTICS
            var that = this;

            //als de einddatum niet wordt meegegeven dan nemen we de laatste 30 dagen.
            //anders eigen periode
            if (typeof end === 'undefined') {
                $.ajax({
                    type: 'POST',
                    data: {timestamp: start},
                    url: this.settings.API + '/restapi.php/' + this.site + '/stats/get_hourly',
                    success: function(data) {
                        that.usersPerDay = JSON.parse(data);
                        if (that.usersPerDay.length === 0) return;
                        that.fillStats();
                    }
                });
            } else {
                start = start / 1000;
                end = end / 1000;

                $.ajax({
                    type: 'POST',
                    data: {start: start, einde: end},
                    url: this.settings.API + '/restapi.php/seasons/stats/session',
                    success: function(data) {
                        console.log(data);
                        that.usersPerPeriod = JSON.parse(data);
                        if (that.usersPerPeriod.length === 0) return;
                        that.periodStats();
                    }
                });
            }
    }

    SiteStats.prototype.setSelectMonthBtn = function(monthsArr, datetimepicker){

        //selecteer hele maand
        var that = this;
        $('.datepicker tfoot').find('th').html('<button>select month</button>').off('click').on('click', function(e){
            //SET MONTH STATISTICS
            var maand = $($('.datepicker-switch')[0]).text().split(' ')[0];
            var jaar = $($('.datepicker-switch')[0]).text().split(' ')[1];
            var maandcijfer = monthsArr.indexOf(maand.toLowerCase());

            var dateformat = ((maandcijfer.toString().length === 1) ? '0' + (maandcijfer+1) : (maandcijfer+1)) + '/01/' + jaar;
            var monthtimestamp = new Date(dateformat).getTime();

            setTimeout(function(){
                $('.form-control').val(maand);

                $.ajax({
                    type: 'POST',
                    data: {timestamp: monthtimestamp},
                    url: that.settings.API + '/restapi.php/' + that.site + '/stats/get_daily',
                    success: function(data) {

                        that.usersPerDay = JSON.parse(data);
                        if (that.usersPerDay.length === 0) return;
                        that.fillStats();
                    }
                });

            }, 0);

            datetimepicker.off('changeDate');
        });

    }

    SiteStats.prototype.periodStats = function(){

        var that = this;
        // STATS INVULLEN
        var aantalUsers = 0;
        var duration = [];
        duration['sec_60'] = [];
        duration['sec_120'] = [];
        duration['sec_300'] = [];
        duration['sec_900'] = [];
        duration['sec_1800'] = [];
        duration['sec_3600'] = [];
        duration['sec_7200'] = [];
        duration['sec_hoger'] = [];

        that.usersPerPeriod.forEach(function(user){
            aantalUsers++;

            if (user.duration < 60) {
                duration['sec_60'].push(user.duration);
            } else if (user.duration < 120) {
                duration['sec_120'].push(user.duration);
            } else if (user.duration < 300) {
                duration['sec_300'].push(user.duration);
            } else if (user.duration < 900) {
                duration['sec_900'].push(user.duration);
            } else if (user.duration < 1800) {
                duration['sec_1800'].push(user.duration);
            } else if (user.duration < 3600) {
                duration['sec_3600'].push(user.duration);
            } else if (user.duration < 7200) {
                duration['sec_7200'].push(user.duration);
            } else {
                duration['sec_hoger'].push(user.duration);
            }
        });

        for (var key in duration) {
            console.log(duration[key].length);
        }
    }

    SiteStats.prototype.fillStats = function(){

        var that = this;
        // STATS INVULLEN
        var visualArray = [['Datum', 'Aantal gebruikers']];
        var aantalNul = 0;

        that.usersPerDay.sort(function(a, b){return a.time-b.time});

        that.usersPerDay.forEach(function(number){

                var t = new Date( number.time );
                if (that.usersPerDay.length < 26){
                    var uur = t.getHours();

                    if (uur === 0) aantalNul++;

                    if (aantalNul === 2) return;

                    visualArray.push([uur + 'u', number.num_sta]);
                } else {

                    t.setMonth( t.getMonth() + 1 );

                    var dag, maand;

                    if (t.getDate().toString().length === 1) {
                        dag = '0' + t.getDate();
                    } else {
                        dag = t.getDate();
                    }

                    if (t.getMonth().toString().length === 1) {
                        maand = '0' + t.getMonth();
                    } else {
                        maand = t.getMonth();
                    }

                    visualArray.push([dag + '/' + maand, number.num_sta]);
                }
        });

        google.load("visualization", "1", {
            packages:["corechart"],
            callback: function(){
                var data = google.visualization.arrayToDataTable(visualArray);

                var options = {
                    title: 'Gebruik laatste 30 dagen',
                    backgroundColor: '#e4e7ea',
                    animation: {
                        duration: 5,
                        easing: 'in'
                    },
                    chartArea: {left:130,top:0,width:'80%',height:'70%'},
                    legend: {position: 'none'},
                    vAxis: {
                        title: 'Gebruikers',
                        showTextEvery: 50
                    },
                    hAxis: {
                        title: ''
                    }
                };

                var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

                chart.draw(data, options);
            }
        });
    }

    return SiteStats;
})();

/* globals Settings:true */

var Tables = (function () {

    var Tables = function () {
        _.bindAll(this);

        this.bind();
        this.settings = new Settings();
    };

    Tables.prototype.bind = function() {
        $('.delete-row').click(this.confirmDelete);
    };

    Tables.prototype.confirmDelete = function(event) {
        if(confirm('Bent u zeker?')) {
            event.preventDefault();

            var link = this.settings.URI + '/' + $(event.currentTarget).attr('href') + '&ajax=true';
            var siteid = $(event.currentTarget).attr('data-deleteInSidebar');

            $.ajax({
                type: 'GET',
                url: link,
                success: function(data) {
                    if(data === 'true') {
                        $("#ajaxDelete").removeClass('hide');
                        $(event.currentTarget).parent().parent().remove();


                        // Remove site we just deleted from the sidebar too
                        if(typeof siteid !== 'undefined' && siteid !== false) {
                            // siteid is not undefined or false (so it's a site and not a user)
                            var $sidebarLinks = $('.site-view-link');
                            // loop over all sidebar links
                            for(var i = 0; i < $sidebarLinks.length; i++) {
                                // Check if data-siteid attribute and siteid match
                                if($($sidebarLinks[i]).attr('data-siteid') === siteid) {
                                    // They match, delete the node from the menu
                                    $($($sidebarLinks[i])).remove();
                                }
                            }
                        }
                    }
                }
            });
        }
    };

    return Tables;
})();

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

/* globals Forms:true, Tables:true, SiteDashboard:true */
var forms = new Forms();
var tables = new Tables();
var SiteDashboard = new SiteDashboard();
var SiteStats = new SiteStats();
var Applicaties = new Applicaties();
//var Agenda = new Agenda();

})();