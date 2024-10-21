// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * mobile number verification.
 *
 * @module     local_users/verification
 * @copyright  Dipanshu Kasera <kasera.dipanshu@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/ajax', 'core/str', 'core/notification'], function($, Ajax, Str, Notification) {

    Str.get_strings([
        {
            key: 'success:successfullysentotp',
            component: 'local_users'
        },
        {
            key: 'success:successfullyresentotp',
            component: 'local_users'
        },
        {
            key: 'error:otpexpired',
            component: 'local_users'
        },
        {
            key: 'error:phone1',
            component: 'local_users'
        },
        {
            key: 'error:country',
            component: 'local_users'
        },
        {
            key: 'error:somethingwentwrong',
            component: 'local_users'
        },
        {
            key: 'error:lockedusers',
            component: 'local_users'
        },
        {
            key: 'error:lockedsms',
            component: 'local_users'
        }
        ,
        {
            key: 'error:code',
            component: 'local_users'
        }
    ]).then(function(s) {

        $("#id_sentotplink").on('click', function() {
            var phone1 = $("#id_phone1").val();
            var country = $("#id_country").val();
            var flag = 1;
            var otpcode = 0;

            if (phone1 == '' || country == 0) {
                $('#id_error_country').html(s[3]).show();
            } else {
                $('#id_error_country').hide();
                sendotp(phone1, country, otpcode, flag);
            }

            var duration = $('input[name="duration"]').val();
            var sec = duration * 1000;

            setTimeout(function() {
                $("#resendotplink").show();
            }, sec);
            $("#resendotplink").hide();
        });

        $("#resendotplink").on('click', function() {
            var phone1 = $("#id_phone1").val();
            var country = $("#id_country").val();
            var flag = 2;
            var otpcode = 0;

            if (phone1 == '' || country == 0) {
                $('#id_error_country').html(s[3]).show();
            } else {
                $('#id_error_country').hide();
                sendotp(phone1, country, otpcode, flag);
            }

            $('#id_error_otpcode').hide();

            var duration = $('input[name="duration"]').val();
            var sec = duration * 1000;

            $("#resendotplink").hide();
            setTimeout(function() {
                $("#resendotplink").show();
            }, sec);
        });

        $("#id_validateotp").on('click', function() {
            var phone1 = $("#id_phone1").val();
            var country = $("#id_country").val();
            var otpcode = $("#id_otpcode").val();
            var flag = 3;
            if (otpcode == '') {
                $('#id_error_otpcode').html(s[8]).show();
            } else {
                sendotp(phone1, country, otpcode, flag);
                $('#id_error_otpcode').hide();
            }
            $('#id_error_country').hide();
        });

        /**
         * sendotp user
         *
         * @param {object} phone1
         * @param {object} country
         * @param {object} otpcode
         * @param {object} flag
         */
        function sendotp(phone1, country, otpcode, flag) {
            if (phone1 != '' && (country != '' || country != 0)) {
                var params = {};
                params.phone1 = phone1;
                params.country = country;
                params.otpcode = otpcode;
                params.flag = flag;
                var promise = Ajax.call([{
                    methodname: 'local_users_validateuserdetails',
                    args: params
                }]);
                promise[0].done(function(res) {
                    if (res.res == 1) {
                        if (flag == 1) {
                            $('#id_error_country').html(s[0]);
                        } else if (flag == 2) {
                            $('#id_error_country').html(s[1]);
                        }

                        $(".signup_form").hide();
                        $("#id_sentotplink").hide();
                        $("#id_error_country").hide();
                        $("#loginpassdiv").show();

                        $("#id_country").prop('disabled', true);
                        $("#id_phone1").prop('disabled', true);
                        $('#id_error_country').show();
                        $('#id_error_country').css('color', 'green');
                    } else if (res.res == 3) {
                        var str = Str.get_string('error:lockoutnotification', 'local_users', res.id);
                        str.then(data => {
                            $('#id_error_otpcode').html(data).show();
                        });
                        if (res.id == 0) {
                            $('#id_validateotp').hide();
                            $('#resendotplink').hide();
                            Notification.addNotification({
                                type: 'error',
                                message: s[6],
                            });
                        }
                    } else if (res.res == 4) {
                        $('#id_error_otpcode').html(s[2]).show();
                    } else if (res.res == 5) {
                        window.location.href = M.cfg.wwwroot + '/local/users/confirm.php?r=0';
                    }  else if (res.res == 6) {
                        $('#loginpassdiv').hide();
                        Notification.addNotification({
                            type: 'error',
                            message: s[7],
                        });
                        $("#id_sentotplink").hide();
                        $("#id_country").prop('disabled', true);
                        $("#id_phone1").prop('disabled', true);
                    } else {
                        window.location.href = M.cfg.wwwroot + '/local/users/registration.php?status=1&vid='+res.id;
                    }
                }).fail(function() {
                    // do something with the exception
                });
            } else {
                if (phone1 == '' || country == 0) {
                    $('#id_error_country').html(s[3]).show();
                } else {
                    $('#id_error_country').hide();
                }
            }
        }
    });
});
