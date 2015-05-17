google.load("visualization", "1", {packages:["corechart", "bar", "geochart"]});
google.setOnLoadCallback(drawRegionsMap);

function loadData()
{
    var options = {
        title: 'Volumes per country',
    };

    var options2 = {
        title: 'Volumes per country',
        animation: {
            duration: 1000,
            easing: 'out'
        },
        hAxis: {
            minValue: 0
        },
        legend: { position: "none" }
    };

    var options3 = {
        title: 'Messages per country',
        sizeAxis: {minValue: 0}
    };

    var period = 5;
    $.getJSON('/read.php', {chart: 'data', period: period}, function(data){


        var cntData = data.data.perCountries;
        var countries = data.data.countries;

        var columns = countries;
        columns.unshift('Total');
        columns.unshift('Date');
        var info = [];
        for (var date in cntData) {
            var counts = [];
            for (var cnt in cntData[date]) {
                counts.push(cntData[date][cnt])
            }
            var dt = $.format.date(new Date(parseInt(date) * 1000), "HH:mm:ss");
            counts.unshift(dt);
            info.unshift(counts);
        }
        info.unshift(columns);

        var dataShow = google.visualization.arrayToDataTable(info);
        window.chart.draw(dataShow, options);

        var data2 = [['Country', 'Volume']];

        var volumes = [];

        for (var date in cntData){
            var volume = cntData[date];
            delete volume.total;
            for (var cnt in volume) {
                volumes[cnt] = volumes[cnt] ? volumes[cnt] + volume[cnt] : volume[cnt];
            }
        }

        for (var cnt in volumes) {
            data2.push([cnt, volumes[cnt]]);
        }

        var dataShow2 = google.visualization.arrayToDataTable(data2);
        window.barChart.draw(dataShow2, options2);

        cntData = data.data.msgPerCountries;
        var total = Math.round(cntData.total * 100 / period) / 100;
        $("#msgPerSec").html(total + " total msgs per sec");
        delete cntData.total;
        info = [['Country', 'Msg/min']];
        for (var cnt in cntData) {
            info.push([cnt, cntData[cnt]]);
        }
        var dataShow3 = google.visualization.arrayToDataTable(info);
        window.mapChart.draw(dataShow3, options3);
    });

}

function drawRegionsMap() {
    window.chart = new google.visualization.AreaChart(document.getElementById('regions_div'));
    window.barChart = new google.visualization.BarChart(document.getElementById('barchart_material'));
    window.mapChart = new google.visualization.GeoChart(document.getElementById('map_div'));

    setInterval("loadData()", 5000);
}

$( document ).ready(function() {
    $("#start").click(function(){
        if ($(this).text() == 'Start!') {
            $(this).text('Stop!').removeClass('btn-success').addClass('btn-danger');
            window.myinterval = setInterval("sendMsg()", 50);
            $("#log").show();
        } else {
            $(this).text('Start!').addClass('btn-success').removeClass('btn-danger');
            clearInterval(window.myinterval);
            $("#log").hide();
        }
    });
});

function sendMsg()
{
    var amounts = {
        'US': 4400,
        'CA': 2100,
        'GB': 3200,
        'AU': 1500
    };

    var country = getCountry();
    var rate = 0.7471;
    var sell = Math.random() * 2000 + amounts[country];
    var buy = sell * rate;
    var msg = {
        "userId": "134256",
        "currencyFrom": "EUR",
        "currencyTo": "GBP",
        "amountSell": sell,
        "amountBuy": buy,
        "rate": rate,
        "timePlaced" : getTime(),
        "originatingCountry" : country
    };
    var str = JSON.stringify(msg);
    $("#log").text('Sent: ' + str);

    $.post('consume.php', str, function(data){
        // $("#res").text(JSON.stringify(data));
    });

}

function getCountry()
{
    var myArray = ['US', 'CA',  'GB', 'AU'];
    return myArray[Math.floor(Math.random() * myArray.length)];
}

function getTime()
{
    var now = new Date;
    var date = now.getTime() + now.getTimezoneOffset()*60*1000;
    return $.format.date(new Date(date), "dd-MMM-yy HH:mm:ss");
}
