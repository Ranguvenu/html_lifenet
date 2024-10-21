/**
 * Add a create new group modal to the page.
 *
 * @module     auth_otp/otp
 * @class      NewInstitute
 * @package    auth_otp
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery', 'core/str', 'core/modal_factory', 'core/modal_events', 'core/fragment', 'core/ajax', 'core/yui', 'core/notification'],
        function($, Str, ModalFactory, ModalEvents, Fragment, Ajax, Y,Notification) {

    /**
     * Constructor
     *
     * @param {String} selector used to find triggers for the new group modal.
     * @param {int} contextid
     *
     * Each call to init gets it's own instance of this class.
     */
    var NewReferalcode = function(args) {
        this.contextid = args.contextid;

        var self = this;
        self.init(args.selector);
    };

    /**
     * @var {Modal} modal
     * @private
     */
    NewReferalcode.prototype.modal = null;

    /**
     * @var {int} contextid
     * @private
     */
    NewReferalcode.prototype.contextid = -1;

    /**
     * Initialise the class.
     *
     * @param {String} selector used to find triggers for the new group modal.
     * @private
     * @return {Promise}
     */
    NewReferalcode.prototype.init = function(args) {
        var self = this;

        var head =  {key:'referalcodeheader', component:'auth_otp'};

        customstrings = Str.get_strings([head,{
                key: 'referalcodeheader',
                component: 'auth_otp'
            },
            {
                key: 'apply',
                component: 'auth_otp'
            },
            {
                key: 'skip',
                component: 'auth_otp'
            }]);

        return customstrings.then(function(strings) {
                return ModalFactory.create({
                    type: ModalFactory.types.DEFAULT,
                    title: strings[0],
                    body: self.getBody(),
                    footer: self.getFooter(strings),
                });
            }.bind(this)).then(function(modal) {
            // Keep a reference to the modal.
            this.modal = modal;

            this.modal.getFooter().find('[data-action="save"]').on('click', this.submitForm.bind(this));
            this.modal.getFooter().find('[data-action="cancel"]').on('click', function() {
                params = {};
                params.contextid = 1;
                params.status = 1;
                var promise = Ajax.call([{
                    methodname: 'auth_otp_alter_popup_status',
                    args: params
                }]);
                promise[0].done(function(resp) {
                    window.location.href =  window.location.href;
                }).fail(function(ex) {
                     console.log(ex);
                });

            });
            // added for custom navigating from the top lists ends here.
            this.modal.getRoot().on('submit', 'form', function(form) {
                self.submitFormAjax(form, self.args);
            });
            this.modal.show();
            this.modal.getRoot().animate({"right":"0%"}, 500);
            $(".close").click(function(){
                params = {};
                params.contextid = 1;
                params.status = 1;
                var promise = Ajax.call([{
                    methodname: 'auth_otp_alter_popup_status',
                    args: params
                }]);
                promise[0].done(function(resp) {
                    window.location.href =  window.location.href;
                }).fail(function(ex) {
                     console.log(ex);
                });
            });
            return this.modal;
        }.bind(this));

    };

    /**
     * @method getBody
     * @private
     * @return {Promise}
     */
    NewReferalcode.prototype.getBody = function(formdata) {
        if (typeof formdata === "undefined") {
            formdata = {};
        }
        // alert(formdata);
        // Get the content of the modal.
        var params = {jsonformdata: JSON.stringify(formdata)};
        return Fragment.loadFragment('auth_otp', 'referalcode_form', this.contextid, params);
    };
    /**
     * @method getFooter
     * @private
     * @return {Promise}
     */
    NewReferalcode.prototype.getFooter = function(customstrings) {

        var footer = '';

        var style = 'style="display:none;"';

        footer += '<button type="button" class="btn btn-primary" data-action="save">'+customstrings[2]+'</button>&nbsp;';

        footer += '<button type="button" class="btn btn-secondary" data-action="cancel">'+customstrings[3]+'</button>';
        return footer;

    };

    /**
     * @method handleFormSubmissionResponse
     * @private
     * @return {Promise}
     */
    NewReferalcode.prototype.handleFormSubmissionResponse = function() {
        this.modal.hide();
        // We could trigger an event instead.
        // Yuk.
        Y.use('moodle-core-formchangechecker', function() {
            M.core_formchangechecker.reset_form_dirty_state();
        });
        Notification.addNotification({
            message: 'Referral code successfully applied.',
            type: 'success'
        });
    };

    /**
     * @method handleFormSubmissionFailure
     * @private
     * @return {Promise}
     */
    NewReferalcode.prototype.handleFormSubmissionFailure = function(data) {
        // Oh noes! Epic fail :(
        // Ah wait - this is normal. We need to re-display the form with errors!
        this.modal.setBody(this.getBody(data));
    };

    /**
     * Private method
     *
     * @method submitFormAjax
     * @private
     * @param {Event} e Form submission event.
     */
    NewReferalcode.prototype.submitFormAjax = function(e) {
        // We don't want to do a real form submission.
        e.preventDefault();

        // Convert all the form elements values to a serialised string.
        var formData = this.modal.getRoot().find('form').serialize();
        // alert(this.contextid);
        // Now we can continue...
        Ajax.call([{
            methodname: 'auth_otp_submit_referalcode',
            args: {contextid: this.contextid, jsonformdata: JSON.stringify(formData)},
            done: this.handleFormSubmissionResponse.bind(this, formData),
            fail: this.handleFormSubmissionFailure.bind(this, formData)
        }]);
    };

    /**
     * This triggers a form submission, so that any mform elements can do final tricks before the form submission is processed.
     *
     * @method submitForm
     * @param {Event} e Form submission event.
     * @private
     */
    NewReferalcode.prototype.submitForm = function(e) {
        e.preventDefault();
        var self = this;
        self.modal.getRoot().find('form').submit();
    };

    return /** @alias module:auth_otp/newotp */ {
        // Public variables and functions.
        /**
         * Attach event listeners to initialise this module.
         *
         * @method init
         * @param {string} selector The CSS selector used to find nodes that will trigger this module.
         * @param {int} contextid The contextid for the course.
         * @return {Promise}
         */
        init: function(args) {

            return new NewReferalcode(args);
        }
    };
});