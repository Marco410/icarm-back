@extends('emails.layout')

@section('title', 'Invitación a registrarse')

@section('guestName')
{{ $data['guest']['name'] ?? 'Buen día' }}
@endsection

<style type="text/css">
        <!-- MDB 20/MAY/2020 -->
        <!-- Manejo de texto largo en el correo, para que el url no quede en un solo renglon y extienda el ancho del mail-->
        .long-text {
            white-space: pre;           /* CSS 2.0 */
            white-space: pre-wrap;      /* CSS 2.1 */
            white-space: pre-line;      /* CSS 3.0 */
            white-space: -pre-wrap;     /* Opera 4-6 */
            white-space: -o-pre-wrap;   /* Opera 7 */
            white-space: -moz-pre-wrap; /* Mozilla */
            white-space: -hp-pre-wrap;  /* HP Printers */
            word-wrap: break-word;      /* IE 5+ */
        }
</style>

@section('body')
<table  width="740" style="width:555.0pt" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td style="padding-bottom: 10px;">
            <table  width="740" style="width:555.0pt" border="0" cellspacing="0" cellpadding="0" bgcolor="#0e264b">
                <!-- <tr>
                    <td class="fluid-img" style="font-size:0pt; line-height:0pt; text-align:left;"><img src="http://201.107.4.81/loomdemy/web-app/assets/img/email/image1-mail.jpg" width="650" height="366" border="0" alt="" /></td>
                </tr> -->
                <tr>
                    <td class="p30-15" style="padding: 50px 30px;">
                        <table  width="740" style="width:555.0pt" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="h3 pb20" style="color:#ffffff; font-family:'Muli', Arial,sans-serif; font-size:25px; line-height:32px; text-align:left; padding-bottom:20px;">
                                    ¡Hola!
                                </td>
                            </tr>
                            <tr>
                                <td class="text pb20" style="color:#ffffff; font-family:Arial,sans-serif; font-size:14px; line-height:26px; text-align:left; padding-bottom:20px;">

									Te he enviado este correo como una invitación para que te registres a mi plataforma de cursos, en ella encontrarás información muy valiosa que te va a interesar. <br/>
									El proceso es simple, solo debes presionar el siguiente botón y llenar el formulario.

                                </td>
                            </tr>
                            <!-- Button -->
                            <tr>
                                <td align="center">
                                    <table class="center" border="0" cellspacing="0" cellpadding="0" style="text-align:center;">
                                        <tr>
                                            <td class="pink-button text-button" style="background:#ff6666; color:#c1cddc; font-family:'Muli', Arial,sans-serif; font-size:14px; line-height:18px; padding:12px 30px; text-align:center; border-radius:0px 22px 22px 22px; font-weight:bold;">
                                                <a href="{{$data['urlToRegister']}}" target="_blank" class="link-white" style="color:#ffffff; text-decoration:none;">
                                                    <span class="link-white" style="color:#ffffff; text-decoration:none;">Quiero registrarme</span>
                                                </a>
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                            <!-- END Button -->

                            <!--tr>
                                <td class="text pb20" style="color:#ffffff; font-family:Arial,sans-serif; font-size:14px; line-height:26px; text-align:left; padding-bottom:20px;">
									<br />
									También puedes usar la siguiente liga para acceder al formulario de registro, solo debes abrir una ventana de tu navegador de internet, copiarla y pegarla.
                                    <br />
                                    <pre style="width: 200px; white-space: pre; white-space: pre-wrap; white-space: pre-line; white-space: -pre-wrap; white-space: -o-pre-wrap; white-space: -moz-pre-wrap; white-space: -hp-pre-wrap; word-wrap: break-word;">
                                        <a href="{{$data['urlToRegister']}}" target="_blank">{{$data['urlToRegister']}}</a>
                                    </pre>

                                </td>
                            </tr-->

                            <tr>
                                <td class="text pb20" style="color:#ffffff; font-family:Arial,sans-serif; font-size:14px; line-height:26px; text-align:left; padding-bottom:20px;">

									Atentamente:
									<br />
									<h2>{{$data['host']['name'] ?? 'Lumy'}}</h2>

                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
@endsection
