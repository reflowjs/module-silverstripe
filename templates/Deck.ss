<!doctype html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Reflow</title>
        <link rel="stylesheet" href="reflow/public/thirdparty/reflow-silverstripe/highlight.8.3.css">
        <link rel="stylesheet" href="reflow/public/thirdparty/reflow-silverstripe/silverstripe.css">
    </head>
    <body>
        <div class="wrapper" data-behaviors="scale" data-scale-target=".content" data-scale-delay="250" data-scale-modifier="{$Scale}">
            <div class="content"></div>
        </div>
        <script src="reflow/public/thirdparty/reflow-silverstripe/jquery.1.9.1.js"></script>
        <script src="reflow/public/thirdparty/reflow-silverstripe/jquery.easing.1.3.js"></script>
        <script src="reflow/public/thirdparty/reflow-silverstripe/jquery.transit.0.9.9.js"></script>
        <script src="reflow/public/thirdparty/reflow-silverstripe/jquery.debounce.1.1.js"></script>
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
                "preloadBefore" : {$PreloadBefore},
                "preloadAfter"  : {$PreloadAfter},
                "unloadBefore"  : {$UnloadBefore},
                "unloadAfter"   : {$UnloadAfter},
                "behaviors"     : {
                    "animation-fade-in"   : new Reflow.Behavior.Animation.FadeIn(),
                    "animation-fade-out"  : new Reflow.Behavior.Animation.FadeOut(),
                    "animation-slide-in"  : new Reflow.Behavior.Animation.SlideIn(),
                    "animation-slide-out" : new Reflow.Behavior.Animation.SlideOut(),
                    "background"          : new Reflow.Behavior.Background(),
                    "center"              : new Reflow.Behavior.Center(),
                    "fit"                 : new Reflow.Behavior.Fit(),
                    "fixed-image"         : new Reflow.Behavior.FixedImage(),
                    "highlight"           : new Reflow.Behavior.Highlight(),
                    "scale"               : new Reflow.Behavior.Scale(),
                    "swipe"               : new Reflow.Behavior.Swipe()
                },
                "pages" : [
                    <% loop $Slides %>
                        new Reflow.Page({
                            "resources" : {
                                "html"  : <% if $Markup %>"{$Link}Markup",<% else %>null,<% end_if %>
                                "css"   : <% if $Styles %>"{$Link}Styles",<% else %>null,<% end_if %>
                                "js"    : <% if $Scripts %>"{$Link}Scripts",<% else %>null,<% end_if %>
                                "trail" : null
                            },
                            "hash" : "{$URLSegment}"
                        }),
                    <% end_loop %>
                ],
                "defaults" : {
                    "animations" : {
                        "show" : "{$Animation}",
                        "hide" : "{$Animation}"
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

            $(window).bind("keydown", $.throttle(250, function(e) {
                if (e.which == 37) {
                    Reflow.getInstance().previous();
                }

                if (e.which == 39) {
                    Reflow.getInstance().next();
                }
            }));

            $(window).bind("keydown", function(e) {
                if (e.altKey && e.which === 70) {
                    var element = document.querySelector(".content");

                    if (element.requestFullscreen) {
                      element.requestFullscreen();
                    } else if (element.msRequestFullscreen) {
                      element.msRequestFullscreen();
                    } else if (element.mozRequestFullScreen) {
                      element.mozRequestFullScreen();
                    } else if (element.webkitRequestFullscreen) {
                      element.webkitRequestFullscreen();
                    }
                }
            });
        </script>
    </body>
</html>
