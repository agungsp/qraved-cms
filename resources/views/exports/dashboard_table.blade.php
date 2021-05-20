<table>
    <thead>
    <tr>
        <th>#</th>
        <th>date_start</th>
        <th>date_end</th>
        <th>restaurant</th>
        <th>count</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $row['date_start'] ?? '-' }}</td>
            <td>{{ $row['date_end'] ?? '-' }}</td>
            <td>{{ $row['restaurant'] ?? '-' }}</td>
            <td>{{ $row['count'] ?? '0' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
