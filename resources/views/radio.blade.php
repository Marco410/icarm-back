

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
               padding:20px;
               display: flex;
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
                font-weight: 600;
                font-size: 25px;
                margin-bottom: 10px;
           }

           p{
            font-size: 13px;
           }
   
           img {
               border-radius: 25px;
               margin-top: 10px;
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
           }
   
   </style>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ICARM App 📱</title>
    <link rel="icon" href="{{ asset('assets/logo_back.png') }}" type="image/png">

    
    <!-- Metadatos para SEO -->
    <meta name="description" content="💒 Amor & Restauración Morelia - Conéctate con nuestra comunidad a través de la app oficial.">
    <meta name="keywords" content="iglesia, amor, restauración, morelia, comunidad, fe, app, cristiana">
    <meta name="author" content="ICARM">

    <!-- Open Graph para redes sociales -->
    <meta property="og:title" content="Radio | Amor & Restauración Morelia | App Oficial ">
    <meta property="og:description" content="Escucha nuestra radio las 24 hrs del día | Descarga la app oficial de Amor & Restauración Morelia y conéctate con nuestra comunidad donde quiera que estés. 🙏">
    <meta property="og:image" content="https://zeno.fm/_next/image/?url=https%3A%2F%2Fimages.zeno.fm%2FoU_QTjtJrboK2rm3nPb8NiKuieHzoQuYg06OF-85A8U%2Frs%3Afit%3A240%3A240%2Fg%3Ace%3A0%3A0%2FaHR0cHM6Ly9zdHJlYW0tdG9vbHMuemVub21lZGlhLmNvbS9jb250ZW50L3N0YXRpb25zLzJmNjE2OTI2LTkzOGQtNDNmZC1iYjBiLTBiMDM0M2ExMmFhMS9pbWFnZS8_dXBkYXRlZD0xNzE5NDYwNDEyMDAw.webp&w=3840&q=100">
    <meta property="og:url" content="https://www.amoryrestauracionmorelia.com"> 
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Radio | Amor & Restauración Morelia - App Oficial 📱">
    <meta name="twitter:description" content="Escucha nuestra radio las 24 hrs del día | Descarga la app oficial de Amor & Restauración Morelia y conéctate con nuestra comunidad. 🙏💒">
    <meta name="twitter:image" content="{{ asset('assets/logo_back.png') }}">

</head>
<body>

    <div class="container">
        <h1>Amor & Restauración Morelia</h1>
        <a href="https://www.amoryrestauracionmorelia.com">
            <img src="{{ asset('assets/logo_back.png') }}" height="120px" alt="Logo app"> 
        </a>
        <p style="font-weight: bold">¡Descubre la Aplicación de Nuestra Iglesia! 📱</p>
        <p>Bienvenido(a) a la aplicación oficial de nuestra iglesia Amor y Restauración Morelia, donde podrás experimentar una conexión espiritual más profunda y mantenerte al tanto de todas las actividades y eventos importantes. 📆 🗒️</p>
        <div id="btn-descargar" class="btn">Descargar</div>
    </div>
    
</body>
</html>

<script>

        redirect();
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