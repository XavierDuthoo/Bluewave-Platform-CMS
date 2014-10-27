/* globals Settings:true, Document:true, Agenda:true, Paragraph:true */

var SiteDashboard = (function () {

    var SiteDashboard = function () {
        _.bindAll(this);
        if($('.site-dashboard').length === 0) {
            return false;
        }

        this.settings = new Settings();
        this.siteid = $('.site-dashboard').attr('data-siteid');

        this.bind();
        this.initAll();
    };

    SiteDashboard.prototype.bind = function() {
        $('.ajax-update-simple').submit(this.updateContent);
        $('.add-block li a').click(this.getNewBlockHtml);
    };

    SiteDashboard.prototype.initAll = function() {
        var that = this;
        var contentblocks = $('.content-blocks > div');

        $.each(contentblocks, function(index, value) {
            switch($(value).attr('data-context')) {
                case 'paragraph':
                    var newParagraph = new Paragraph($(value), that.siteid);
                    break;

                case 'document':
                    var newDocument = new Document($(value), that.siteid);
                    break;

                case 'agenda':
                    var newAgenda = new Agenda($(value), that.siteid);
                    break;
            }
        });
    };

    // Update for simple ajax calls (timer, ssid, landing page & popup)
    SiteDashboard.prototype.updateContent = function(event) {
        event.preventDefault();
        var $submit = $(event.currentTarget).find('input[type=submit]');
            $submit.attr('disabled', 'disabled');
            $submit.val('Bezig...');

        var sortable = $(event.currentTarget).attr('data-sortable'),
            context = $(event.currentTarget).attr('data-context'),
            siteid = $(event.currentTarget).attr('data-siteid'),
            id = $(event.currentTarget).attr('data-id');

        var args = {};
            args.context  = context;
            args.sortable = sortable;
            args.site_id  = siteid;
            args.id       = id;

        switch(context) {
            case 'ssid':
                args.ssid = $('#ssid').val();
                break;

            case 'landing':
                args.url = $('#landing').val();
                break;

            case 'popup':
                args.active = $('#popup-checkbox').is(':checked');
                args.content = $('#popup-content').val();
                break;

            case 'timer':
                args.active = $('#timer-checkbox').is(':checked');
                args.seconds = $('#timer-seconds').val();
                break;

        }

        $.ajax({
            type: 'POST',
            url: this.settings.URI + '/index.php?page=sites&action=edit&id=' + siteid + '&ajax=true',
            data: args,
            success: function(data) {
                $('.saved-wrapper').html(data);
                setTimeout(function() {
                    $submit.removeAttr('disabled');
                    $submit.val('Opslaan');
                }, 300);
            }
        });

    };

    SiteDashboard.prototype.getNewBlockHtml = function(event) {
        event.preventDefault();
        var context = $(event.currentTarget).attr('data-context');
        var that = this;

        $.ajax({
            type: 'GET',
            url: this.settings.URI + '/index.php?page=sites&action=getBlock&context=' + context,
            success: function(data) {
                // append block to blocks
                $('.content-blocks').append(data);
                // set siteid as data attr
                $('.last-added').attr('data-siteid', that.siteid);

                if(context === 'paragraph') {
                    var newParagraph = new Paragraph($('.last-added'), that.siteid);
                } else if(context === 'document') {
                    var newDocument = new Document($('.last-added'), that.siteid);
                } else if(context === 'agenda') {
                    var newAgenda = new Agenda($('.last-added'), that.siteid);
                }

                // add event listeners
                $('.last-added').find('a.minimize').click(that.minimizePanel);
                $('.last-added').find('a.panel-close').click(that.deleteBlock);
                $('.last-added').find('form.ajax-update-complex').submit(that.updateContentComplex);

                // remove class last added so we only have it once
                $('.last-added').removeClass('last-added');
            }
        });
    };

    SiteDashboard.prototype.minimizePanel = function(event) {
        var t = $(event.currentTarget);
        var p = t.closest('.panel');
        if(!$(event.currentTarget).hasClass('maximize')) {
            p.find('.panel-body, .panel-footer').slideUp(200);
            t.addClass('maximize');
            t.html('&plus;');
        } else {
            p.find('.panel-body, .panel-footer').slideDown(200);
            t.removeClass('maximize');
            t.html('&minus;');
        }

        return false;
    };

    SiteDashboard.prototype.deleteBlock = function(event) {
        $(event.currentTarget).closest('.panel').fadeOut(200);
        var that = this;

        var isNew = $(event.currentTarget).parents('.panel').attr('data-new');
        if(isNew !== 'true') {
            // Delete block op server
            var args = {};
            args.context = $(event.currentTarget).parents('.panel').attr('data-context');
            args.id = $(event.currentTarget).parents('.panel').attr('data-blockid');

            $.ajax({
                type: 'POST',
                url: that.settings.URI + '/index.php?page=sites&action=deleteBlock',
                data: args,
                success: function(data) {
                    console.log(data);
                }
            });
        }

        return false;
    };

    return SiteDashboard;
})();