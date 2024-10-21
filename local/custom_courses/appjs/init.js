
(function (t) {
    function AddonPluginCustomCoursesDeleteLinkToPageHandler() {
        this.pattern = new RegExp('\/local\/custom_courses\/delete\\.php');
        this.name = "AddonPluginCustomCoursesDeleteLinkToPageHandler";
        this.priority = 0;
    }
    AddonPluginCustomCoursesDeleteLinkToPageHandler.prototype = Object.create(t.CoreContentLinksHandlerBase.prototype);
    AddonPluginCustomCoursesDeleteLinkToPageHandler.prototype.constructor = AddonPluginCustomCoursesDeleteLinkToPageHandler;
    AddonPluginCustomCoursesDeleteLinkToPageHandler.prototype.getActions = function(siteIds, url, params) {
        var action = {
            action: function(siteId, navCtrl) {
                t.CoreSitesProvider.getSite(siteId).then(function(site) {
                    const page = `courses/storage`;
                    var pageParams = {
                    };

                    t.CoreNavigatorService.navigateToSitePath(page);
                });
            }
        };
        return [action];
    };
    t.CoreContentLinksDelegate.registerHandler(new AddonPluginCustomCoursesDeleteLinkToPageHandler());
})(this);
