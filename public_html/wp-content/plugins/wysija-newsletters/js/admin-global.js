(function(e){"use strict";e(document).ready(function(){e(".mp-select-sort").on({sort:function(){e(this).each(function(t,n){var r=e(n),o=r.children("option");o.detach().sort(function(t,n){return 0===parseInt(e(t).data("sort"),10)||0===parseInt(e(n).data("skip"),10)?0:"NA"===t.innerHTML?1:"NA"===n.innerHTML?-1:t.innerHTML.toLowerCase()>n.innerHTML.toLowerCase()?1:-1}).appendTo(r),o.filter("[selected]").eq(0).prop("selected",!0)})}}).trigger("sort")})})(jQuery.noConflict(),_);