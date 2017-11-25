/**
 * Recognize what input is.
 * @return
 */
function recognizeAjaxInput(data) {
    let isResponseJSON = false;

    try {
        jQuery.parseJSON(data);
        return 'JSON';
    } catch (e) {
        if (data.startsWith('!!!')) {
            return 'ERROR';
        } else if (data.startsWith('$$$')) {
            return 'LANG';
        }

        return 'TEXT';
    }
}

/**
 * Get Language text.
 * @param {*}  path
 * @return {*} string
 */
function lang(string) {
    var output = "PLACEHOLDER";

    $.ajax({
        type: "POST",
        url: "__request/__ajax/get-lang",
        async: false,
        data: "string=" + string,
        success: function (data) {
            if (recognizeAjaxInput(data) == 'LANG') {
                output = data.substring(3);
            }
        }
    });

    return output;
}