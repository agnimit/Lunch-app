
            $("#stars").raty();
            function first(){
                if(/*typeof*/ $.cookie('userid') == undefined) {
                    alert("Please login again");
                    window.location("http://agarwal.mobileplusinc.com");
                }
                $("#reviews-table > tbody").empty();
                $.ajax({
                    url: '/api/lunches.php?date=' + window.today,
                    type: 'get',
                    dataType: 'json',
                })
                .done(function(data) {
                    if(typeof data[0] != "undefined") {
                        if($.cookie('usertype') == '1') {
                            console.log("delete lunch");
                            $("#deleteLunch").show();
                        } 

                        $("#filled").val("");
                        $("#cater").html(data[0].caterer);
                        $("#desc").html("<p> " + data[0].description) + "</p>";
                        $("#error-lunch").hide();
                        $("#success").show();
                        window.lunchid = data[0].id;
                        window.lunchname = data[0].name;
                        second();
                    } else {
                        $("#success").hide();
                        $("#table-data").hide();
                        $('#write-review').hide();
                        $("#error-lunch").show();
                        $('#deleteLunch').hide();
                        $('#deleteReview').hide();
                        if($.cookie('usertype') === "1") {
                            $("#temp").empty();
                            $("#error-lunch").html('<div class="alert alert-danger"> Write a Lunch here </div>')
                            $("#writeLunch").show();
                        }
                    }
                })
            }
            function second(){
                $.ajax({
                    url: '/api/reviews.php?lunch_id=' + window.lunchid,
                    type: 'get',
                    dataType: 'json',
                })
                .done(function(data) {
                    $("#table-data").hide();
                    var check= true;
                    if ( data[0] != "undefined") {
                        console.log("second gets here");
                        var avg = 0;
                        for(var i =0; i < data.length; i++) {
                            avg += parseInt(data[i].rating,10);
                            console.log(avg);
                        }
                        avg /= data.length;
                        $("#avgRating").html("Average Rating: <div id='showAvg'></div>");
                        $("#showAvg").raty({readOnly: true, score: avg});
                        drawTable(data);
                        for (var i = 0; i < data.length; i++) {
                            if(data[i].user_id == $.cookie('userid')) {
                                check = false;
                            }
                        }
                        if(check) {
                            console.log("third");
                            $("#write-review").show();
                            $("#write-review").trigger("reset");
                        } else {
                            $('#deleteReview').show();
                        }
                        $("#table-data").show();
                        
                    }
                    else {
                        console.log("second");
                        $("#write-review").show();
                        $("#write-review").trigger("reset");
                        $("#table-data").hide();
                        $('#deleteReview').hide();
                    }
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                });

                function drawTable(data) {
                    for (var i = 0; i < data.length; i++) {
                        drawRow(data[i], i);
                    }
                    $("#reviews-table").append('</tbody>');
                }

                function drawRow(rowData, i) {
                    var row = $("<tr />")
                    $("#reviews-table").append(row); //this will append tr element to table... keep its reference for a while since we will add cels into it
                    row.append($("<td>" + rowData.Name + "</td>"));
                    row.append($("<td><div id=appendStars" + i + "></div>"));
                    $('#appendStars' + i).raty({readOnly: true, score: rowData.rating });
                    //row.append($("<td>" + rowData.rating + "</td>"));
                    row.append($("<td>" + rowData.comment + "</td>"));
                }
            }
            $("#submit1").click(function(event) {
                window.select =  $('#stars').raty('score');
                firstpass();
            });
            $("#datepicker").datepicker({
                onSelect: function(dateText, inst) {
                    $("#table-data").hide();
                    $("#writeLunch").hide();
                    $("#write-review").hide();
                    $("#deleteReview").hide();
                    $("#writeLunchName").val("");
                    $("#lunchDesc").val("");
                    $("#writeCatererName").val(""); 
                    var getdate = $("#datepicker").val().split('/');
                    window.today = getdate[2] + "-" + getdate[0] + "-" + getdate[1]; 
                    first();
                }
            });
            function firstpass() {
                if(/*typeof*/ $.cookie('userid') != undefined) {
                    $.ajax({
                        url: '/api/reviews.php?',
                        data: {"rating" : window.select, "comment" : $("#filled").val(), "lunch_id" : window.lunchid, "user_id" : $.cookie('userid')},
                        type: 'post',
                    }) .done(function(data) {
                        alert("Your review has been submitted.");
                        $("#write-review").hide();
                        first();
                    }) 
                }
                else {
                    alert("Please login again");
                    window.location= "http://agarwal.mobileplusinc.com";
                }
            }

            $("#calendarclick").click(function(event) {
                if($.cookie('userid') == 'undefined') {
                    alert("Please login again");
                    window.location = "http://agarwal.mobileplusinc.com";
                }
                $("#showCalendar").show();
                $("#showReviews").hide();
                $("#showLunches").hide();
                $("#writeLunch").hide();
                $("#deleteLunch").hide();
                $('#deleteReview').show();
                first();
            });
            $("#getReviews").click(function(event) {
                $("#showCalendar").hide();
                $("#showReviews").show();
                $("#showLunches").hide();
                $("#writeLunch").hide();
                $("#deleteLunch").hide();
                $('#deleteReview').hide();
                $('#reviewsAll-table > tbody').empty();
                $.ajax({
                    url: '/api/reviews.php?all=true',
                    type: 'get',
                    dataType: 'json',
                })
                .done(function(data) {
                    for (var i = 0; i < data.length; i++) {
                        var row = $("<tr />")
                        $("#reviewsAll-table").append(row); //this will append tr element to table... keep its reference for a while since we will add cels into it
                        row.append($("<td>" + data[i].Name + "</td>"));
                        row.append($("<td>" + data[i].rating + "</td>"));
                        row.append($("<td>" + data[i].comment + "</td>"));
                        row.append($("<td>" + data[i].caterer + "</td>"));
                        row.append($("<td>" + data[i].date + "</td>"));
                    }
                    $("#reviews-table").append('</tbody>');
                })
            });
            $("#getLunches").click(function(event) {
                $("#showCalendar").hide();
                $("#showReviews").hide();
                $("#showLunches").show();
                $("#writeLunch").hide();
                $("#deleteLunch").hide();
                $('#deleteReview').hide();
                $('#lunchesAll-table > tbody').empty();
                $.ajax({
                    url: '/api/lunches.php?all=true',
                    type: 'get',
                    dataType: 'json',
                })
                .done(function(data) {
                    for (var i = 0; i < data.length; i++) {
                        var row = $("<tr />")
                        $("#lunchesAll-table").append(row); //this will append tr element to table... keep its reference for a while since we will add cels into it
                        row.append($("<td>" + data[i].description + "</td>"));
                        row.append($("<td>" + data[i].caterer + "</td>"));
                        row.append($("<td>" + data[i].date + "</td>"));
                    }
                    $("#lunches-table").append('</tbody>');
                })
            });

            $("#submit2").click(function(event) {
                if($.cookie('userid') === null || $.cookie('userid') === "" || /*typeof*/ $.cookie('userid') == 'undefined') {
                    alert("Please login again");
                    window.location = "http://agarwal.mobileplusinc.com";
                }
                $.ajax({
                    url: '/api/lunches.php?',
                    data: {"name" : $("#writeLunchName").val(), "description" : $("#lunchDesc").val(), "caterer" : $("#writeCatererName").val(), "date" : window.today},
                    type: 'post',
                }) .done(function(data) {
                    alert("Your lunch has been submitted.");
                    $("#writeLunch").hide();
                    $("#deleteLunch").show();
                    $('#deleteReview').hide();
                    first();
                }) 
            });
            $("#deleteLunch").click(function(event){
                if(/*typeof*/ $.cookie('userid') == 'undefined') {
                    alert("Please login again");
                    window.location = "http://agarwal.mobileplusinc.com";
                }
                $.ajax({
                    url: '/api/lunches.php?date=' + window.today,  
                    type: 'delete',
                    dataType: "json",
                }) .done(function(data) {
                    alert("Your lunch has been deleted.");
                    $("#deleteLunch").hide();
                    $("#writeLunch").show();
                    $('#deleteReview').show();
                    first();
                }) 
            });

            $('#deleteReview').click(function(event){
                if(/*typeof*/ $.cookie('userid') == 'undefined') {
                    alert("Please login again");
                    window.location = "http://agarwal.mobileplusinc.com";
                }
                $.ajax({
                    url: '/api/reviews.php?lunch_id=' + window.lunchid + "&user_id=" + $.cookie('userid'),  
                    type: 'delete',
                    dataType: "json",
                }) .done(function(data) {
                    alert("Your review has been deleted.");
                    $("#deleteLunch").show();
                    $('#deleteReview').hide();
                    first();
                }) 
            });

            $('#logout').click(function(event) {
                $.removeCookie('userid');
                $.removeCookie('usertype');
                window.location= 'http://agarwal.mobileplusinc.com';
            });
            






