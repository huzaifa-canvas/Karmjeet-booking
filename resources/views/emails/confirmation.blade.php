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
        <p>
            {{ $name }} ,<br/>
            Congratulations For Signing Up For  <br/>
        </p>
            <table>
                <tr>
                  <th>Sr.</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Metting link</th>
                  <th>Metting Password</th>
                </tr>
                @foreach ($dateTime as $key => $value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $value['date'] }}</td>
                        <td>{{ $value['time'] }}</td>
                        <td><a href="{{ $value['url'] }}" target="_blank" >{{ $value['url'] }}</a></td>
                        <td>123456</td>
                    </tr>
                @endforeach
              </table>
            <p>
                <br/>You have taken a significant step towards feeling better and creating your best life!<br/>
                <strong style="color: red">Please note you are able to cancel and reschedule up to 24 hours in advance without incurring a late cancellation fee ($75) or no show fee ($100).</strong>
                <br/>Looking forward to journeying with you!
                <br/> {{ ucwords($coach_name) }}
        </p>
    </body>
</html>

