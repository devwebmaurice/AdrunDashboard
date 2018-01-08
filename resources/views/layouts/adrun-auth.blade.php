<!DOCTYPE html>
<html>

<head>
    
    @include('partials.adrun-head')
    
</head>

<body class="gray-bg">

    <div class="loginColumns animated fadeInDown">
        
        @yield('content')
        
        
        
        <hr/>
        <div class="row">
            <div class="col-md-6">
                Adrun © 2017
            </div>
            <div class="col-md-6 text-right">
               <small>V 1.0.8</small>
            </div>
        </div>
    </div>
@include('partials.javascripts')
</body>

</html>

