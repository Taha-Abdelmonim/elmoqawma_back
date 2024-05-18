@php
	$medal = "gold.jpeg";
	if ($certificate->score < 85) {
		$medal = "silver.jpeg";
	}
@endphp
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Staff Get Salaries</title>
	<style type="text/css">
      * {
          font-family: "XBRiyaz", sans-serif !important;

      }


      .container {
          text-align: center;
          background-image: url("{{public_path('images/global/certificate_'.$medal)}}");
          background-repeat: no-repeat;
          background-size: 100% 100%;
          width: 1280px;
          height: 600px;
          display: inline-block;
          /*padding-top: 100px;*/

      }


      .centered {
          width: 100%;
          margin-top: -5em;
      }


      .centered h1 {
          font-size: 1.6rem;
          color: #000000;
          padding-top: 260px;
          margin-bottom: 0em;
          margin-right: -10em;
      }

      .container h2 {
          float: left;
          font-size: 1rem;
          color: #000000;
          margin-right: -16.5em;
          padding-top: 120px;
      }

      .centered p {
          font-size: 1rem;
          font-weight: bold;
          color: #000000;
          margin-top: 50px;
          margin-right: -29em;
      }


	</style>
</head>

<body dir="rtl">
<div class="container">
	<div class="centered">
		<h1>{{$certificate->username}}</h1>
		<h2>{{$certificate->score}}%</h2>
		<p>{{ \Carbon\Carbon::parse($certificate->created_at)->format('Y/m/d') }}</p>

	</div>
</div>

</body>

</html>