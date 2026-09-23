{{-- resources/views/Designation/Dashbord.blade.php --}}
<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Upper Level</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($designations as $designation)
        <tr>
            <td>{{ $designation->name }}</td>
            <td>{{ $designation->upper_level }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
