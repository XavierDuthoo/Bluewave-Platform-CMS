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