<table>
    <thead>
    <tr>
        <th>#</th>
        <th>user_id</th>
        <th>user_email</th>
        <th>resto_id</th>
        <th>resto_name</th>
        {{-- <th>question_id</th> --}}
        <th>question</th>
        <th>answer_status</th>
        <th>action</th>
        <th>created_at</th>
    </tr>
    </thead>
    <tbody>
    @foreach($logs as $log)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $log->user->qraved_user_mapping_id ?? '-' }}</td>
            <td>{{ $log->user->email ?? '-' }}</td>
            <td>{{ $log->restaurant->id ?? '-' }}</td>
            <td>{{ $log->restaurant->name ?? '-' }}</td>
            {{-- <td>{{ $log->question->id ?? '-' }}</td> --}}
            <td>{{ $log->question->name ?? '-' }}</td>
            <td>
                @empty($log->answer)
                    -
                @else
                    {{ $log->answer->status ? 'correct' : 'incorrect' }}
                @endempty
            </td>
            <td>{{ $log->action ?? '-' }}</td>
            <td>{{ $log->created_at ?? '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
