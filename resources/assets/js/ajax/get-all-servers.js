$(document).ready(function () {
    $(".search-input-top-servers").keyup(function () {
        let resultWrapperID = $(this).attr("data-search");
        let value = $(this).val();

        if (value.length == 0)
            return;

        $.ajax({
            type: "POST",
            url: "_request/ajax/get-all-servers",
            data: "name=" + $(this).val(),
            success: function (data) {
                let response;
                let isResponseJSON = false;
                let stringBG;
                let stringAccessTypeBox;
                let i;

                try {
                    response = jQuery.parseJSON(data);
                    isResponseJSON = true;
                } catch (e) {
                    response = data;
                }

                if (isResponseJSON) {
                    for (i = 0; i < response.size(); i++) {
                        response[i]['user_count'];


                        if (response[i]['has_background_box']) {
                            stringBG = "images/structure/bg_server_default.jpg";
                        } else {
                            stringBG = "images/structure/bg_server_default.jpg";
                        }

                        switch (response[i]['access_type']) {
                            case 0:
                                stringAccessTypeBox = '<a href="#" class= "btn btn-primary"><i class="fa fa-unlock" aria-hidden="true"></i> <TEXT></a>';
                                break;
                            case 1:
                                stringAccessTypeBox = '<a href="#" class="btn btn-primary"><i class="fa fa-unlock-alt" aria-hidden="true"></i> <TEXT></a>';
                                break;
                            case 2:
                                stringAccessTypeBox = '<i class="fa fa-lock" aria-hidden="true"></i> <TEXT>';
                                break;
                        }

                        $(resultWrapperID).append(' \
                            <div class="server-box" data-server-id="'+ response[i]['id'] + '"> \
                                '+ stringBG + ' \
                                <div class="title">'+ response[i]['name'] + '</div> \
                                <div class="info small"> \
                                    <div class="left"> \
                                        '+ stringAccessTypeBox +' \
                                    </div> \
                                    <div class="right"> \
                                        <i class="fa fa-users" aria-hidden="true"></i> <TEXT> \
                                    </div> \
                                    <div class="gc-cleaner"></div> \
                                </div> \
                            </div> \
                    ');
            }
        } else {
                console.log(xxx().html());
            }

                function xxx() {
                <span>
                    <p>Hey!</p>
                </span>
            }
            }
        });
    });
});