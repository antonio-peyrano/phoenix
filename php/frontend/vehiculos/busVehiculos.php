<?php     
    $sufijo= "veh_";
    
    echo '  <html>
                <link rel= "stylesheet" href= "./css/queryStyle.css"></style>        
                <center><div id= "divbusqueda">
                    <form id="frmbusqueda" method="post" action="">
                        <table class="queryTable" colspan= "7">
                            <tr><td class= "queryRowsnormTR" width ="180">Por numero econ�mico: </td><td class= "queryRowsnormTR" width ="250"><input type= "text" id= "numeco"></td><td rowspan= "3"><img id="'.$sufijo.'buscar" align= "left" src= "./img/grids/view.png" width= "25" height= "25" alt="Buscar"/></td></tr>
                            <tr><td class= "queryRowsnormTR">Por numero de placa: </td><td class= "queryRowsnormTR"><input type= "text" id= "numplaca"></td><td></td></tr>
                            <tr><td class= "queryRowsnormTR">Por periodo: </td><td class= "queryRowsnormTR"><input type= "text" id= "vehperiodo"></td><td></td></tr>
                        </table>
                    </form>
                </div></center>';
    
    echo '<div id= "busRes">';
        include_once("catVehiculos.php");
    echo '</div>
          </html>';
    
?>