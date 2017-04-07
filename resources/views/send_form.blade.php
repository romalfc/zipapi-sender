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
        {!! Form::open(array('class' => 'form-signin', 'files' => 'true')) !!}
            <h2 class="form-signin-heading">Sender API</h2>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <label for="username" class="sr-only">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Username" required="" autofocus="" value="{{ old('username') }}">
            <label for="password" class="sr-only">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password" required="" value="{{ old('password') }}">
            
            @if(isset($json) && !is_null($json))
                <label for="zip">{{ $filename }}</label>
                <input type="hidden" value="{{ $filename }}" name="filename">
                <input type="hidden" value="update" name="type">
                <button type="submit" name="submit" value="send" class="btn btn-lg btn-primary btn-block">Send</button>
                @include('partials.options')
            @else
                <label for="zip">Select zip-archive
                    <input type="file" name="zip" class="form-control" placeholder="ZIP" required="" value=" ">
                </label>
                <label for="e_method">Select encryption method
                    {!! Form::select('e_method', App\Helpers\Encryption::$ssl_methods); !!}
                </label>      
                <br><br>
                <input type="hidden" value="create" name="type">
                <button type="submit" name="submit" class="btn btn-lg btn-primary btn-block">Send</button>
            @endif            
        {!! Form::close() !!}
    </div> <!-- /container --> 
    @if(isset($json) && !is_null($json))
    <h2 class="form-signin-heading text-center">Result JSON:</h2>
    <div class="well container">
        <pre>
            {{ $json }}
        </pre>
    </div>
    @endif            

</body></html>