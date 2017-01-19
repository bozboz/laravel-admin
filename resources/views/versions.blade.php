<!DOCTYPE html>
<html>
<head>
    <title>Bozboz Versions</title>
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
    <style type="text/css">
        html {
            font-family: -apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #292b2c;
        }
        html, body {
            background-color: #eee;
        }
        .versions {
            margin: 2em auto;
            width: 100%;
            min-width: 340px;
            max-width: 600px;
        }
        table {
            margin-bottom: 0;
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 3px 5px 0 rgba(0, 0, 0, .5);
        }
        th, td {
            padding: 1em 1.5em;
        }
        th {
            background-color: #262626;
            color: white;
            text-align: left;
            border-bottom: 3px solid rgba(0,0,0,.1);
        }
        td {
            border-bottom: 1px solid rgba(0,0,0,.05);
        }
        tfoot td {
            text-align: right;
            padding: .5em 1.5em;
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
            <tfoot>
                <tr>
                    <td colspan="2">
                        @if (request()->has('all'))
                            <a href="{{ url(request()->path()) }}">Show only Bozboz</a>
                        @else
                            <a href="{{ url(request()->path()) }}?all=1">Show all</a>
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>