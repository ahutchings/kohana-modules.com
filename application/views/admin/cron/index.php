<table class="span-24">
    <thead>
        <tr>
            <th>Name</th>
            <th>Time</th>
        </tr>
    </thead>
    <tbody>
    {{#jobs}}
        <tr>
            <td>{{name}}</td>
            <td>{{time_span.hours}} hours, {{time_span.minutes}} minutes, {{time_span.seconds}} seconds</td>
        </tr>
    {{/jobs}}
    </tbody>
</table>
