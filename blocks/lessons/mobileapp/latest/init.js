(function(t) {

    t.CoreUtilsProvider.blockLessonsUtils = {

        /**
         * Check whether the infitite scroll element is visible, and load more messages if so.
         *
         * @param {Object} infiniteScroll Object with enable and complete methods, can be real or faked.
         * @param {Function} loadMoreMessages Function to call to load and display more messages.
         */
        loadMessagesIfShortPage: function(infiniteScroll, loadMoreLessons) {
            if (!infiniteScroll.disabled) {
                var infiniteRect = document.getElementById('block_lessons_infinite_load_lessons').getBoundingClientRect();
                if (infiniteRect.top >= 0 && infiniteRect.bottom <= window.innerHeight) {
                    if (infiniteScroll.hasOwnProperty('spinner')) {
                        window.clearTimeout(infiniteScroll.dismissTimeout);
                        infiniteScroll.spinner.style.display = 'block';
                    }
                    loadMoreLessons(infiniteScroll);
                }
            }
        },

        /**
         * Trigger loading or extra messages to fill the page if required, and set the content height.
         *
         * @param {CoreCompileFakeHTMLComponent} page Must define a loadMoreLessons function.
         */
        pageInit: function(page) {
            window.setTimeout(function() {
                var fakeInfiniteScroll = {
                    disabled: false,
                    dismissTimeout: null,
                    spinner: document.getElementById('block_lessons_infinite_load_lessons').querySelector('.infinite-loading'),
                    enable: function(state) {
                        this.disabled = !state;
                    },
                    complete: function() {
                        this.dismissTimeout = window.setTimeout(function() {
                            fakeInfiniteScroll.spinner.style.display = '';
                        }, 1000);
                    }
                };
                t.CoreUtilsProvider.blockLessonsUtils.loadLessonsIfShortPage(fakeInfiniteScroll, page.loadMoreLessons);
            }, 0);
        }
    };


    class AddonBlockLessonsLinkToPageHandler extends t.CoreContentLinksHandlerBase {
        constructor() {
            super();
            this.pattern = new RegExp("\/blocks\/lessons\/courses\\.php");
            this.name = "AddonBlockLessonsLinkToPageHandler";
            this.priority = 0;
        }
        getActions(siteIds, url, params) {
            var action = {
                action: function(siteId) {
                    t.CoreSitesProvider.getSite(siteId).then(function(site) {
                                var pageParams = {

                                };
                                t.CoreNavigatorService.navigateToSitePath('siteplugins/content/block_lessons/viewlessons/0');
                    });
                }
            };
            return [action];
        }
    };
    t.CoreContentLinksDelegate.registerHandler(new AddonBlockLessonsLinkToPageHandler());
})(this);
