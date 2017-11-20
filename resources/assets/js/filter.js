$(document).ready(function () {
    $(".filter-input").keyup(function () {
        let filterWrapperID = $(this).attr("data-filter");
        let value           = $(".filter-input").val().toLowerCase();

        $(filterWrapperID).find("*[data-filter-searchable]").each(function () {
            if ($(this).text().toLowerCase().indexOf(value) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});