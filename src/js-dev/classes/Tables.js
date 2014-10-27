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