

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee ID Card</title>
<style>

body{
    margin:0;
    padding:0;
    font-family:Arial, Helvetica, sans-serif;
}

.card{
    width:95mm;
    height:148mm;
    background:url('img/cardd.png');
    background-image-resize:2;
    position:relative;
    background-size:cover;
    margin:0 auto;
}

.company{
    font-size:24px; 
    font-weight:bold;
    color:#072d67;
}

.address{
    font-size:12px;
    color:#333;
}

.details{
    width:100%;
    border-collapse:collapse;
    margin-top:30px;
    margin-left:50px;
    
}
    

.details td{
    font-size:11px;
    padding:2px 0;
    vertical-align:top;
    
}

.label{
    width:38%;
    font-weight:bold;
}

.colon{
    width:2%;
    text-align:center;
}
 
.value{
    width:60%;
}
    

</style>
</head>

<body>

<div class="card">

<table width="100%" cellpadding="0" cellspacing="0">

    <!-- TOP SPACE -->
    <tr>
        <td colspan="3" height="40"></td>
    </tr>

    <!-- COMPANY -->
    <tr>
    <td colspan="3" align="center">

        <table width="100%" style="text-align:center;">
            <tr>

                <td width="20%" style="text-align:center;">
                    <img src="img/logo1.png" width="70">
                </td>

                <td  >
                    <div class="company">
                        '.$company_name.'
                    </div>
                      <hr style="color:#ff3c00; background-color:#f22d00; height:1px; border:none; margin:2px 0;">

                    <div class="address">
                        '.$company_address.'
                    </div>
                </td>

            </tr>
        </table>

    </td>
</tr>

    <!-- SPACE BEFORE PHOTO -->
      <tr>
        <td colspan="3" height="0"></td>
    </tr>

    <!-- PHOTO -->
    <tr>
    <td colspan="3" align="center">

        <table style="
            width:40mm;
            height:40mm;
            border-collapse:collapse;
            margin-right:-1.5mm;
            margin-top:-2mm;
             
        ">
            <tr>
                <td style="
                    width:45mm;
                    height:45mm;
                    text-align:center;
                    vertical-align:middle;
                ">
                     '.(!empty($photo) ? '<img src="'.$photo.'" width="42mm" height="40mm">' : '').'
                </td>
            </tr>
        </table>

    </td>
</tr>

    <!-- NAME STRIP SPACE -->
    <tr>
        <td colspan="3" height="8"></td>
    </tr>

    <!-- NAME -->
  <tr>
    <td colspan="3" align="center" style=" padding-left:12px;">

        <div style="
            color:#ffffff;
            font-size:15px;
            font-weight:bold;
            text-transform:uppercase;
            letter-spacing:.3px;
        ">
            '.$name.'
        </div>

    </td>
</tr>

    <!-- SPACE AFTER NAME -->
    

    <!-- DETAILS -->
    <tr>

        <td width="10"></td>

        <td>

            <table class="details">

                <tr>
                  
                    <td class="label"> <img src="'.$user_pic.'" width="12" height="12">
        &nbsp;NAME</td>
                    <td class="colon">:</td>
                    <td class="value">'.$name.'</td>
                </tr>

                <tr>
                    <td class="label"><img src="'.$f_pic.'" width="12" height="12">
        &nbsp;F/H NAME</td>
                    <td class="colon">:</td>
                    <td class="value">'.$father.'</td>
                </tr>

                <tr>
                    <td class="label"><img src="'.$code_pic.'" width="12" height="12">
        &nbsp;E.CODE</td>
                    <td class="colon">:</td>
                    <td class="value">'.$ecode.'</td>
                </tr>

                <tr>
                    <td class="label"><img src="'.$blood_pic.'"width="10" height="10"> &nbsp;BLOOD GROUP</td>
                    <td class="colon">:</td>
                    <td class="value">'.$blood_group.'</td>
                </tr>

                <tr>
                    <td class="label"><img src="'.$position_pic.'" width="12" height="12">
        &nbsp;DESIGNATION</td>
                    <td class="colon">:</td>
                    <td class="value">'.$designation.'</td>
                </tr>

                <tr>
                    <td class="label"><img src="'.$suitcase_pic.'" width="12" height="12">
        &nbsp;DEPARTMENT</td>
                    <td class="colon">:</td>
                    <td class="value">'.$department.'</td>
                </tr>

                 

            </table>

        </td>

        <td width="10"></td>

    </tr>

    <!-- SIGNATURE SPACE -->
    <tr>
        <td colspan="3" height="6"></td>
    </tr>
    

    <!-- SIGNATURE -->
    <tr>
    <td colspan="3" align="center">

      '.(!empty($signature) ? '<img src="'.$signature.'" width="32mm">' : '').'

        <div style="
            font-size:8px;
            font-weight:bold;
             
        ">
            EMPLOYEE SIGNATURE
        </div>

    </td>
</tr>


</table>


</div>
<div style="
    position:absolute;
    bottom:1mm;
    left:0;
    width:100%;
    color:#FFFFFF;
">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>

            <!-- MOBILE -->
            <td width="50%" align="center">

                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <img src="'.$call.'" width="16" style="vertical-align:middle;">
                        </td>

                        <td style="padding-left:4px; text-align:left;">

                            <div style="font-size:10px; line-height:8px;color:white;">
                                MOBILE
                            </div>

                            <div style="font-size:12px; font-weight:bold; line-height:12px;color:white;">
                                '.$mobile.'
                            </div>

                        </td>
                    </tr>
                </table>

            </td>

            <!-- DIVIDER -->
            <td width="4%" align="center">
                <div style="height:10mm; border-left:1px solid #FFFFFF;"></div>
            </td>

            <!-- ALT -->
            <td width="20%" align="center">

                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <img src="'.$call.'" width="16" style="vertical-align:middle;">
                        </td>

                        <td style="padding-left:4px; text-align:left;">

                            <div style="font-size:8px; line-height:10px;color:white;">
                                EMERGENCY/HOME
                            </div>

                            <div style="font-size:12px; font-weight:bold; line-height:12px; color:white;">
                                '.$alt_mobile.'
                            </div>

                        </td>
                    </tr>
                </table>

            </td>

        </tr>
    </table>

</div>
</body>
</html>