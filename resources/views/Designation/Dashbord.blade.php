{{-- resources/views/Designation/Dashbord.blade.php --}}
<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Level</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($designations as $designation)
        <tr>
            <td>{{ $designation->name }}</td>
            <td>{{ $designation->level }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
