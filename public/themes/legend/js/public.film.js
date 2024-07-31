jQuery(document).ready(function () {
    jQuery("#list_actor_carousel").carouFredSel({
        auto: false,
        prev: "#prevActor",
        next: "#nextActor",
    });
    if (
        typeof window.screen.width == "undefined" ||
        window.screen.width > 480
    ) {
        jQuery(".movie-meta-info").slimScroll({
            height: "277px",
            railVisible: true,
            alwaysVisible: true,
        });
    }
    var score_current = jQuery("#score_current").val();
    var hint_current = jQuery("#hint_current").val();
    jQuery("#hint").html(hint_current);
    jQuery("#score").html(score_current + " POINTS");

    function scorehint(score) {
        var text = "";
        if (score == "1") {
            text = "แย่มาก";
        }
        if (score == "2") {
            text = "แย่";
        }
        if (score == "3") {
            text = "ไม่ดี";
        }
        if (score == "4") {
            text = "ไม่ค่อยดี";
        }
        if (score == "5") {
            text = "ธรรมดา";
        }
        if (score == "6") {
            text = "ดูได้";
        }
        if (score == "7") {
            text = "ดูเหมือนจะดี";
        }
        if (score == "8") {
            text = "ดี";
        }
        if (score == "9") {
            text = "ดีมาก";
        }
        if (score == "10") {
            text = "ดีเยี่ยม";
        }
        return text;
    }
    jQuery("#star").raty({
        half: false,
        score: function () {
            return jQuery(this).attr("data-score");
        },
        mouseover: function (score, evt) {
            jQuery("#score").html(score + " ĐIỂM");
            jQuery("#hint").html(scorehint(score));
        },
        mouseout: function (score, evt) {
            var score_current = jQuery("#score_current").val();
            var hint_current = jQuery("#hint_current").val();
            jQuery("#hint").html(hint_current);
            jQuery("#score").html(score_current + " ĐIỂM");
        },
        click: function (score, evt) {
            jQuery
                .ajax({
                    url: URL_POST_RATING,
                    type: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    data: JSON.stringify({
                        rating: score,
                    }),
                })
                .done(function (data) {
                    fx.displayMessage("ขอบคุณที่ให้คะแนนภาพยนตร์เรื่องนี้!");
                });
        },
    });
    jQuery("#star").css("width", "200px");
    jQuery(".box-rating #hint").css("font-size", "12px");
});
