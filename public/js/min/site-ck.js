$(document).ready(function(){var o="http://analytics-app.net/";$(".follow-btn").each(function(){var o=$(this),l=o.data("following");l?o.addClass("btn-primary").text("Following"):o.text("Follow")}),$(".follow-btn").click(function(){var l=$(this),a=l.data("following");a?l.removeClass("btn-primary").text("Follow").data("following",0):l.addClass("btn-primary").text("Following").data("following",1);var t=l.data("user-id");$.post(o+"apiproxy/follow/"+t,{followingState:a}).fail(function(){a?l.removeClass("btn-primary").text("Follow").data("following",0):l.addClass("btn-primary").text("Following").data("following",1),alert("There was an error making the follow/unfollow request.")})})});