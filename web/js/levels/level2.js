
var boxopened = "";
var imgopened = "";
var count = 0;
var found = 0;
var obj = 20;
var level = 2;
var timer = setInterval(timer, 1000);
var win = false;
var sound = new Audio();


function playSound(url){
    sound.pause();
    sound.currentTime = 0;
    sound.src = url;
    sound.play();
}

function randomFromTo(from, to) {
    return Math.floor(Math.random() * (to - from + 1) + from);
}


function shuffle() {
    var children = $("#boxcard").children();
    var child = $("#boxcard div:first-child");
    var array_img = new Array();
    for (i = 0; i < children.length; i++) {
        array_img[i] = $("#" + child.attr("id") + " img").attr("src");
        child = child.next();
    }



    var child = $("#boxcard div:first-child");

    for (z = 0; z < children.length; z++) {
        randIndex = randomFromTo(0, array_img.length - 1);
        // set new image
        $("#" + child.attr("id") + " img").attr("src", array_img[randIndex]);
        array_img.splice(randIndex, 1);
        child = child.next();
    }
}

function timer() {

    obj--;
    document.getElementById('timer_inp').innerHTML = obj;
    if (obj === 0) {
        win = false;
        var timeToInsert = 20 - obj;
        clearInterval(timer);
        alert('Игра закончена');
        $.ajax({
            type: 'POST',
            url: Routing.generate('record_new'),
            dataType: "json",
            data: JSON.stringify({clicks: count, gametime: timeToInsert,
                level: level, win: win}),
            success: function (data) {
                $('ul').html(data);
                alert(data);
            }

        });
        obj = 20;
        window.location.href = "http://127.0.0.1:8000/logout";
        
    }
}

function stopTime() {
    clearInterval(timer);

}


function resetGame() {
    shuffle();
    $("img").hide();
    $("img").removeClass("opacity");
    count = 0;
    $("#msg").remove();
    $("#count").html("" + count);
    boxopened = "";
    imgopened = "";
    found = 0;
    obj = 20;
    timer();
    return false;

}

$(document).ready(function () {
    $("img").hide();
    $("#boxcard div").click(openCard);
    shuffle();
    function openCard() {

        id = $(this).attr("id");
        if ($("#" + id + " img").is(":hidden")) {
            $("#boxcard div").unbind("click", openCard);
            $("#" + id + " img").show();
            $("#" + id + " img").css("animation", "s_im 0.7s normal");
            if (imgopened == "") {
                boxopened = id;
                imgopened = $("#" + id + " img").attr("src");
                setTimeout(function () {
                    $("#boxcard div").bind("click", openCard)
                }, 1);
            } else {
                currentopened = $("#" + id + " img").attr("src");
                if (imgopened != currentopened) {
                    // close again

                    setTimeout(function () {


                        $("#" + id + " img").css("animation", "h_im 0.7s normal");
                        $("#" + boxopened + " img").css("animation", "h_im 0.7s normal");
                    }, 400);

                    setTimeout(function () {


                        $("#" + boxopened + " img").hide();
                        $("#" + id + " img").hide();
                        imgopened = "";
                        boxopened = "";
                    }, 1100);




                } else {
                    // found
                    playSound('http://mirmuz.com/usr/audio/457/611/dsdsd_by_tox1ctox_at_mirmuz_20170420_1492698036.mp3');
                    $("#" + id + " img").addClass("opacity");
                    $("#" + boxopened + " img").addClass("opacity");
                    found++;
                    boxopened = "";
                    imgopened = "";
                }

                setTimeout(function () {
                    $("#boxcard div").bind("click", openCard)
                }, 1100);
            }


            count++;
            $("#count").html("" + count);

            if (found == 3) {
               window.location.href="http://127.0.0.1:8000/level/2#completed";
                stopTime();
                var timeToInsert = 20 - obj;
                win = true;

                $.ajax({
                    type: 'POST',
                    url: Routing.generate('record_new'),
                    dataType: "json",
                    data: JSON.stringify({clicks: count, gametime: timeToInsert,
                        level: level, win: win}),
                    success: function (data) {
                        $('ul').html(data);
                        alert(data);
                    }

                });

            }
        }
    }
});
