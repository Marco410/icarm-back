

<!DOCTYPE html>
<html lang="es">
<head>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <style>
        html,
           body {
               background-color: #F4F5F7;
               color: #1A2228;
               font-family: 'Nunito', sans-serif;
               justify-content: center;
               margin: 0;
               height: 100vh;
               padding: 10px;
               display: flex;
               margin-bottom: 20px;
            }

            .container {
                max-width: 600px;
                width: 100%;
                text-align: center;
                background-color: white; 
                padding: 20px; 
                border-radius: 10px; 
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
                height: fit-content;

            }

            
            h1 {
                font-weight: bold;
                font-size: 30px;
                margin-bottom: 10px;
           }

           h2 {
                font-weight: 500;
                font-size: 20px;
               margin-bottom: 10px;
           }

           h3 {
                font-weight: bold;
                font-size: 18px;
                margin-bottom: 10px;
           }

           p{
            font-size: 13px;
           }
   
           img {
               border-radius: 8px;
               margin-top: 10px;
               max-width: 500px;
            }
            
            .event{
               width: 100%;
           }

           .logo{
                border-radius: 25px;
                margin-top: 10px;
                height: 70px;
           }

           .btn {
                background: #454FD8;
                color: white;
                padding: 5px;
                border-radius: 8px;
                margin: 30px 10px 10px 10px;
                font-size: 12px;
                font-weight: bold;
                cursor: pointer;
                text-decoration: none !important;
           }
   
   </style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$evento->nombre}} 📆</title>
    <link rel="icon" href="{{ asset('assets/logo_back.png') }}" type="image/png">

    
    <!-- Metadatos para SEO -->
    <meta name="description" content="💒 Amor & Restauración Morelia - {{$evento->nombre}} - {{$evento->descripcion}} - Conéctate con nuestra comunidad a través de la app oficial.">
    <meta name="keywords" content="iglesia, amor, restauración, morelia, comunidad, fe, app, cristiana">
    <meta name="author" content="ICARM">

    <!-- Open Graph para redes sociales -->
    <meta property="og:title" content="{{$evento->nombre}} | Amor & Restauración Morelia ">
    <meta property="og:description" content="Descarga la app oficial de Amor & Restauración Morelia para conocer los detalles del evento. 📆">
    <meta property="og:image" content="{{ asset('eventos/' . $evento->id . '/' . $evento->img_horizontal) }}">
    <meta property="og:url" content="https://www.amoryrestauracionmorelia.com"> 
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Amor & Restauración Morelia - App Oficial 📱">
    <meta name="twitter:description" content="Descarga la app oficial de Amor & Restauración Morelia para conocer los detalles del evento. 📆">
    <meta name="twitter:image" content="{{ asset('eventos/' . $evento->id . '/' . $evento->img_horizontal) }}">

</head>
<body>

    <div class="container">
        <div>
            <img src="{{ asset('assets/logo_back.png') }}" class="logo"  alt="Logo app"> 

            <h2>Amor & Restauración Morelia</h2>
        </div>
        <h1>{{$evento->nombre}}</h1>
        <a href="https://www.amoryrestauracionmorelia.com">
            <img src="{{ asset('eventos/' . $evento->id . '/' . $evento->img_horizontal) }}" class="event"  alt="Logo app"> 
        </a>
        <h3 style="font-weight: bold">Fecha, hora y ubicación</h3>
        <p>📆 {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d M') }} - 
            {{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d M') }} | 
            {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('h:i A') }}</p>
        
  
        <div id="btn-descargar" class="btn">Descargar la aplicación para más detalles</div>
    </div>
       
    <script>

        const downloadButton = document.getElementById('btn-descargar');

        downloadButton.addEventListener('click', function() {
            redirect();
        });

        function redirect(){

            const playStoreUrl = "https://play.google.com/store/apps/details?id=com.mtm.icarm";
            const appStoreUrl = "https://apps.apple.com/mx/app/ayr-morelia/id6449270971?l=en-GB";
            const userAgent = navigator.userAgent.toLowerCase();
        
            if (userAgent.includes('iphone') || userAgent.includes('ipad') || userAgent.includes('macintosh')) {
                // Dispositivos de Apple
                window.location.href = appStoreUrl;
            } else if (userAgent.includes('android') || userAgent.includes('windows')) {
                // Dispositivos Android o Windows
                window.location.href = playStoreUrl;
            } else {
                // Opción por defecto si no se detecta un dispositivo compatible
                console.log("Dispositivo no compatible detectado.");
            } 
        }


</script>
</body>
</html>
