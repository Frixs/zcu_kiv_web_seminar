$(document).ready(function () {
    $(".server-group-settings-wrapper .user-wrapper select").on("change", function () {
        let uID = $(this).attr('data-uid');
        let gID = $(this).val();
        let serverid = $(this).parent().attr('data-serverid');
        let nameWrapper = $(this).parent().parent().children('.name-box');

        $.ajax({
            type: "POST",
            url: root + "/__request/__ajax/set-server-user-group",
            data: "uid=" + uID + "&gid=" + gID + "&serverid=" + serverid,
            success: function (data) {
                let response;
                let langUpdated = lang('server.settings.snack_group_updated');

                response = data;

                if (response != "true") {
                    return;
                }

                nameWrapper.append(" \
                    <span class=\"on-group-update-snack gc-hidden\" data-uid=\"" + uID + "\">" + langUpdated + "</span> \
                ");
                nameWrapper.children('.on-group-update-snack[data-uid=' + uID + ']').fadeIn("fast");

                setTimeout(function () {
                    nameWrapper.children('.on-group-update-snack[data-uid=' + uID + ']').fadeOut("fast", function () {
                        $(this).remove();
                    });
                }, 3000);
            }
        });
    });
});