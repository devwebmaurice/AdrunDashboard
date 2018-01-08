@extends('layouts.adrun-auth')

@section('content')

<div class="row">

            <div class="col-md-6">
                <h2 class="font-bold">ADRUN DASHBOARD</h2>

                <p>
                   Notice: This is a Restricted Web Site for Official Adrun Business only. Unauthorized entry is prohibited and subject to prosecution under Title 18 of the U.S. Code.
                </p>

            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                    
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were problems with input:
                            <br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form class="m-t" role="form"method="POST" action="{{ url('login') }}">
                        
                        <input type="hidden"
                               name="_token"
                               value="{{ csrf_token() }}">
                        
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Email" required="" name="email" value="{{ old('email') }}">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" placeholder="Password" required="" name="password">
                        </div>
                        <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

                        <a href="#">
                            <small>Forgot password?</small>
                        </a>

                        <p class="text-muted text-center">
                            <input type="checkbox"
                                           name="remember"> Remember me
                        </p>
                    </form>
                    <p class="m-t">
                        <small>Adrun Dashboard System &copy; 2017</small>
                    </p>
                </div>
            </div>
        </div>

@endsection