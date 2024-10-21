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
 * Controls the message popover in the nav bar.
 *
 * @module     core_message/message_attachments
 * @copyright  Moodle India <ryan@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(
    [
        'jquery',
        'core/str'
    ],
    function (
        $,
        Str
    ) {
        var init = function () {
            document.getElementById('photosvideos').addEventListener('click', openDialog);
            document.getElementById('document').addEventListener('click', openDialogDoc);
            function openDialog() {
                document.getElementById('msgattachments').click();
                $('#errormsg').text('');
            }
            function openDialogDoc() {
                document.getElementById('msgdocuments').click();
                $('#errormsg').text('');
            }
            $("#msgattachments").change(function () {
                $('#mainmediadropdownmenu').removeClass('show');
                $('#mediadropdownmenu').removeClass('show');
                readURL(this);
            });
            $("#msgdocuments").change(function () {
                $('#mainmediadropdownmenu').removeClass('show');
                $('#mediadropdownmenu').removeClass('show');
                readURL(this);
            });
            function readURL(input) {
                $('[data-region="confirm-dialogue-container"]').removeClass('hidden');
                if (input.files && input.files[0]) {
                    console.log(input.files[0]);
                    var type;
                    var filetype = input.files[0].type;
                    if(filetype.indexOf("image") !== -1){
                        type = 'image.png'
                    }else if(filetype.indexOf("video") !== -1){
                        type = 'video.png'
                    }else{
                        type = 'document.png'
                    }
                    return Str.get_strings([
                        {
                            key: 'confirmation',
                            component: 'core_message',
                            param: null
                        },
                        {
                            key: 'title',
                            component: 'core_message',
                            param: input.files[0].name
                        },
                        {
                            key: 'filetype',
                            component: 'core_message',
                            param: input.files[0].type
                        }
                    ])
                        .then(function (strings) {
                            var fileype = strings[2];
                            var title = strings[1];
                            var heading = strings[0];
                            $('[data-region="dialogue-text"]').html(heading);
                            var reader = new FileReader();
                            $('[data-region="title-text"]').html(title);
                            var imgurl = $('#pictureurl').val();
                            $('#attachmentpreview').attr('src', imgurl+type);
                            $('#attachmentpreview').show();
                            reader.readAsDataURL(input.files[0]);
                        });
                }
            }
            $('[data-action="okay-confirm"]').click(function () {
                attachmentpreview();
                $('[data-region="title-text"]').html('');
                $('[data-region="type-text"]').html('');
                $('[data-action="send-message"]').trigger('click');
                $('#attachmentpreview').hide();
                $('#attachmentpreview').attr('src', '');
            });
            $('[data-action="cancel-confirm"]').click(function () {
                attachmentpreview();
                $("#msgattachments").val('');
                $("#msgdocuments").val('');
                $('#attachmentpreview').hide();
                $('#attachmentpreview').attr('src', '');
            });
            function attachmentpreview() {
                $('[data-region="confirm-dialogue-container"]').addClass('hidden');
                $('#attachmentpreview').addClass('hidden');
            }
            $('[data-region="send-message-txt"]').keypress(function () {
                $('#errormsg').text('');
            });
        };
        return {
            init: init,
        };
    });
