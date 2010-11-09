<div class="span-11 colborder">

    <h3>Open Tickets</h3>
    {{open_tickets}}

    <h3>New Search Results</h3>
    {{search_results}}

    <h3>Pending Deletion</h3>
    {{pending_deletion}}

    <h3>Next Cron</h3>
    {{next_cron.hours}} hours, {{next_cron.minutes}} minutes, {{next_cron.seconds}} seconds

</div>

<div class="span-12 last">

    <h3>Newest Modules</h3>
    <table>
        <tbody>
        {{#newest_modules}}
            <tr>
                <td>{{username}}/{{name}}</td>
                <td>{{created_at}}</td>
            </tr>
        {{/newest_modules}}
        </tbody>
    </table>

    <h3>Recently Updated</h3>
    <table>
        <tbody>
        {{#recently_updated}}
            <tr>
                <td>{{username}}/{{name}}</td>
                <td>{{updated_at}}</td>
            </tr>
        {{/recently_updated}}
        </tbody>
    </table>

</div>
