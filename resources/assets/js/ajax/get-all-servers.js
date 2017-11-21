$(document).ready(function () {
    $(".search-input-top-servers").keyup(function () {
        let resultWrapper = $(this).attr("data-search");
        let value = $(this).val();

        if (value.length == 0)
            return;

        $.ajax({
            type: "POST",
            url: "_request/ajax/get-all-servers",
            data: "name=" + $(this).val(),
            success: function (data) {
                console.log(data);
            }
        });
    });
});