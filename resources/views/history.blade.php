<html lang="en"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sender API</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/send_form.css" rel="stylesheet">

  </head>

  <body>
    @include('partials.navbar')
    <div class="container">
        <div class="form-signin">
            <h2 class="form-signin-heading">Sended files history</h2>
            <table class="table">
                <thead>
                    <td>ID</td>
                    <td>File</td>
                    <td>JSON Structure</td>
                    <td>Date of creation</td>
                </thead>
                @foreach($files as $file)
                    <tr>
                        <td>{{ $file->id }}</td>
                        <td>{{ $file->filename }}</td>
                        <td>{{ $file->structure }}</td>
                        <td>{{ $file->created_at }}</td>
                    </tr>
                @endforeach
            </table>
            <div class="row text-center">
                {{ $files->links() }}
            </div>
        </div>
    </div> <!-- /container --> 

</body></html>