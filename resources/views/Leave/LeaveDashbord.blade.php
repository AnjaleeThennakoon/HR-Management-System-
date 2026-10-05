<x-layout title="Leaves - HR System">
    <main>
        <h1>Leaves</h1>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($leaves as $leave)
                    <tr>
                        <td>{{ $leave->id }}</td>
                    </tr>
                @empty
                    <tr>
                        <td>No leaves found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <input type="hidden" id="edit_leave_id">
    </main>
</x-layout>