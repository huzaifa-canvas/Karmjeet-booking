<!DOCTYPE html>
<html>
    <head>
        <style>
        table {
          font-family: arial, sans-serif;
          border-collapse: collapse;
          width: 100%;
        }

        td, th {
          border: 1px solid #dddddd;
          text-align: left;
          padding: 8px;
        }

        tr:nth-child(even) {
          background-color: #dddddd;
        }
        </style>
        </head>
    <body>
        <table>
            <tr>
                <th>Name</th>
                <td>{{ $data["name"] }}</td>
            </tr>
            <tr>
                <th>Email</th>
                 <td>{{ $data["email"] }}</td>
            </tr>
            <tr>
                <th>Type</th>
                <td>{{ $data["type"] }}</td>
            </tr>
            <tr>
                <th>Message</th>
                <td>{{ $data["message"] }}</td>
            </tr>
        </table>
    </body>
</html>

