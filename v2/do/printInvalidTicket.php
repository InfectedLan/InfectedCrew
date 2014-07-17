<?php
require_once '../api/security.php';
  require_once '../api/error.php';
  require_once '../api/user.php';
  require_once '../api/mysql.php';
  require_once '../api/avatar.php';
  session_start();
  if(!isset($_SESSION["username"])||User::getCrewChiefing($_SESSION["username"])=="")
  {
    die('ACCESS DENIED<br /><img src="http://c22blog.files.wordpress.com/2012/10/skiidie.jpg" />');
  }
?>
	<script type="text/javascript" src="code39.js"></script>
	<script type="text/javascript">
<!--
window.print();
//-->
</script>
<style>
body {
height: 842px;
text-align: center;
}

#total {
  border-style:solid;
margin: 0 auto;
text-align: left;
width: 810px;
height: 470px;
/*background: #f0f0f0;*/
padding-left: 60px;
padding-top: 60px;
font: 14px Arial;
border-width: 1px;
border-color: #f0f0f0;
}

#total td {
height: 30px;
}

h2 {
margin: 4px;
margin-bottom: 30px;
font-family: Arial;
font-size: 30px;
}
#inputdata {
float: right;
margin-right: 130px;
margin-top: 10px;
}
#tekstprint {
width: 45%;
float:left;
margin-top: 55px;
margin-right: 10px;
}

#logo {
width: 50%;
float: right;
margin-top: 35px;
margin-left: 20px;

}
</style>
</head>
<body onload="printpage()">
<div id="total">
<h2>Billettnr: infected_v_2014_UGYLDIG_BILETT</h2><table>
<tr><td width='100px'><b>Navn:</b></td><td>UGYLDIG</td></tr><tr><td><b>Født:</b></td><td>29.11.1998</td></tr><tr><td><b>Adresse:</b></td><td>Husveien 2</td></tr><tr><td><b>Mobil:</b></td><td>94132789</td></tr><tr><td><b>Brukernavn:</b></td><td>xxJensxx</td></tr><tr><td><b>Sete:</b></td><td>R12S2</td></tr><tr><td colspan="2"><b>Innsjekking: </b>Forsiden av Asker Kulturhus(Bibilioteket)</td></tr><div id='inputdata'><img src="../api/getQR.php?data=https%3A%2F%2Ftickets.infected.no%2Fekstra%2Fqrcheckin.php%3Fid%3DYOMOMA" width="200%"></div>

</div>

<br />
<!--
<script type="text/javascript">
/* <![CDATA[ */
  function get_object(id) {
   var object = null;
   if (document.layers) {
    object = document.layers[id];
   } else if (document.all) {
    object = document.all[id];
   } else if (document.getElementById) {
    object = document.getElementById(id);
   }
   return object;
  }
get_object("inputdata").innerHTML=DrawCode39Barcode(get_object("inputdata").innerHTML,1);
/* ]]> */
</script>
-->
</div>
</div></table><div id="tekstprint">Denne billetten skal vises ved innsjekking på Radar. Husk å ta med gyldig legitimasjon. De under 14 må ha med
bekreftelse fra foreldre. Skjema på nettsiden.</div><div id="logo"><img src="https://tickets.infected.no/images/logo_infected.jpg" width="60%"></div>
</div>