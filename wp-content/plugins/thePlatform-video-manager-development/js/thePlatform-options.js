/* thePlatform Video Manager Wordpress Plugin
 Copyright (C) 2013-2015 thePlatform, LLC

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License along
 with this program; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA. */

(function ($) {

    function configure_metadata_fields() {
        var $optionsPageName = $('input[name=option_page]');
        var $table = $optionsPageName.siblings('table');
        $table.css('display', 'none');
        $table.after('<div id="drag-columns"></div>');
        var $dragColumnsContainer = $('#drag-columns');
        var $sortableFields = $table.find('.sortableField');
        var cols = {
            'write': [],
            'read': [],
            'hide': []
        };
        $sortableFields.each(function () {
            var name = $(this).parent().siblings('th').html();
            var value = $(this).val();
            if (!value.length)
                value = 'hide';
            cols[value].push({
                id: $(this).attr('id'),
                name: name,
                userfield: $(this).data('userfield')
            });
        });

        var colSource = $("#column-template").html();
        var colTemplate = _.template(colSource);
        for (var colName in cols) {
            var column = colTemplate({
                colName: colName
            });

            $dragColumnsContainer.append(column);
            var $col = $('ul[data-col=' + colName + ']');
            for (var i in cols[colName]) {
                var field = cols[colName][i];
                var $li = $('<li />');
                $li.data('id', field.id);
                $li.data('userfield', field.userfield);
                if (field.id == 'title') {
                  $li.addClass('unsortable');
                }
                $li.text(field.name);
                $col.append($li);
            }
        }
        $clear = $('<div />');
        $clear.addClass('clear');
        $dragColumnsContainer.append($clear);

        $(".sortable").sortable({
            items: "li:not(.unsortable)",
            connectWith: ".sortable",
            receive: function (e, ui) {
                var receiver = $(e.target).data('col');
                var itemId = $(ui.item).data('id');

                if (receiver == "write" && ($(ui.item).data('userfield') === true || $(ui.item).data('id') == 'id')) {
                    $(ui.sender).sortable('cancel');
                } else {
                    var $selectField = $('select[name="' + $optionsPageName.val() + '[' + itemId + ']"]');
                    $selectField.find('option:selected').attr('selected', false);
                    $selectField.find('option[value="' + receiver + '"]').attr('selected', true);
                }
            }
        }).disableSelection();
    }

    function capitalize(s) {
        return s[0].toUpperCase() + s.slice(1);
    }

    function authenticate() {
        var usr = $("#mpx_username").val();
        var pwd = $("#mpx_password").val();

        if (usr.indexOf('mpx/') === -1) {
            usr = 'mpx/' + usr;
        }
        var hash = base64Encode(usr + ":" + pwd);

        var data = {
            action: 'verify_account',
            _wpnonce: tp_options_local.tp_nonce.verify_account,
            auth_hash: hash
        };

        $.post(tp_options_local.ajaxurl, data, function (response) {
            var $verificationImage = $("#verification_image");
            if ($verificationImage.length > 0) {
                $verificationImage.remove();
            }

            if (response.success) {
                $('#verify-account-dashicon').removeClass('dashicons-no').addClass('dashicons-yes');
                $("#mpx_password").css('border-color', '');
                // Show the account field and set the values from the ajax resposne
                var accounts = response.data;
                $accountIdField = $('#mpx_account_id');

                if ($accountIdField.find('option').length > 0) {
                    return;
                }

                for (var i = 0; i < accounts.length; i++) {
                    var option = document.createElement('option');
                    option.setAttribute('data-pid', accounts[i].pid);
                    option.text = accounts[i].title;
                    option.value = accounts[i].id;
                    $accountIdField.append(option);
                }

                $accountIdField.parent().parent().show();

                $('#mpx_account_pid').attr('value', $accountIdField.find('option:selected').data('pid'));
            } else {
                $('#verify-account-dashicon').removeClass('dashicons-yes').addClass('dashicons-no');
                $("#mpx_password").attr('placeholder', 'Invalid Username or Password').css('border-color', 'red').val('');
            }
        });
    }

    function base64Encode(data) {
        var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
        var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
            ac = 0,
            enc = "",
            tmp_arr = [];

        if (!data) {
            return data;
        }

        do {
            o1 = data.charCodeAt(i++);
            o2 = data.charCodeAt(i++);
            o3 = data.charCodeAt(i++);

            bits = o1 << 16 | o2 << 8 | o3;

            h1 = bits >> 18 & 0x3f;
            h2 = bits >> 12 & 0x3f;
            h3 = bits >> 6 & 0x3f;
            h4 = bits & 0x3f;

            tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
        } while (i < data.length);

        enc = tmp_arr.join('');

        var r = data.length % 3;

        return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
    }

    function configure_pid_fields() {
        if ($('#mpx_account_id option:selected').length !== 0) {

            $('#mpx_account_pid').attr('value', $('#mpx_account_id option:selected').data('pid'));
        } else
            $('#mpx_account_id').parent().parent().hide();

        if ($('#default_player_name option:selected').length !== 0) {
            $('#default_player_pid').attr('value', $('#default_player_name option:selected').data('pid'));
        } else
            $('#default_player_name').parent().parent().hide();

        if ($('#mpx_server_id option:selected').length === 0) {
            $('#mpx_server_id').parent().parent().hide();
        }

        //Set up the PID for the mpx account on change in the Settings page
        $('#mpx_account_id').change(function () {
            $('#mpx_account_pid').attr('value', $('#mpx_account_id option:selected').data('pid'));
        });

        //Set up the PID for the Player on change in the Settings page
        $('#default_player_name').change(function () {
            $('#default_player_pid').attr('value', $('#default_player_name option:selected').data('pid'));
        });
    }

    _.template.formatColName = function (colName) {
        return colName[0].toUpperCase() + colName.slice(1);
    };

    $(document).ready(function () {
        var TP_PAGE_KEY = $('#TP_PAGE_KEY').text();

        if (TP_PAGE_KEY == 'TP_FIELDS') {
            configure_metadata_fields();
        }

        if (TP_PAGE_KEY == 'TP_PREFERENCES') {
            configure_pid_fields();

            //Hide hidden setting fields
            $('.hidden-option').each(function () {
                $(this).parent().parent().hide();
            });

            // Validate account information in plugin settings fields by logging in to mpx
            $("#verify-account-button").click(authenticate);

            var $passwordField = $('#mpx_password');

            if ($passwordField) {
                if ($passwordField.val() === '' && $('#mpx_username').val() !== '') {
                    $passwordField.attr('placeholder', 'Invalid Username or Password').css('border-color', 'red');
                }
            }
        }
    });
})(jQuery);
