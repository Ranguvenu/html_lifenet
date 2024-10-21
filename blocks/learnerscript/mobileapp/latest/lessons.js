(function(t) {

    /**
     * @var {number} The number of the next page of lessons to load.
     */
    var nextPage = 1;

    /**
     * Load the next page of news items for the infinite scroll.
     *
     * @param {Object} infiniteScroll
     */
    t.loadMoreLessons = function(infiniteScroll) {
        var args = {
            page: nextPage,
        }
        t.CoreSitesProvider.getCurrentSite().read('block_lessons_get_lessons', args).then(function(response) {
            t.CONTENT_OTHERDATA.hasMoreLessons = response.hasMoreLessons;
            response.lessons.forEach(function(lesson) {
                // Message and title can duplicate but id is not, so need to check message items are duplicated or not.
                var exist = t.CONTENT_OTHERDATA.lessons.some(function(l) {
                    if (l.id === lesson.id) {
                        return true;
                    } else {
                        return false;
                    }
                });
                if(!exist) {
                    t.CONTENT_OTHERDATA.lessons.push(lesson);
                }
            });
            nextPage++;
            if (t.CONTENT_OTHERDATA.hasMoreLessons) {
                window.setTimeout(function() {
                    t.CoreUtilsProvider.blockLessonsUtils.loadLessonsIfShortPage(infiniteScroll, t.loadMoreLessons);
                }, 0);
            } else {
                infiniteScroll.target.disabled = true;
            }
        }).finally(function() {
            infiniteScroll.target.complete();
        });
    };

    // window.initPageURL('lessons', t.CONTENT_OTHERDATA.pageurl);
    t.openInBrowser = window.openInBrowser;


    t.CoreUtilsProvider.blockLessonsUtils.pageInit(t);
})(this);
