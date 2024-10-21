var that = this;
this.loadMoreError = false;
(function(t) {
   var nextPage = 1; 
   var search = '';

    t.loadMoreCourses = function(infiniteComplete) {
                var args = {
                    search: search,
                    page: nextPage                    
                }
                t.CoreSitesProvider.getCurrentSite().read('local_resources_get_courses', args).then(function(response) {
                    t.CONTENT_OTHERDATA.hasMoreCourses = response.hasMoreCourses;
                    t.CONTENT_OTHERDATA.courses = t.CoreUtilsProvider.uniqueArray(t.CONTENT_OTHERDATA.courses
                            .concat(response.courses), 'id');
                    nextPage++;
                    t.CONTENT_OTHERDATA.canLoadMore = response.total > t.CONTENT_OTHERDATA.courses.length ? true : false;
                }).catch((error) => {
                    t.CoreDomUtils.showErrorModalDefault(error, 'addon.blog.errorloadentries', true);
                    t.loadMoreError = true; // Set to prevent infinite calls with infinite-loading.
                }).finally(() => {
                    infiniteComplete && infiniteComplete();
                });
    };

    t.search = function(query) {
        search = query;
        nextPage = 0;
        var args = {
            search: search,
            page: nextPage
        }
        t.CoreSitesProvider.getCurrentSite().read('local_resources_get_courses', args).then(function(response) {
            t.CONTENT_OTHERDATA.hasMoreCourses = response.hasMoreCourses;
            if (response.courses && response.courses.length) {
                t.CONTENT_OTHERDATA.courses = t.CoreUtilsProvider.uniqueArray(t.CONTENT_OTHERDATA.courses
                    .concat(response.courses), 'id');
            } else {
                t.CONTENT_OTHERDATA.courses = response.courses;
            }
            console.table(response);
            console.table(t.CONTENT_OTHERDATA);
            t.CONTENT_OTHERDATA.canLoadMore = response.total > t.CONTENT_OTHERDATA.courses.length ? true : false;
        }).catch((error) => {
            t.CoreDomUtils.showErrorModalDefault(error, 'addon.blog.errorloadentries', true);
            // t.loadMoreError = true; // Set to prevent infinite calls with infinite-loading.
        }).finally(() => {
            // infiniteComplete && infiniteComplete();
        });
    };

})(this);
