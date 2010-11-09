<h3>All Modules</h3>

<table class="span-24">
    <thead>
        <tr>
            <th>Id</th>
            <th>Username</th>
            <th>Name</th>
            <th>Description</th>
            <th>Created At</th>
            <th>Updated At</th>
        </tr>
    </thead>
    <tbody>
    {{#modules}}
        <tr>
            <td>{{id}}</td>
            <td><a href="http://github.com/{{username}}">{{username}}</a></td>
            <td><a href="http://github.com/{{username}}/{{name}}">{{name}}</a></td>
            <td>{{description}}</td>
            <td>{{created_at}}</td>
            <td>{{updated_at}}</td>
        </tr>
    {{/modules}}
    </tbody>
</table>
