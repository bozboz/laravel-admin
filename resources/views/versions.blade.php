<!DOCTYPE html>
<html>
<head>
    <title>Bozboz Versions</title>
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
    <style type="text/css">
        html {
            width: 100%;
            height: 100%;
            position: relative;
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            font-size: 100%;
            line-height: 1.42857143;
        }
        html, body {
            background-color: #eee;
        }
        .versions {
            margin: 2em auto;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 3px 5px 0 rgba(0, 0, 0, .5);
            background-color: white;
        }
        .versions table {
            margin-bottom: 0;
        }
        .versions th, .versions td {
            padding: 1em 1.5em;
        }
    </style>
</head>
<body>
    <div class="versions">
        <table class="table">
            <thead class="thead-inverse">
                <tr>
                    <th>Package</th>
                    <th>Version</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($packages as $package)
                <tr>
                    <td>{{ $package->name }}</td>
                    <td>{{ $package->version }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>