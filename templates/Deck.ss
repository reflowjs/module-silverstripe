<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Reflow</title>
        <link rel="stylesheet" href="reflow/public/thirdparty/reflow-silverstripe/highlight.8.3.css">
        <link rel="stylesheet" href="reflow/public/thirdparty/reflow-silverstripe/silverstripe.css">
    </head>
    <body>
        <div class="wrapper" data-behaviors="scale" data-scale-target=".content" data-scale-delay="250">
            <div class="content"></div>
        </div>
        <script src="reflow/public/thirdparty/reflow-silverstripe/jquery.1.9.1.js"></script>
        <script src="reflow/public/thirdparty/reflow-silverstripe/jquery.easing.1.3.js"></script>
        <script src="reflow/public/thirdparty/reflow-silverstripe/jquery.transit.0.9.9.js"></script>
        <script src="reflow/public/thirdparty/reflow-silverstripe/jgestures.0.9.js"></script>
        <script src="reflow/public/thirdparty/reflow-silverstripe/highlight.8.3.js"></script>
        <script src="reflow/public/thirdparty/reflow-silverstripe/jquery.fontspy.3.0.0.js"></script>
        <script src="reflow/public/thirdparty/reflow-core/dist/reflow.js"></script>
        <script>
            fontSpy("latoregular", {
                "glyphs"  : "a",
                "success" : function() {
                    if (Reflow.getInstance()) {
                        Reflow.getInstance().updateBehaviors();
                    }
                }
            });

            fontSpy("droid_sans_monoregular", {
                "glyphs"  : "a",
                "success" : function() {
                    if (Reflow.getInstance()) {
                        Reflow.getInstance().updateBehaviors();
                    }
                }
            });

            var reflow = new Reflow({
                "target"        : ".content",
                "adapter"       : new Reflow.Adapter.jQuery(),
                "preloadBefore" : 3,
                "preloadAfter"  : 3,
                "unloadBefore"  : 3,
                "unloadAfter"   : 3,
                "behaviors"     : {
                    "animation-fade-in"   : new Reflow.Behavior.Animation.FadeIn(),
                    "animation-fade-out"  : new Reflow.Behavior.Animation.FadeOut(),
                    "animation-slide-in"  : new Reflow.Behavior.Animation.SlideIn(),
                    "animation-slide-out" : new Reflow.Behavior.Animation.SlideOut(),
                    "background"          : new Reflow.Behavior.Background(),
                    "center"              : new Reflow.Behavior.Center(),
                    "fit"                 : new Reflow.Behavior.Fit(),
                    "highlight"           : new Reflow.Behavior.Highlight(),
                    "scale"               : new Reflow.Behavior.Scale(),
                    "swipe"               : new Reflow.Behavior.Swipe()
                },
                "pages" : [
                    <% loop $Slides %>
                        new Reflow.Page({
                            "resources" : {
                                <% if $Markup %>
                                    "html" : "{$Link}Markup",
                                <% end_if %>
                                <% if $Styles %>
                                    "css"  : "{$Link}Styles",
                                <% end_if %>
                                <% if $Scripts %>
                                    "js"   : "{$Link}Scripts",
                                <% end_if %>

                                "noop" : "noop"
                            },
                            "hash" : "$URLSegment"
                        }),
                    <% end_loop %>
                ],
                "defaults" : {
                    "animations" : {
                        "show" : "none",
                        "hide" : "none"
                    }
                },
                "animations" : {
                    "fade"       : new Reflow.Animation.Fade(),
                    "horizontal" : new Reflow.Animation.Horizontal(),
                    "vertical"   : new Reflow.Animation.Vertical(),
                    "none"       : new Reflow.Animation.None()
                }
            });

            $(".content").bind("swipeone", function(e, modified) {
                if (modified.direction.startX == -1) {
                    Reflow.getInstance().next();
                }

                if (modified.direction.startY == -1) {
                    Reflow.getInstance().next();
                }

                if (modified.direction.startX == 1) {
                    Reflow.getInstance().previous();
                }

                if (modified.direction.startY == 1) {
                    Reflow.getInstance().previous();
                }
            });

            $(window).bind("keydown", function(e) {
                if (e.which == 37) {
                    Reflow.getInstance().previous();
                }

                if (e.which == 39) {
                    Reflow.getInstance().next();
                }
            });
        </script>
    </body>
</html>
