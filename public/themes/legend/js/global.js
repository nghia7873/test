jQuery(document).ready(function () {
    var fixKeyword = function (str) {
        str = str.toLowerCase();
        str = str.replace(/(<([^>]+)>)/gi, "");
        str = str.replace(/[`~!@#$%^&*()_|\=?;:'",.<>\{\}\[\]\\\/]/gi, "");
        str = str.split(" ").join("+");
        return str;
    };
    jQuery("a#swstyle").click(function () {
        var $Data = jQuery(this);
        var $Style = $Data.attr("data");
        jQuery.post(
            AjaxURL,
            {
                CStyle: 1,
                Style: $Style,
            },
            function (data) {
                if (data == 1) {
                    location.reload();
                } else {
                    fx.messageBox(
                        "ข้อผิดพลาด",
                        "ระบบแสดงว่าคุณกำลังพยายามแทรกแซงระบบของเราโดยเจตนา!"
                    );
                }
            }
        );
    });
});

function SetupSlimscroll(e) {
    $(e).slimScroll({
        height: "250px",
    });
}
