<!DOCTYPE html>
<html>
    <body>
        <p>
            @if ($type == 48)

                {{ $session['full_name'] }},
                I'm looking forward to our session together on {{ $session['date'] }} {{ $session['start_end_time'] }}. You can come prepared with something you'd like to explore or I can provide several prompts for discussion. Please note that you have 24 hours to cancel or reschedule if something has come up.
                (Reschedule / Cancel) Button
                -{{ $session['coach_name'] }}

            @elseif ($type == 24)

                {{ $session['full_name'] }},
                I'm looking forward to our session together on {{ $session['date'] }} {{ $session['start_end_time'] }}. You can come prepared with something you'd like to explore or
                I can provide several prompts for discussion.
                -{{ $session['coach_name'] }}

            @elseif ($type == 60)

                {{ $session['full_name'] }},
                Friendly reminder that your session begins with {{ $session['coach_name'] }} @ {{ $session['start_end_time'] }}.
                Best!

            @endif
    </body>
</html>
