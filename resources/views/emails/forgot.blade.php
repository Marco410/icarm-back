<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE-edge" >
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.bootstrap4.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.css">
        
    <style type="text/css" >
        .image{
           border-radius: 20px;
           margin:15px;
        }
    </style>
    </head>
    <body>
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-12 text-center">
                                    <img src="{{ asset('assets/logo.png') }}" class="image" height="100px" alt=""> <br>

                                    <h1>Recuperación de Contraseña</h1>
                                    <p><strong>Estimado, {{ $user->nombre }} {{ $user->apellido_paterno }}</strong></p>
                                    <p>Espero que este mensaje te encuentre bien. Hemos recibido una solicitud para restablecer la contraseña asociada a tu cuenta en nuestra aplicación.</p>
                                    <h3>Tu nueva contraseña</h3>
                                    <p>Por ahora hemos preparado esta nueva contraseña temporal con la que podrás acceder, te recomendamos que una vez que accedas, cambies la contraseña por una que te pueda ser familiar.</p>

                                    <p>Tu contraseña: <strong>{{$pass}}</strong></p>
   
                                    <p>De parte de todo el equipo de desarrollo de Amor y Restauración Morelia</p>
                                    <p>Saludos</p>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </body>
</html>