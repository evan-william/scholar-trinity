<table>
    <thead>
    <tr>
        @foreach(array_keys($rows->first() ?? []) as $heading)
            <th>{{ $heading }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)
        <tr>
            @foreach($row as $value)
                <td>{{ $value }}</td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
