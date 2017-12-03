$(document).ready(function () {
    $(".search-input-top-servers").on("change paste keyup", function () {
        let resultWrapperID = $(this).attr("data-search");
        let value = $(this).val();

        if (value.length < 3) {
            $(resultWrapperID).empty();
            return;
        }

        $.ajax({
            type: "POST",
            url: root + "/__request/__ajax/get-all-servers-without-yours",
            data: "name=" + $(this).val(),
            success: function (data) {
                let response;
                let stringBG;
                let stringAccessTypeBox;
                let i;
                let langJoin = lang('structures.community-box.join');
                let langSendRequest = lang('structures.community-box.send_request');
                let langPrivate = lang('structures.community-box.private');
                let langNoResults = lang('structures.community-box.no_results');

                let input = recognizeAjaxInput(data);
                switch (input) {
                    case 'JSON':
                        response = jQuery.parseJSON(data);
                        break;
                    case 'TEXT':
                        response = data;
                        break;
                }

                $(resultWrapperID).empty();

                if (input == 'JSON') {
                    for (i = 0; i < response.length; i++) {
                        response[i]['user_count'];


                        if (parseInt(response[i]['has_background_placeholder']) == 1) {
                            stringBG = "storage/server/" + response[i]['id'] + "_background_placeholder.jpg";
                        } else {
                            stringBG = "images/structure/server_background_placeholder_default.jpg";
                        }

                        switch (parseInt(response[i]['access_type'])) {
                            case 0:
                                stringAccessTypeBox = '<a href="server/join/server:'+ response[i]['id'] +'" class= "btn btn-primary"><i class="fa fa-unlock" aria-hidden="true"></i> ' + langJoin + '</a>';
                                break;
                            case 1:
                                stringAccessTypeBox = '<a href="server/send-request/server:'+ response[i]['id'] +'" class="btn btn-primary"><i class="fa fa-unlock-alt" aria-hidden="true"></i> ' + langSendRequest + '</a>';
                                break;
                            case 2:
                                stringAccessTypeBox = '<i class="fa fa-lock" aria-hidden="true"></i> ' + langPrivate;
                                break;
                        }

                        $(resultWrapperID).append(' \
                            <div class="server-box" data-server-id="'+ response[i]['id'] + '"> \
                                <img src="'+ stringBG + '" alt="" draggable="false" tabindex="-1"> \
                                <div class="title">'+ response[i]['name'] + '</div> \
                                <div class="info small"> \
                                    <div class="left"> \
                                        '+ stringAccessTypeBox + ' \
                                    </div> \
                                    <div class="right"> \
                                        <i class="fa fa-users" aria-hidden="true"></i> '+ response[i]['user_count'] + ' \
                                    </div> \
                                    <div class="gc-cleaner"></div> \
                                </div> \
                            </div> \
                        ');
                    }

                    if (i == 0) $(resultWrapperID).append('<i class="text-center small gc-block-center">'+ langNoResults +'</i>');

                } else {
                    $(resultWrapperID).append('<i class="text-center small gc-block-center">'+ langNoResults +'</i>');
                }

                $(resultWrapperID).append('<hr class="color">');
            }
        });
    });
});