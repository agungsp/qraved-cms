<table>
    <thead>
    <tr>
        <th>#</th>
        <th>id</th>
        <th>qraved_user_mapping_id</th>
        <th>qraved_user_token</th>
        <th>email</th>
        <th>contact</th>
        <th>gender</th>
        <th>birth_date</th>
        <th>interest</th>
        <th>job</th>
        <th>created_at</th>
        <th>updated_at</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr>
            <th>{{ $loop->iteration }}</th>
            <th>{{ $user->id }}</th>
            <th>{{ $user->qraved_user_mapping_id }}</th>
            <th>{{ $user->qraved_user_token }}</th>
            <th>{{ $user->email }}</th>
            <th>{{ $user->contact }}</th>
            <th>{{ $user->gender }}</th>
            <th>{{ $user->birth_date }}</th>
            <th>{{ $user->interest }}</th>
            <th>{{ $user->job }}</th>
            <th>{{ $user->created_at }}</th>
            <th>{{ $user->updated_at }}</th>
        </tr>
    @endforeach
    </tbody>
</table>
