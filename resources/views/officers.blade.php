<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officers - District Secretariat Vavuniya</title>
    <link href='icons/right_logo.png' rel='icon' type='image/png'>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .banner {
            background: linear-gradient(180deg, #7dd3d9 0%, #a8e6ea 100%);
            min-height: 65vh; /* Use min-height instead of fixed height */
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .page-header h2 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .page-header p {
            font-size: 1.1em;
            color: #555;
        }

        table {
            width: 90%; /* Adjust table width */
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            background-color: #fff;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .add-officer-btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #28a745; /* Green for add button */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .add-officer-btn:hover {
            background-color: #218838;
        }

        .action-btn {
            padding: 8px 12px;
            margin: 2px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
            font-size: 0.9em;
            transition: background-color 0.3s ease;
        }

        .action-btn:hover {
            opacity: 0.9;
        }

        .action-btn:nth-of-type(1) { /* Modify button */
            background-color: #ffc107;
            color: #333;
        }

        .action-btn:nth-of-type(2) { /* Delete button */
            background-color: #dc3545;
        }
    </style>
</head>
<body>
   @include('partials.header_nav')
   
    <!-- Cyan/Turquoise Banner Section -->
    <section class="banner">
        @if(session('success'))
            <div class="alert alert-success" style="background-color: #d4edda; border-color: #c3e6cb; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 4px; width: 90%; max-width: 900px;">
                {{ session('success') }}
            </div>
        @endif
        <div class="page-header">
            <h2 style="color: rgb(6, 4, 60); font-weight: bold">Officers List</h2>
            <p>Manage officers by modifying or deleting entries</p>
        </div>

        <!-- Add Officer Button -->
        <div style="text-align: center; margin-bottom: 20px;">
            <a href="createaccount" class="add-officer-btn">Add Officer</a>
        </div>

        <!-- Officer Table -->
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->user_id }}</td>
                    <td>{{ "{$user->first_name} {$user->last_name}" }}</td>
                    <td>{{ $user->designation }}</td>
                    <td>
                        <button class="action-btn" onclick="modifyOfficer('{{ $user->user_id }}')">Modify</button>
                        <button class="action-btn" onclick="deleteOfficer('{{ $user->user_id }}')">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    @include('partials.footer')

    <script>
        function modifyOfficer(userId) {
            window.location.href = '/users/' + userId + '/edit';
        }

        function deleteOfficer(userId) {
            if (confirm('Are you sure you want to delete this officer? This action cannot be undone.')) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '/users/' + userId;

                let csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                let methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>