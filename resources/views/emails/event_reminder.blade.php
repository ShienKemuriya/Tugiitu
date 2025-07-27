<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>配信リマインダー通知</title>
    <style>
        /*
         * メールクライアント互換性のため、CSSはできるだけシンプルに
         * またはインラインスタイルを使用することを推奨します。
         * ここでは基本的なスタイルを<style>ブロックに記述しています。
         */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            line-height: 1.6;
            color: #333;
            background-color: #f7f7f7; /* スクリーンショットの背景に似た薄い灰色 */
            margin: 0;
            padding: 20px 0;
            -webkit-text-size-adjust: 100%; /* iOSのテキストサイズ調整を無効化 */
            -ms-text-size-adjust: 100%;    /* Windows Phoneのテキストサイズ調整を無効化 */
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff; /* 白いコンテンツエリア */
            border-radius: 8px; /* やや丸い角 */
            box-shadow: 0 2px 4px rgba(0,0,0,0.05); /* 控えめな影 */
            padding: 30px;
            border: 1px solid #e0e0e0; /* 薄い境界線 */
        }
        h1 {
            color: #1a202c; /* 見出しの濃い色 */
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center; /* 中央寄せ */
        }
        p {
            margin-bottom: 15px;
        }
        strong {
            font-weight: 600; /* 少し太め */
            color: #1a202c;
        }
        .button {
            display: inline-block;
            background-color: #2d3748; /*ボタンの色*/
            color: #ffffff !important; /* メールクライアントで色が変わらないように */
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none; /* 下線なし */
            font-weight: 600;
            font-size: 16px;
            margin-top: 20px;
            text-align: center;
            border: none;
        }
        .button-wrapper {
            text-align: center; /* ボタンを中央寄せ */
            margin-top: 25px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>配信リマインダー通知</h1>

        <p>こんにちは、{{ $notifiable->name }} さん</p>

        <p>「<strong>{{ $streamer->name }}</strong>」さんの配信がまもなく始まります。</p>

        <p>配信タイトル：<strong>{{ $event->title }}</strong></p>

        <p>開始時刻：<strong>{{ $event->start_time->format('Y年m月d日 H:i') }}</strong></p>

        <div class="button-wrapper">
            <a href="{{ url("/post/{$event->id}") }}" class="button">配信詳細を見る</a>
        </div>

        <p>引き続きアプリをお楽しみください！</p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} [あなたの会社名/サービス名]. All rights reserved.</p>
    </div>
</body>
</html>