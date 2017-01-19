<!DOCTYPE html>
<html>
<head>
    <title>Bozboz Versions</title>
    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
    <style type="text/css">
        html {
            width: 100%;
            height: 100%;
            position: relative;
            background-color: #eee;
            font-family: sans-serif;
        }
        table {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border-spacing: 0;
            border-collapse: collapse;

        }
        th, td {
            padding: .5em;
            text-align: left;
        }
        th {
            background-color: #ccc;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <th>Package</th>
            <th>Version</th>
        </tr>
        @foreach ($packages as $package)
            <tr>
                <td>{{ $package->name }}</td>
                <td>{{ $package->version }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>