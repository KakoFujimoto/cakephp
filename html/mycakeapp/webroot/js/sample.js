function countdownTimer() {
    //現在日時を取得
    var nowTime = new Date();
    //オークション終了日時を取得
    var endTime = new Date('$biditem->endtime');
    //オークション終了日時までの差分を取得（ミリ秒単位）
    var timeDifference = Math.floor(endTime - nowTime);
    if (timeDifference >= 0) {
        //一日をミリ秒で表した数値
        var oneDay = 24 * 60 * 60 * 1000;
        //日数差分を取得
        var days = Math.floor(timeDifference / oneDay);
        //差分時間を取得
        var hours = Math.floor((timeDifference % oneDay) / (60 * 60 * 1000));
        //差分分数を取得
        var minutes = Math.floor((timeDifference % oneDay) / (60 * 1000)) % 60;
        //差分秒数を取得
        var seconds = Math.floor((timeDifference % oneDay) / 1000) % 60 % 60;
        //HTML上に出力
        var limitTime = days + "日" + hours + "時間" + minutes + "分" + seconds + "秒";
        document.getElementById("timer").innerHTML = limitTime;
        //1秒ごとに処理を繰り返す仕組み
        setTimeout(countdownTimer, 1000);
    } else {
        document.getElementById("timer").innerHTML = "終了済み";
    }
}
countdownTimer();
