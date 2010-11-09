<h3>User-Submitted</h3>

<table class="span-24">
    <thead>
        <tr>
            <th align="left">Username</th>
            <th align="left">Name</th>
            <th align="left">Description</th>
            <th>Links</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<h3>GitHub Search Results</h3>

<table class="span-24">
    <thead>
        <tr>
            <th align="left">Username</th>
            <th align="left">Name</th>
            <th align="left">Description</th>
            <th>Links</th>
        </tr>
    </thead>
    <tbody>
    {{#search_results}}
        <tr>
            <td>{{username}}</td>
            <td>{{name}}</td>
            <td>{{description}}</td>
            <td align="center">
                <a href="http://github.com/{{username}}/{{name}}">GitHub</a>
                <a href="/admin/modules/add?username={{username}}&amp;name={{name}}">Add</a>
                <a href="/admin/modules/ignore?username={{username}}&amp;name={{name}}&amp;from=search">Ignore</a>
            </td>
        </tr>
    {{/search_results}}
    </tbody>
</table>
