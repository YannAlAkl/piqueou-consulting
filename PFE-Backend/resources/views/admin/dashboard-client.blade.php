<x-app-layout>
    <h1>Dashboard Clients</h1>
    <table>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->account_status }}</td>
            </tr>
        @endforeach
    </table>
    {{ $users->links() }}
</x-app-layout>
