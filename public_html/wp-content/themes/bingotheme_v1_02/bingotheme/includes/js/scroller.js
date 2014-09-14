function moveScroller() {
    var move = function() {
        var st = $(window).scrollTop();
        var ot = $("#navigation-anchor").offset().top;
        var s = $("#navigation");
        if(st > ot) {
            s.css({
                position: "fixed",
                top: "0",
		left:"0",
		right:"0",
            });
        } else {
            if(st <= ot) {
                s.css({
                    position: "relative",
                    top: ""
			
                });
            }
        }
    };
    $(window).scroll(move);
    move();
}